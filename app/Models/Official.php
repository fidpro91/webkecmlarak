<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Official extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jabatan',
        'foto',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            \App\Services\BaganSvgService::sync();
        });

        static::deleted(function () {
            \App\Services\BaganSvgService::sync();
        });
    }

    public function getFotoUrlAttribute(): string
    {
        if (!$this->foto) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&background=047857&color=ffffff&size=256';
        }

        if (str_starts_with($this->foto, 'http')) {
            return $this->foto;
        }

        return Storage::url($this->foto);
    }
}
