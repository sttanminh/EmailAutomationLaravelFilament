<?php
namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Models\Job; // Make sure to create a Job model
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;

class JobResource extends Resource
{
    protected static ?string $model = Job::class; // Job Model

    protected static ?string $navigationIcon = 'heroicon-o-document-text'; 

    protected static ?string $navigationLabel = 'Job Queue';
    protected static ?string $pluralModelLabel = 'Job Queue';
    protected static ?string $slug = 'jobs';
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
                TextColumn::make('payload')->limit(100),
                TextColumn::make('attempts')->sortable(),
                TextColumn::make('created_at')->label('Created')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('Retry')
                    ->action(fn ($record) => \Artisan::call('queue:retry', ['id' => $record->id]))
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
            'index' => Pages\ListJobs::route('/'),
        ];
    }
}
