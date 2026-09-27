<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'gambar',
        'deskripsi',
        'urutan',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'urutan' => 'integer',
    ];

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
