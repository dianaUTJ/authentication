<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;


class StatsPayments extends BaseWidget
{
    use InteractsWithPageTable, HasWidgetShield;

    protected function getStats(): array
    {

        return [
            Stat::make('Pagos totales', Number::format(Payment::sum('amount') / 100, 2)),
        ];

    }
}
