<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'title',
        'slug',
        'content',
        'type',
        'order_number',
        'status',
        'is_premium',
        'word_count',
        'bean_price',
        'file_path'
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function saveContent(array $data): bool
    {
        if (!$this->file_path) {
            return false;
        }

        return Storage::disk('local')->put($this->file_path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function calculateKisaBean(): int
    {
        $words = $this->word_count;
        $type = strtolower($this->type ?? 'regular');

        if ($type === 'puisi') {
            if ($words >= 700 && $words <= 1500) {
                return (int) ceil(($words / 100) * 1.2);
            }
        } else {
            if ($words >= 1000 && $words <= 1500) {
                return (int) ceil(($words / 100) * 1.5);
            }
        }

        return 0;
    }

    public function parseJsonData(): array
    {
        if (is_array($this->file_path)) {
            return $this->file_path;
        }

        $path = $this->file_path;

        if (empty($path)) {
            return [];
        }

        if (is_string($path) && Storage::exists($path)) {
            $jsonString = Storage::get($path);
            return json_decode($jsonString, true) ?? [];
        }

        if (is_string($path)) {
            return json_decode($path, true) ?? [];
        }

        return [];
    }

    public function calculateWordCount(): int
    {
        $data = $this->parseJsonData();
        $type = strtolower($data['type'] ?? $this->type ?? 'regular');

        if ($type === 'chat') {
            return 0;
        }

        $htmlContent = $data['content'] ?? '';
        $pureText = strip_tags($htmlContent);

        $pureText = html_entity_decode($pureText);
        $pureText = trim(preg_match('/\s+/', ' ', $pureText));

        if (empty($pureText)) {
            return 0;
        }

        return count(explode(' ', $pureText));
    }

    public function calculateBubbleCount()
    {
        $data = $this->parseJsonData();
        $type = strtolower($data['type'] ?? $this->type ?? 'regular');

        if ($type !== 'chat') {
            return 0;
        }

        $bubbles = $data['bubbles'] ?? [];

        return is_array($bubbles) ? count($bubbles) : 0;
    }
}
