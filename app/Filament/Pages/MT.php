<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class MT extends Page
{
    protected static ?string $navigationIcon = '';

    protected static ?string $title = 'Mobil Tangki';

    protected static ?string $navigationGroup = 'Fleet Management';

    protected static string $view = 'filament.pages.m-t';
}
