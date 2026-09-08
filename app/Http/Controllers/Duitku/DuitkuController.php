<?php

namespace App\Http\Controllers\Duitku;

use App\Http\Controllers\Controller;
use App\Models\UserTransaction;
use App\Models\User;
use App\Services\Duitku\DuitkuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DuitkuController extends Controller
{
    protected DuitkuService $duitkuService;

    public function __construct(DuitkuService $duitkuService)
    {
        $this->duitkuService = $duitkuService;
    }

    /**
     * Buat Transaksi Topup
     */
    public function createTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();
        $referenceCode = 'TOPUP-' . time() . '-' . $user->id;
        $amount = (int) $request->amount;

        $result = $this->duitkuService->createInvoice(
            $referenceCode,
            $amount,
            $request->payment_method,
            $user->email,
            $user->name
        );

        if (isset($result['statusCode']) && $result['statusCode'] == '00') {
            UserTransaction::create([
                'user_id' => $user->id,
                'reference_code' => $referenceCode,
                'type' => 'topup',
                'amount' => $amount,
                'gross_amount' => $result['amount'] ?? $amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'description' => 'Topup saldo via ' . strtoupper($request->payment_method),
                'payment_payload' => json_encode($result),
            ]);

            return response()->json([
                'status' => 'success',
                'payment_url' => $result['paymentUrl'],
                'reference_code' => $referenceCode,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $result['statusMessage'] ?? 'Gagal membuat transaksi.',
        ], 400);
    }

    public function callback(Request $request)
    {
        $amount = $request->input('amount');
        $referenceCode = $request->input('merchantOrderId');
        $signature = $request->input('signature');
        $resultCode = $request->input('resultCode');

        if (!$this->duitkuService->validateCallbackSignature($amount, $referenceCode, $signature)) {
            return response()->json(['message' => 'Invalid Signature'], 400);
        }

        $transaction = UserTransaction::where('reference_code', $referenceCode)->first();
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($transaction->status === 'success') {
            return response()->json(['message' => 'Already processed'], 200);
        }

        DB::transaction(function () use ($transaction, $resultCode, $request) {
            $payload = json_decode($transaction->payment_payload, true) ?? [];
            $payload['callback_response'] = $request->all();

            if ($resultCode == '00') {
                $transaction->update([
                    'status' => 'success',
                    'payment_payload' => json_encode($payload),
                ]);

                // Tambahkan koin ke balance user
                DB::table('users')
                    ->where('id', $transaction->user_id)
                    ->increment('kisa_bean_balance', $transaction->amount);
            } else {
                $transaction->update([
                    'status' => 'failed',
                    'payment_payload' => json_encode($payload),
                ]);
            }
        });

        return response()->json(['message' => 'Callback processed successfully'], 200);
    }

    public function returnPage(Request $request)
    {
        $merchantOrderId = $request->query('merchantOrderId');

        if (!$merchantOrderId) {
            return redirect()->route('profile')->with('error', 'Transaksi tidak ditemukan.');
        }

        $transaction = UserTransaction::where('reference_code', $merchantOrderId)->first();

        if (!$transaction) {
            return redirect()->route('profile')->with('error', 'Data transaksi tidak valid.');
        }

        // Fallback check jika callback webhook terlambat masuk
        if ($transaction->status === 'pending') {
            $duitkuStatus = $this->duitkuService->checkTransactionStatus($merchantOrderId);

            if (isset($duitkuStatus['statusCode']) && $duitkuStatus['statusCode'] === '00') {
                DB::transaction(function () use ($transaction, $duitkuStatus) {
                    $lockedTransaction = UserTransaction::where('id', $transaction->id)
                        ->lockForUpdate()
                        ->first();

                    if ($lockedTransaction->status === 'pending') {
                        $payload = json_decode($lockedTransaction->payment_payload, true) ?? [];
                        $payload['fallback_check_response'] = $duitkuStatus;

                        $lockedTransaction->update([
                            'status' => 'success',
                            'payment_payload' => json_encode($payload),
                        ]);

                        DB::table('users')
                            ->where('id', $lockedTransaction->user_id)
                            ->increment('kisa_bean_balance', $lockedTransaction->amount);
                    }
                });

                $transaction->refresh();
            } elseif (isset($duitkuStatus['statusCode']) && in_array($duitkuStatus['statusCode'], ['02', '03'])) {
                $transaction->update(['status' => 'failed']);
            }
        }

        return view('topup.return', ['transaction' => $transaction]);
    }
}
