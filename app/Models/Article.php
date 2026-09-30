<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'gambar',
        'category_id',
        'user_id',
        'status',
        'published_at',
        'hashtags',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'hashtags' => 'array',
    ];

    public function socialPostLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SocialPostLog::class);
    }

    public function getFormattedHashtagsAttribute(): array
    {
        $raw = $this->hashtags;
        if (empty($raw)) {
            return [];
        }

        $items = is_array($raw) ? $raw : json_decode($raw, true);
        if (!is_array($items)) {
            // Jika disimpan sebagai string comma-separated
            $items = array_map('trim', explode(',', (string)$raw));
        }

        $formatted = [];
        foreach ($items as $item) {
            $item = trim((string)$item);
            if (!empty($item)) {
                $formatted[] = str_starts_with($item, '#') ? $item : '#' . $item;
            }
        }

        return array_values(array_unique($formatted));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->orderBy('published_at', 'desc');
    }

    public function getGambarUrlAttribute(): string
    {
        if (!$this->gambar) {
            return 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=800&q=80';
        }

        if (str_starts_with($this->gambar, 'http')) {
            return $this->gambar;
        }

        return Storage::url($this->gambar);
    }
}
