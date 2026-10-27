<?php

namespace App\Http\Middleware;

use App\Models\Setting;
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

        $latestVersion = (int) Setting::get('current_contract_version', 1);

        if ($user && !$user->contract_signed_at or ($user->contract_version != $latestVersion)) {
            session('error', 'Anda harus menandatangani Perjanjian Kerja Sama Penulis terlebih dahulu.');
            return redirect()->route('author.contract.sign');
        }

        return $next($request);
    }
}
