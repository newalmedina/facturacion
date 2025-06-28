<?php

namespace App\Filament\CustomWidgets;

use App\Models\Order;
use App\Models\OtherExpense;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Carbon;

class VentasVsGastosPorDiaChart extends LineChartWidget
{
    use InteractsWithForms;

    protected static ?string $heading = 'Ventas vs Gastos (por día)';
    protected static ?string $maxHeight = '300px';

    public ?string $fecha_ini = null;
    public ?string $fecha_fin = null;

    protected function getFormSchema(): array
    {
        $year = now()->year;

        return [
            Forms\Components\DatePicker::make('fecha_ini')
                ->label('Fecha desde')
                ->default(Carbon::createFromDate($year, 1, 1))
                ->required(),

            Forms\Components\DatePicker::make('fecha_fin')
                ->label('Fecha hasta')
                ->default(Carbon::createFromDate($year, 12, 31))
                ->required(),
        ];
    }

    protected function getFormModel(): array
    {
        return [
            'fecha_ini' => $this->fecha_ini,
            'fecha_fin' => $this->fecha_fin,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->form->fill([
            'fecha_ini' => $this->fecha_ini,
            'fecha_fin' => $this->fecha_fin,
        ]);

        $this->form->reactive();

        $this->form->on('updated', function () {
            $this->fecha_ini = $this->form->getState()['fecha_ini'];
            $this->fecha_fin = $this->form->getState()['fecha_fin'];

            $this->notify('success', 'Fechas actualizadas');
            $this->refresh();
        });
    }

    protected function getData(): array
    {
        $start = $this->fecha_ini ? Carbon::parse($this->fecha_ini) : now()->subMonth();
        $end = $this->fecha_fin ? Carbon::parse($this->fecha_fin) : now();

        $days = $start->copy();
        $labels = [];
        $ventasData = [];
        $gastosData = [];

        while ($days <= $end) {
            $labels[] = $days->format('d-m-Y');

            $ventasData[] = Order::withCalculatedTotals()
                ->sales()
                ->invoiced()
                ->whereDate('date', $days->toDateString())
                ->get()
                ->sum('total');

            $gastosData[] = OtherExpense::with('details')
                ->whereDate('date', $days->toDateString())
                ->get()
                ->sum(fn($expense) => $expense->total);

            $days->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas',
                    'data' => $ventasData,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'transparent',
                    'fill' => false,
                    'pointRadius' => 3,
                    'pointHoverRadius' => 8,
                ],
                [
                    'label' => 'Gastos',
                    'data' => $gastosData,
                    'borderColor' => '#EF4444',
                    'backgroundColor' => 'transparent',
                    'fill' => false,
                    'pointRadius' => 3,
                    'pointHoverRadius' => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
