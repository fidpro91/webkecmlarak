<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_menu',
        'slug',
        'tipe',
        'url',
        'parent_id',
        'urutan',
        'status',
        'icon',
    ];

    protected $casts = [
        'status' => 'boolean',
        'urutan' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->where('status', true)->orderBy('urutan', 'asc');
    }

    public function allChildren(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('urutan', 'asc');
    }

    public function page(): HasOne
    {
        return $this->hasOne(MenuPage::class, 'menu_id');
    }

    public function scopeIndukAktif(Builder $query): Builder
    {
        return $query->whereNull('parent_id')
                     ->where('status', true)
                     ->orderBy('urutan', 'asc')
                     ->with('children');
    }

    public function getTargetUrlAttribute(): string
    {
        if ($this->tipe === 'external_link') {
            return $this->url ?? '#';
        }

        if ($this->tipe === 'module') {
            return url($this->url ?? '/');
        }

        return route('page.show', $this->slug ?? 'halaman');
    }
}
