<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Village extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'kepala_desa',
        'jumlah_penduduk',
        'luas_wilayah',
        'deskripsi',
        'foto',
    ];

    protected $casts = [
        'jumlah_penduduk' => 'integer',
    ];

    public function getFotoUrlAttribute(): string
    {
        if (!$this->foto) {
            return 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80';
        }

        if (str_starts_with($this->foto, 'http')) {
            return $this->foto;
        }

        return Storage::url($this->foto);
    }
}
