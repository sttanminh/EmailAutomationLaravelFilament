<?php

namespace App\Filament\Resources\JobFailureResource\Pages;

use App\Filament\Resources\JobFailureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobFailure extends EditRecord
{
    protected static string $resource = JobFailureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
