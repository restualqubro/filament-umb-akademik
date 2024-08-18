<?php

namespace App\Filament\Widgets;

use App\Models\Surat;
use App\Models\User;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFIlters;

    protected function getStats(): array
    {
        $code = $this->filters['code'];
        $pending = Surat::where('status', '!=', 'BARU')
                            ->where('status', '!=', 'DITOLAK')
                            ->where('status', '!=', 'DISETUJUI')
                            ->where('akademik_id', $code)
                            ->count();
        $complete = Surat::where('status', 'Disetujui')
                            ->where('akademik_id', $code)
                            ->count();
        $mahasiswa = User::role('Mahasiswa')
                            ->where('email_verified_at', '!=', null)
                            ->count();        
        return [
            Stat::make('Pengajuan Pending', $pending),
            Stat::make('Pengajuan Diselesaikan', $complete),
            Stat::make('Mahasiswa Terdaftar', $mahasiswa),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa';
    }
}

