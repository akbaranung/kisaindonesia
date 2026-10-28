<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;

class ContractPdfController extends Controller
{
    public function download($storyId)
    {
        $story = Story::with('contract')
            ->where('user_id', Auth::id())
            ->findOrFail($storyId);

        $contract = $story->contract;

        if (!$contract) {
            abort(403, 'Akses ditolak. Perjanjian kerja sama untuk cerita ini belum ditandatangani.');
        }

        return view('pdf.author-contract', [
            'story' => $story,
            'contract' => $contract,
        ]);
    }
}
