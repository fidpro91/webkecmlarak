<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, ?string $value): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting_{$key}");
        Cache::forever("setting_{$key}", $value);

        return $setting;
    }

    public static function allKeyed(): array
    {
        return Cache::rememberForever('settings_all', function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    public static function clearCache(): void
    {
        $keys = self::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
        }
        Cache::forget('settings_all');
    }

    public static function baganStrukturUrl(): string
    {
        $custom = self::get('bagan_struktur_organisasi');
        if (!empty($custom)) {
            return $custom;
        }
        return asset('images/bagan-struktur-organisasi.svg');
    }

    public static function logoUrl(): string
    {
        $custom = self::get('logo');
        if (!empty($custom)) {
            if (str_starts_with($custom, 'http://') || str_starts_with($custom, 'https://')) {
                return $custom;
            }
            if (str_starts_with($custom, '/storage/') || str_starts_with($custom, 'storage/')) {
                return asset($custom);
            }
            if (str_starts_with($custom, 'settings/')) {
                return \Illuminate\Support\Facades\Storage::url($custom);
            }
            return asset($custom);
        }
        return asset('images/logoponorogo.png');
    }
}
