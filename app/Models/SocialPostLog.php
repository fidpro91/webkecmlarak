<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialPostLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'social_media_account_id',
        'platform',
        'status',
        'message',
        'payload_preview',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(SocialMediaAccount::class, 'social_media_account_id');
    }
}
