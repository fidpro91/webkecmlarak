<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('social_media_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('platform'); // facebook, instagram, twitter, telegram, whatsapp
            $table->string('account_name'); // Nama tampilan akun / channel
            $table->string('account_id')->nullable(); // Page ID, Chat ID, username
            $table->string('app_id')->nullable(); // App ID / Client ID / API Key
            $table->text('app_secret')->nullable(); // App Secret / API Secret
            $table->text('access_token')->nullable(); // Access Token / Bot Token
            $table->text('token_secret')->nullable(); // Optional Secret / Bearer / Chat ID
            $table->string('webhook_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_post')->default(true);
            $table->timestamp('last_posted_at')->nullable();
            $table->string('last_status')->nullable();
            $table->timestamps();
        });

        Schema::create('social_post_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('social_media_account_id')->nullable()->constrained('social_media_accounts')->nullOnDelete();
            $table->string('platform');
            $table->enum('status', ['success', 'failed', 'simulated'])->default('success');
            $table->text('message')->nullable();
            $table->text('payload_preview')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_post_logs');
        Schema::dropIfExists('social_media_accounts');
    }
};
