<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'tipe',
        'file',
        'deskripsi',
    ];

    public function getFileUrlAttribute(): string
    {
        if (str_starts_with($this->file, 'http')) {
            return $this->file;
        }

        return Storage::url($this->file);
    }
}
