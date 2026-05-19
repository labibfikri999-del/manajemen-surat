<?php

namespace App\Services;

use App\Models\Dokumen;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;

class SuratStatsService
{
    private const PUSAT_ROLES = ['direktur', 'staff', 'sekjen'];

    public function suratMasukCount(User $user): int
    {
        return $this->dokumenMasukQuery($user)->count()
            + $this->manualSuratMasukQuery($user)->count();
    }

    public function suratKeluarCount(User $user): int
    {
        return $this->dokumenKeluarQuery($user)->count()
            + $this->manualSuratKeluarQuery($user)->count();
    }

    public function arsipDigitalCount(User $user): int
    {
        return $this->arsipQuery($user)->count();
    }

    public function laporanStats(User $user): array
    {
        $suratMasuk = $this->suratMasukCount($user);
        $suratKeluar = $this->suratKeluarCount($user);
        $arsip = $this->arsipDigitalCount($user);

        $arsipDistribution = $this->arsipQuery($user)
            ->selectRaw('kategori_arsip as kategori, COUNT(*) as count')
            ->groupBy('kategori_arsip')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->kategori ?? 'Umum',
                    'count' => $item->count,
                ];
            });

        if ($arsipDistribution->isEmpty() && $arsip > 0) {
            $arsipDistribution = collect([['label' => 'Umum', 'count' => $arsip]]);
        }

        return [
            'surat_masuk' => $suratMasuk,
            'surat_keluar' => $suratKeluar,
            'arsip_digital' => $arsip,
            'monthly_masuk' => $this->monthlyCounts($user, 'masuk'),
            'monthly_keluar' => $this->monthlyCounts($user, 'keluar'),
            'arsip_distribution' => $arsipDistribution,
        ];
    }

    private function dokumenMasukQuery(User $user)
    {
        $query = Dokumen::query();

        if ($user->isInstansi()) {
            return $query
                ->where('instansi_id', $user->instansi_id)
                ->whereHas('user', function ($q) {
                    $q->whereIn('role', self::PUSAT_ROLES);
                });
        }

        return $query->where(function ($q) {
            $q->where('jenis_dokumen', 'surat_masuk')
                ->orWhereNull('jenis_dokumen')
                ->orWhereHas('user', function ($userQuery) {
                    $userQuery->where('role', User::ROLE_INSTANSI);
                });
        });
    }

    private function dokumenKeluarQuery(User $user)
    {
        $query = Dokumen::query();

        if ($user->isInstansi()) {
            return $query
                ->where('instansi_id', $user->instansi_id)
                ->whereHas('user', function ($q) {
                    $q->where('role', User::ROLE_INSTANSI);
                });
        }

        return $query->where(function ($q) {
            $q->where('jenis_dokumen', 'surat_keluar')
                ->orWhereHas('user', function ($userQuery) {
                    $userQuery->where('role', User::ROLE_INSTANSI);
                });
        });
    }

    private function manualSuratMasukQuery(User $user)
    {
        $query = SuratMasuk::query();

        if ($user->isInstansi()) {
            $query->where('instansi_id', $user->instansi_id);
        }

        return $query;
    }

    private function manualSuratKeluarQuery(User $user)
    {
        $query = SuratKeluar::query()->whereNull('dokumen_id');

        if ($user->isInstansi()) {
            $query->where('instansi_id', $user->instansi_id);
        }

        return $query;
    }

    private function arsipQuery(User $user)
    {
        $query = Dokumen::where('is_archived', true);

        if ($user->isInstansi()) {
            $query->where('instansi_id', $user->instansi_id);
        }

        return $query;
    }

    private function monthlyCounts(User $user, string $direction): array
    {
        $months = array_fill(1, 12, 0);
        $year = (int) date('Y');

        $dokumenQuery = $direction === 'masuk'
            ? $this->dokumenMasukQuery($user)
            : $this->dokumenKeluarQuery($user);

        foreach ($this->groupByMonth($dokumenQuery, 'created_at', $year) as $month => $count) {
            $months[(int) $month] += (int) $count;
        }

        $manualQuery = $direction === 'masuk'
            ? $this->manualSuratMasukQuery($user)
            : $this->manualSuratKeluarQuery($user);

        $dateColumn = $direction === 'masuk' ? 'tanggal_diterima' : 'tanggal_keluar';
        foreach ($this->groupByMonth($manualQuery, $dateColumn, $year) as $month => $count) {
            $months[(int) $month] += (int) $count;
        }

        return array_values($months);
    }

    private function groupByMonth($query, string $dateColumn, int $year): array
    {
        return $query
            ->whereYear($dateColumn, $year)
            ->selectRaw("MONTH($dateColumn) as month, COUNT(*) as count")
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
    }
}
