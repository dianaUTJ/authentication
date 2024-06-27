<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use App\Models\Payment;
use Flowframe\Trend\TrendValue;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class PaymentsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    use HasWidgetShield;


    protected function getData(): array
    {
            $amount = Payment::sum('amount') / 100;

            //for each user
            // $data = Trend::query(
            //     Payment::query()
            //     ->PaymentList()
            // )
            //     ->between(
            //         start: now()->subDays(30),
            //         end: now()
            //     )
            //     ->perDay()
            //     ->aggregate($amount, 'sum');


            //for all users
            $data = Trend::model(Payment::class)
                ->between(
                    start: now()->subDays(30),
                    end: now()
                )
                ->perDay()
                ->aggregate($amount, 'sum');

        return [
            'datasets' => [
                [
                    'label' => 'Payments of the last 30 days',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
