<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use HasFactory;

    public const LAYOUT_CLASSIC = 'classic';
    public const LAYOUT_GRADIENT_SOFT = 'gradient_soft';
    public const LAYOUT_SPLIT_DIAGONAL = 'split_diagonal';

    protected $fillable = [
        'judul',
        'gambar',
        'deskripsi',
        'urutan',
        'status',
        'layout_style',
        'focal_point',
    ];

    protected $casts = [
        'status' => 'boolean',
        'urutan' => 'integer',
    ];

    public function getLayoutStyleLabelAttribute(): string
    {
        return match ($this->layout_style) {
            self::LAYOUT_GRADIENT_SOFT => 'Gradien Lembut',
            self::LAYOUT_SPLIT_DIAGONAL => 'Split Miring',
            default => 'Klasik',
        };
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', true)->orderBy('urutan', 'asc');
    }

    public function getGambarUrlAttribute(): string
    {
        if (str_starts_with($this->gambar, 'http')) {
            return $this->gambar;
        }

        return Storage::url($this->gambar);
    }
}
