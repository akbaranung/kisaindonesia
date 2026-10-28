<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\StoryContract;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthorContract
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $storyId = $request->route('story') ?? $request->query('story_id');

        if ($storyId) {
            $contract = StoryContract::where('story_id', $storyId)->first();
            $latestVersion = (int) Setting::get('current_contract_version', 1);

            if (!$contract || !$contract->signed_at || (int) $contract->contract_version !== $latestVersion) {
                session()->flash('error', 'Anda harus menandatangani Perjanjian Kerja Sama untuk cerita ini terlebih dahulu.');

                return redirect()->route('author.contract.sign', ['story' => $storyId]);
            }
        } else {
            session()->flash('error', 'Silakan pilih cerita terlebih dahulu untuk mengajukan monetisasi.');
            return redirect()->route('my-stories');
        }

        return $next($request);
    }
}
