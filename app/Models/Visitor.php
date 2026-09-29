<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'visited_date',
        'hits',
    ];

    protected $casts = [
        'visited_date' => 'date',
        'hits' => 'integer',
    ];

    /**
     * Catat kunjungan pengunjung dengan aman dan efisien.
     */
    public static function track(?Request $request = null): void
    {
        $request = $request ?: request();
        if (!$request) {
            return;
        }

        try {
            $ip = $request->ip() ?: '127.0.0.1';
            $today = now()->toDateString();
            $sessionKey = 'visited_logged_' . $today;

            if (!session()->has($sessionKey)) {
                session()->put($sessionKey, true);

                $visitor = self::firstOrCreate(
                    [
                        'ip_address' => $ip,
                        'visited_date' => $today,
                    ],
                    [
                        'user_agent' => substr((string) $request->userAgent(), 0, 500),
                        'hits' => 0,
                    ]
                );

                $visitor->increment('hits');
            } else {
                // Sesi sudah tercatat hari ini, tambahkan hits secara berkala jika perlu
                self::where('ip_address', $ip)
                    ->where('visited_date', $today)
                    ->increment('hits');
            }
        } catch (\Throwable $e) {
            // Hindari kegagalan request utama jika logging visitor error
            Log::warning('Visitor track error: ' . $e->getMessage());
        }
    }

    /**
     * Dapatkan total estimasi dan akumulasi pengunjung website.
     */
    public static function totalVisitorsCount(): int
    {
        $base = (int) (Setting::get('base_visitor_count') ?? 14850);
        $recorded = self::count();

        return $base + $recorded;
    }

    /**
     * Dapatkan pengunjung unik hari ini.
     */
    public static function todayVisitorsCount(): int
    {
        return self::where('visited_date', now()->toDateString())->count();
    }
}
