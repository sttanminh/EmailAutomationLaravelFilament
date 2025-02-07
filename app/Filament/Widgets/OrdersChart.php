<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Illuminate\Support\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders Over Time';

    protected function getData(): array
    {
        // Fetch orders grouped by date
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $orders->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('M d'))->toArray(),
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orders->pluck('total')->toArray(),
                    'borderColor' => '#ff6384',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'fill' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Line chart
    }
}
