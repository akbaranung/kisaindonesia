<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'title',
        'slug',
        'content',
        'type',
        'order_number',
        'status',
        'is_premium',
        'word_count',
        'bean_price',
        'file_path'
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function saveContent(array $data): bool
    {
        if (!$this->file_path) {
            return false;
        }

        return Storage::disk('local')->put($this->file_path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function calculateKisaBean(): int
    {
        $words = $this->word_count;
        $type = strtolower($this->story->type ?? 'regular');

        if ($type === 'puisi') {
            if ($words >= 700 && $words <= 1500) {
                return (int) ceil(($words / 100));
            }
        } else {
            if ($words >= 1000 && $words <= 1500) {
                return (int) ceil(($words / 100));
            }
        }

        return 0;
    }

    public function parseJsonData(): array
    {
        if (is_array($this->file_path)) {
            return $this->file_path;
        }

        $path = $this->file_path;

        if (empty($path)) {
            return [];
        }

        if (is_string($path) && Storage::exists($path)) {
            $jsonString = Storage::get($path);
            return json_decode($jsonString, true) ?? [];
        }

        if (is_string($path)) {
            return json_decode($path, true) ?? [];
        }

        return [];
    }

    public function calculateWordCount(): int
    {
        $data = $this->parseJsonData();
        $type = strtolower($data['type'] ?? $this->type ?? 'regular');

        // 1. JIKA TIPE BAB ADALAH CHAT
        if ($type === 'chat') {
            $bubbles = $data['bubbles'] ?? [];
            if (!is_array($bubbles) || empty($bubbles)) {
                return 0;
            }

            $totalWords = 0;

            foreach ($bubbles as $bubble) {
                $textParts = [];

                // Ambil teks dari pesan utama (misal: text atau center_text)
                if (!empty($bubble['message'])) {
                    $textParts[] = $bubble['message'];
                }

                // Ambil teks dari caption (misal: gambar dengan caption)
                if (!empty($bubble['caption'])) {
                    $textParts[] = $bubble['caption'];
                }

                // Jika ada teks yang perlu dihitung
                if (!empty($textParts)) {
                    $combinedText = implode(' ', $textParts);
                    $cleanText = trim(preg_replace('/\s+/', ' ', strip_tags($combinedText)));

                    if (!empty($cleanText)) {
                        // Gunakan str_word_count untuk menghitung kata secara presisi
                        $totalWords += str_word_count($cleanText);
                    }
                }
            }

            return $totalWords;
        }

        // 2. JIKA TIPE BAB ADALAH REGULAR (NOVEL / PUISI)
        $htmlContent = $data['content'] ?? (is_string($this->content ?? null) ? $this->content : '');

        $pureText = strip_tags($htmlContent);
        $pureText = html_entity_decode($pureText);
        $pureText = trim(preg_replace('/\s+/', ' ', $pureText)); // Perbaikan: mengganti preg_match ke preg_replace

        if (empty($pureText)) {
            return 0;
        }

        return str_word_count($pureText);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }
}
