<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'download_category_id',
        'file',
        'tipe_file',
        'ukuran_file',
        'jumlah_unduhan',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'jumlah_unduhan' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DownloadCategory::class, 'download_category_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', true)->orderBy('created_at', 'desc');
    }

    public function isPdf(): bool
    {
        return strtolower($this->tipe_file) === 'pdf';
    }

    public function getFileUrlAttribute(): string
    {
        if (str_starts_with($this->file, 'http')) {
            return $this->file;
        }

        return Storage::url($this->file);
    }

    public static function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
