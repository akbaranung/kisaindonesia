<?php

namespace App\Livewire\Story;

use Livewire\Component;
use App\Models\Story;
use App\Models\Chapter;
use App\Models\ReadHistory;
use App\Models\User;
use App\Models\UserTransaction;
use App\Services\StoryViewService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoryReader extends Component
{
    public $story;
    public $chapter;
    public $chatRows = [];
    public $totalRows = 0;
    public $regularContent = '';

    public $visibleCount = 1;

    public $prevSlug = null;
    public $nextSlug = null;

    public $isLocked = false;
    public $showUnlockModal = false;

    const AUTHOR_SHARE_PERCENTAGE = 50;
    const ADMIN_SHARE_PERCENTAGE = 50;

    public function mount(Story $story, Chapter $chapter, StoryViewService $viewService)
    {
        if ($story->status !== 'published' || $chapter->status !== 'published' || $chapter->story_id !== $story->id) {
            abort(404);
        }

        $this->story = $story;
        $this->chapter = $chapter;
        $author = $story->user;
        $reader = Auth::user();

        if ($chapter->is_premium && $chapter->order_number > 0) {
            if (!auth()->check()) {
                $this->isLocked = true;
            } else {
                if ($author->id === $reader->id) {
                    $this->isLocked = false;
                } else {
                    // cek apakah user pernah membeli bab ini
                    $hasPurchased = DB::table('user_purchased_chapters')
                        ->where('user_id', auth()->id())
                        ->where('chapter_id', $chapter->id)
                        ->exists();

                    if (!$hasPurchased) {
                        $this->isLocked = true;
                    }
                }
            }
        }

        if (!$this->isLocked) {
            $contentData = $chapter->parseJsonData();
            $bubbles        = $contentData['bubbles'] ?? [];
            if ($chapter->type === 'chat') {
                $this->chatRows = $bubbles;

                $this->totalRows = count($bubbles);
                $this->story->load('characters');

                if (auth()->check()) {
                    $existingHistory = ReadHistory::where('user_id', auth()->id())->where('chapter_id', $chapter->id)->first();

                    if ($existingHistory) {
                        $this->visibleCount = $existingHistory->visible_chat_count;
                    }
                }
            } else {
                $cleaned = preg_replace('/color:\s*[^;"]+;?/i', '', $contentData['content']);
                $dataContent = preg_replace('/style="\s*;?\s*"/', '', $cleaned);

                $regularContent = $contentData['content'] ? $dataContent : '';
                $this->regularContent = $regularContent;
            }

            $this->saveProgress();
            $viewService->incrementView($chapter);
        }

        $prev = $story->chapters()->where('status', 'published')->where('order_number', '<', $chapter->order_number)->orderBy('order_number', 'desc')->first();
        $next = $story->chapters()->where('status', 'published')->where('order_number', '>', $chapter->order_number)->orderBy('order_number', 'asc')->first();

        $this->prevSlug = $prev ? $prev->slug : null;
        $this->nextSlug = $next ? $next->slug : null;
    }

    public function updateChatProgress($currentCount)
    {
        $this->visibleCount = $currentCount;
        $this->saveProgress();
    }

    public function unlockWithBeans()
    {

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $chapter = $this->chapter;
        $reader = Auth::user();
        $price = $this->chapter->bean_price;
        $author = $this->chapter->story->user;

        if ($reader->id === $author->id) {
            $this->showUnlockModal = false;
            session()->flash('error', 'Anda adalah penulis cerita ini.');
            return;
        }

        if ($reader->kisa_bean_balance < $price) {
            $this->showUnlockModal = false;
            session()->flash('error', 'Saldo KISA Bean kamu kurang, Bro! Silakan isi ulang dulu.');
            return;
        }

        $authorEarnedBeans = (int) floor(($price * self::AUTHOR_SHARE_PERCENTAGE) / 100);
        $adminEarnedBeans = $price - $authorEarnedBeans;

        DB::transaction(function () use ($reader, $price, $chapter, $authorEarnedBeans, $adminEarnedBeans, $author) {
            $refGroup = 'BUY-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Potong saldo user
            $reader->decrement('kisa_bean_balance', $price);

            // Record Transaksi Pembaca (SPEND)
            UserTransaction::create([
                'user_id'        => $reader->id,
                'reference_code' => $refGroup . '-R',
                'type'           => 'spend',
                'amount'         => $price,
                'gross_amount'   => 0,
                'payment_method' => 'KISA_BEAN',
                'status'         => 'success',
                'description'    => 'Membeli Bab ' . $chapter->order_number . ': ' . $chapter->story->title,
            ]);

            // B. Tambah Saldo Royalti Penulis (50%)
            $author->increment('earned_beans', $authorEarnedBeans);

            UserTransaction::create([
                'user_id'        => $author->id,
                'reference_code' => $refGroup . '-A',
                'type'           => 'earn',
                'amount'         => $authorEarnedBeans,
                'gross_amount'   => 0,
                'payment_method' => 'ROYALTY',
                'status'         => 'success',
                'description'    => 'Royalti Bab ' . $chapter->order_number . ' dari ' . $reader->name,
            ]);

            // C. Tambah Saldo / Record Admin KISA (50%)
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->increment('earned_beans', $adminEarnedBeans);

                UserTransaction::create([
                    'user_id'        => $admin->id,
                    'reference_code' => $refGroup . '-ADM',
                    'type'           => 'earn',
                    'amount'         => $adminEarnedBeans,
                    'gross_amount'   => 0,
                    'payment_method' => 'PLATFORM_SHARE',
                    'status'         => 'success',
                    'description'    => 'Bagian Admin KISA (50%) Bab ' . $chapter->order_number . ' dari ' . $reader->name,
                ]);
            }

            // Catat transaksi di tabel user_purchased_chapters
            DB::table('user_purchased_chapters')->insert([
                'user_id' => $reader->id,
                'chapter_id' => $this->chapter->id,
                'beans_spent' => $price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $this->isLocked = false;

        if ($this->chapter->type === 'chat') {
            if (is_string($this->chapter->content)) {
                $this->chatRows = json_decode($this->chapter->content, true) ?: [];
            } else {
                $this->chatRows = $this->chapter->content ?: [];
            }

            $this->totalRows = count($this->chatRows);
            $this->story->load('characters');
        }

        $this->saveProgress();
        session()->flash('success', 'Bab berhasil dibuka! Selamat membaca.');

        return $this->redirect(
            route('stories.chapter.read', [$this->story->slug, $this->chapter->slug]),
            navigate: true
        );
    }

    public function saveProgress()
    {
        if (auth()->check()) {
            ReadHistory::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'story_id' => $this->story->id
                ],
                [
                    'chapter_id' => $this->chapter->id,
                    'visible_chat_count' => $this->visibleCount
                ]
            );
        }
    }

    public function confirmUnlock()
    {
        if (!Auth::check()) {
            return $this->redirect('login', navigate: true);
        }

        $this->showUnlockModal = true;
    }

    public function cancelUnlock()
    {
        $this->showUnlockModal = false;
    }



    public function render()
    {
        return view('livewire.stories.story-reader');
    }
}
