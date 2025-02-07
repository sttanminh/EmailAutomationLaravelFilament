<?php

namespace App\Filament\Resources\JobFailureResource\Pages;

use App\Filament\Resources\JobFailureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobFailures extends ListRecords
{
    protected static string $resource = JobFailureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
