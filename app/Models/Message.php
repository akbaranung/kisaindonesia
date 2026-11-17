<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'attachment',
        'attachment_name',
        'attachment_type',
        'attachment_size',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'attachment_size' => 'integer',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Relasi ke Conversation
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Relasi ke User Pengirim (Admin atau Penulis)
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Apakah pesan ini dikirim oleh Admin?
     */
    public function isFromAdmin(): bool
    {
        return $this->sender && ($this->sender->isAdmin() || $this->sender->role === 'superadmin');
    }

    /**
     * Cek apakah pesan memiliki lampiran file
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment);
    }

    /**
     * Cek apakah lampiran berupa gambar
     */
    public function isImage(): bool
    {
        return $this->attachment_type && str_starts_with($this->attachment_type, 'image/');
    }

    /**
     * Cek apakah lampiran berupa PDF
     */
    public function isPdf(): bool
    {
        return $this->attachment_type === 'application/pdf';
    }

    /**
     * Format ukuran lampiran (KB / MB)
     */
    public function formattedAttachmentSize(): string
    {
        if (!$this->attachment_size) {
            return '';
        }

        if ($this->attachment_size < 1024) {
            return $this->attachment_size . ' B';
        } elseif ($this->attachment_size < 1048576) {
            return round($this->attachment_size / 1024, 1) . ' KB';
        } else {
            return round($this->attachment_size / 1048576, 1) . ' MB';
        }
    }
}

