<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DailySampleWidget extends Widget
{
    protected static string $view = 'filament.widgets.daily-sample-widget';
    protected static ?int $sort = 1;

    public function getDailySampleProperty()
    {
        return \App\Models\DailySample::with('dailySampleItems.product')->latest()->first();
    }
}