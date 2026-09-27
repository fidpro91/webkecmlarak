<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_layanan',
        'slug',
        'syarat',
        'prosedur',
        'file_sop',
    ];

    public function getFileSopUrlAttribute(): ?string
    {
        if (!$this->file_sop) {
            return null;
        }

        if (str_starts_with($this->file_sop, 'http')) {
            return $this->file_sop;
        }

        return Storage::url($this->file_sop);
    }
}
