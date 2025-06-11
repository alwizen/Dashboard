<?php

namespace App\Filament\Widgets;

use App\Models\Tanker;
use Filament\Widgets\Widget;

class TankerInspectionWidget extends Widget
{
    protected static string $view = 'filament.widgets.tanker-inspection-widget';

    protected static ?int $sort = 3;

    protected static ?string $pollingInterval = '30s';

    public function getViewData(): array
    {
        $totalTanker = Tanker::count();
        $inspectedCount = Tanker::has('inspections')->count();

        $percentage = $totalTanker > 0
            ? round(($inspectedCount / $totalTanker) * 100, 1)
            : 0;

        // Menentukan warna berdasarkan persentase
        $progressColor = $this->getProgressColor($percentage);
        $progressColorClass = $this->getProgressColorClass($percentage);

        return [
            'totalTanker' => $totalTanker,
            'inspectedCount' => $inspectedCount,
            'percentage' => $percentage,
            'progressColor' => $progressColor,
            'progressColorClass' => $progressColorClass,
        ];
    }

    private function getProgressColor(float $percentage): string
    {
        if ($percentage >= 80) {
            return '#10b981'; // Green - Excellent
        } elseif ($percentage >= 60) {
            return '#eab308'; // Yellow - Good  
        } elseif ($percentage >= 40) {
            return '#f97316'; // Orange - Fair
        } else {
            return '#ef4444'; // Red - Poor
        }
    }

    private function getProgressColorClass(float $percentage): string
    {
        if ($percentage >= 80) {
            return 'bg-green-500'; // Green - Excellent
        } elseif ($percentage >= 60) {
            return 'bg-yellow-500'; // Yellow - Good
        } elseif ($percentage >= 40) {
            return 'bg-orange-500'; // Orange - Fair
        } else {
            return 'bg-red-500'; // Red - Poor
        }
    }
}