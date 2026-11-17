<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'last_message_at',
        'user_unread_count',
        'admin_unread_count',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'user_unread_count' => 'integer',
        'admin_unread_count' => 'integer',
    ];

    /**
     * Relasi ke User (Penulis / Pengguna)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke seluruh pesan dalam percakapan ini
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Relasi ke pesan terbaru
     */
    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Tandai semua pesan sebagai telah dibaca oleh user
     */
    public function markAsReadForUser(): void
    {
        if ($this->user_unread_count > 0) {
            $this->messages()
                ->where('sender_id', '!=', $this->user_id)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            $this->update(['user_unread_count' => 0]);
        }
    }

    /**
     * Tandai semua pesan sebagai telah dibaca oleh admin
     */
    public function markAsReadForAdmin(): void
    {
        if ($this->admin_unread_count > 0) {
            $this->messages()
                ->where('sender_id', $this->user_id)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            $this->update(['admin_unread_count' => 0]);
        }
    }
}

