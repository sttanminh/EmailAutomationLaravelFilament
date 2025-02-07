<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Filament\Widgets\StatsOverviewWidget;
use Illuminate\Contracts\View\View;

class Settings extends Page
{
    // Navigation Label in Admin Panel
    protected static ?string $navigationLabel = 'Settings';

    // Page Title
    protected static ?string $title = 'Application Settings';

    // Custom URL (default is '/admin/settings', changing to '/admin/site-settings')
    protected static ?string $slug = 'site-settings';

    // Link the Page to a Blade View
    protected static string $view = 'filament.pages.settings';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->action(fn () => \Artisan::call('cache:clear'))
                ->successNotificationTitle('Cache cleared successfully!'),
        ];
    }


}
