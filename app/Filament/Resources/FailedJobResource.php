<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FailedJobResource\Pages;
use App\Models\FailedJob; // Make sure to create a FailedJob model
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;

class FailedJobResource extends Resource
{
    protected static ?string $model = FailedJob::class; // Failed Job Model

    protected static ?string $navigationIcon = 'heroicon-o-document-text'; 

    protected static ?string $navigationLabel = 'Failed Jobs';
    protected static ?string $pluralModelLabel = 'Failed Jobs';
    protected static ?string $slug = 'failed-jobs';
    protected static ?string $navigationGroup = 'Job';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('queue')->label('Queue Name')->sortable(),
                TextColumn::make('exception')->limit(100)->label('Error Message'),
                TextColumn::make('failed_at')->label('Failed At')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('Retry')
                    // ->action(fn ($record) => \Log::info('Retrying job:', ['id' => $record->uuid]))
                    ->action(fn ($record) => \Artisan::call('queue:retry', ['id' => $record->uuid]))
                    ->icon('heroicon-o-document-text'),

                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFailedJobs::route('/'),
        ];
    }
}
