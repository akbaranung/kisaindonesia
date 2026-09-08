<?php

namespace App\Traits;

use App\Models\UserTransaction;
use Illuminate\Support\Facades\Auth;

trait HasDuitkuPayment
{
    /**
     * Membuka kembali Pop-up Duitku untuk transaksi yang masih pending
     */
    public function reopenPayment($transactionId)
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $transaction = UserTransaction::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->find($transactionId);

        if (!$transaction) {
            session()->flash('error', 'Transaksi tidak ditemukan atau sudah selesai.');
            return;
        }

        $payload = json_decode($transaction->payment_payload, true);
        $duitkuReference = $payload['reference'] ?? null;

        if (!$duitkuReference) {
            session()->flash('error', 'Referensi pembayaran Duitku tidak valid.');
            return;
        }

        // Emit Event ke JS untuk membuka Pop-up SDK Duitku
        $this->dispatch('open-duitku-popup', [
            'reference' => $duitkuReference,
            'referenceCode' => $transaction->reference_code
        ]);
    }
}
