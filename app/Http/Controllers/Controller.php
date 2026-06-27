<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Cache\LockTimeoutException;

abstract class Controller
{
    /**
     * Cache::remember yang aman dari stampede.
     *
     * Cara kerja:
     * 1. Cek cache dulu — kalau ada, langsung return (jalur paling umum)
     * 2. Kalau tidak ada, ambil lock Redis
     * 3. Yang dapat lock → query DB, simpan ke cache, lepas lock
     * 4. Yang tidak dapat lock → tunggu sebentar, lalu cek cache lagi
     *    (karena yang dapat lock sudah isi cachenya)
     *
     * @param string   $key      Cache key
     * @param int      $ttlMenit TTL dalam menit
     * @param callable $query    Closure yang query DB
     * @param int      $lockTtl  Berapa detik lock bertahan (default: 10 detik)
     */
    // protected function rememberWithLock(
    //     string $key,
    //     int $ttlMenit,
    //     callable $query,
    //     int $lockTtl = 10
    // ): mixed {
    //     // ── Cek dulu sebelum berebut lock ──────────────────
    //     // 99% request masuk sini dan langsung return — tidak perlu lock
    //     $cached = Cache::get($key);
    //     if ($cached !== null) {
    //         return $cached;
    //     }

    //     // ── Cache kosong — mulai rebutan lock ───────────────
    //     // Nama lock = key + suffix agar tidak bentrok dengan cache key
    //     $lock = Cache::lock("lock_{$key}", $lockTtl);

    //     try {
    //         // block(detik): tunggu maksimal N detik sampai dapat lock
    //         // Kalau dalam 5 detik tidak dapat lock → lempar exception
    //         $lock->block(5);

    //         // ── Dapat lock — cek lagi (double-check) ────────
    //         // Mungkin request sebelumnya sudah isi cache
    //         // saat kita menunggu lock tadi
    //         $cached = Cache::get($key);
    //         if ($cached !== null) {
    //             return $cached; // sudah diisi orang lain, tidak perlu query DB
    //         }

    //         // ── Benar-benar kosong — kita yang query DB ─────
    //         $result = $query();
    //         Cache::put($key, $result, now()->addMinutes($ttlMenit));

    //         return $result;

    //     } catch (LockTimeoutException $e) {
    //         // Tidak dapat lock dalam 5 detik
    //         // Kemungkinan: ada request lain yang sedang isi cache
    //         // Fallback: query DB langsung tanpa cache (daripada error)
    //         Log::warning("Cache lock timeout untuk key: {$key}");
    //         return $query();

    //     } finally {
    //         // Selalu lepas lock — bahkan kalau ada exception
    //         $lock->forceRelease();
    //     }
    // }
    protected function rememberWithLock(
        string $key,
        int $ttlMenit,
        callable $query,
        int $lockTtl = 10
    ): mixed {
        // 1. Jalur Cepat (Fast Path) - 99% request akan lolos di sini
        $cached = Cache::get($key);
        if ($cached !== null) {
            return $cached;
        }

        try {
            // 2. block() dengan closure otomatis mengatur release lock dengan aman
            return Cache::lock("lock_{$key}", $lockTtl)->block(5, function () use ($key, $ttlMenit, $query) {
                // Gunakan Cache::remember sebagai double-check yang aman saat lock didapat
                return Cache::remember($key, now()->addMinutes($ttlMenit), $query);
            });
        } catch (LockTimeoutException $e) {
            // 3. Jika antre 5 detik gagal, eksekusi query langsung (fallback)
            Log::warning("Cache lock timeout untuk key: {$key}");
            return $query();
        }
    }
}
