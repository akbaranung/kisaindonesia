<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ContractPdfController extends Controller
{
    public function download()
    {
        $user = Auth::user();

        if (!$user || !$user->contract_signed_at) {
            abort(403, 'Akses ditolak. Anda belum menandatangani kontrak.');
        }

        // Generate tampilan surat kontrak PDF
        return view('pdf.author-contract', [
            'user' => $user,
            'signedAt' => $user->contract_signed_at,
            'ip' => $user->contract_signature_ip,
        ]);
    }
}
