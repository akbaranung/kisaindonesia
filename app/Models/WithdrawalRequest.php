<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_no',
        'kisa_amount',
        'conversion_rate',
        'gross_amount',
        'admin_fee',
        'net_amount',
        'bank_name',
        'account_number',
        'account_name',
        'status',
        'processed_at',
        'processed_by',
        'rejection_reason',
        'proof_file_path'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
