<?php

namespace App\Http\Middleware;

use App\Models\Story;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwnsStory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $story = $request->route('story');
        if ($story) {
            if (!$story instanceof Story) {
                $story = Story::where('id', $story)
                    ->orWhere('slug', $story)
                    ->firstOrFail();
            }

            $userPenNameIds = auth()->user()->penNames()->pluck('id')->toArray();

            if (!in_array($story->pen_name_id, $userPenNameIds)) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit cerita ini.');
            }
        }

        return $next($request);
    }
}
