<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialMediaAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform',
        'account_name',
        'account_id',
        'app_id',
        'app_secret',
        'access_token',
        'token_secret',
        'webhook_url',
        'is_active',
        'auto_post',
        'last_posted_at',
        'last_status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_post' => 'boolean',
        'last_posted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SocialPostLog::class, 'social_media_account_id');
    }

    public function getPlatformLabelAttribute(): string
    {
        return match (strtolower($this->platform)) {
            'facebook' => 'Facebook Page',
            'instagram' => 'Instagram Business',
            'twitter', 'x' => 'X (Twitter)',
            'telegram' => 'Telegram Channel / Bot',
            'whatsapp' => 'WhatsApp Gateway',
            default => ucfirst($this->platform),
        };
    }

    public function getPlatformIconAttribute(): string
    {
        return match (strtolower($this->platform)) {
            'facebook' => 'fa-brands fa-facebook text-blue-600',
            'instagram' => 'fa-brands fa-instagram text-pink-600',
            'twitter', 'x' => 'fa-brands fa-x-twitter text-slate-900',
            'telegram' => 'fa-brands fa-telegram text-sky-500',
            'whatsapp' => 'fa-brands fa-whatsapp text-emerald-600',
            default => 'fa-solid fa-share-nodes text-slate-600',
        };
    }

    public function getPlatformColorClassAttribute(): string
    {
        return match (strtolower($this->platform)) {
            'facebook' => 'bg-blue-50 text-blue-700 border-blue-200',
            'instagram' => 'bg-pink-50 text-pink-700 border-pink-200',
            'twitter', 'x' => 'bg-slate-100 text-slate-800 border-slate-300',
            'telegram' => 'bg-sky-50 text-sky-700 border-sky-200',
            'whatsapp' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }

    public function getMaskedTokenAttribute(): string
    {
        if (empty($this->access_token)) {
            return 'Belum diatur';
        }

        $length = strlen($this->access_token);
        if ($length <= 8) {
            return '••••••••';
        }

        return substr($this->access_token, 0, 4) . '••••' . substr($this->access_token, -4);
    }
}
