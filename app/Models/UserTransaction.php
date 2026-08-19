<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_code',
        'type',
        'amount',
        'gross_amount',
        'payment_method',
        'status',
        'description',
        'payment_payload'
    ];

    protected $casts = [
        'payment_payload' => 'array',
        'amount' => 'integer',
        'gross_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
