<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'user_id',
        'contract_version',
        'signer_name',
        'ktp_number',
        'pob',
        'dob',
        'address',
        'signed_at',
        'signature_ip'
    ];

    protected $casts = [
        'dob' => 'date',
        'signed_at' => 'datetime'
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
