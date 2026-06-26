<?php
// app/Support/CacheKey.php

namespace App\Support;

class CacheKey
{
    // ── Stat counts (10 menit) ─────────────────────────
    const STAT_TOTAL_SISWA   = 'stat_total_siswa';
    const STAT_TOTAL_GURU    = 'stat_total_guru';
    const STAT_TOTAL_SOAL    = 'stat_total_soal';
    const STAT_TOTAL_PAKET   = 'stat_total_paket';

    // ── Dashboard data (10 menit) ──────────────────────
    const DASHBOARD_UJIAN_TERBARU        = 'dashboard_ujian_terbaru';
    const DASHBOARD_SISWA_BELUM_LENGKAP  = 'dashboard_siswa_belum_lengkap';

    // ── Chart (1 jam) ──────────────────────────────────
    const CHART_PARTISIPASI = 'chart_partisipasi';

    // ── Dropdown data (1 jam) ──────────────────────────
    const ALL_MATA_PELAJARAN = 'all_mata_pelajaran';
    const ALL_KELAS          = 'all_kelas';
    const ALL_ROLES          = 'all_roles';
    const ALL_GURU_DROPDOWN  = 'all_guru_dropdown';
    const KELAS_WITH_COUNT   = 'kelas_with_count';

    // ── Rekap per paket (1 jam, dynamic key) ──────────
    public static function rekapPaket(int $paketId): string
    {
        return "rekap_paket_{$paketId}";
    }

    // Durasi dalam menit
    const TTL_LONG   = 60;   // 1 jam
    const TTL_MEDIUM = 10;   // 10 menit
}
