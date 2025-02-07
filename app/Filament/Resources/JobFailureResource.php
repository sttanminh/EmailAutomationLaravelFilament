<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobFailureResource\Pages;
use App\Filament\Resources\JobFailureResource\RelationManagers;
use App\Models\JobFailure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;


class JobFailureResource extends Resource
{
    protected static ?string $model = JobFailure::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Job';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('uuid')->label('Job ID (UUID)')->sortable(),
                TextColumn::make('job_name')->label('Job Name')->sortable(),
                TextColumn::make('pdf_link_id')->label('Failed PDF ID')->sortable(),
                TextColumn::make('exception')->limit(100)->label('Error Message'),
                TextColumn::make('failed_at')->label('Failed At')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('Retry')
                    ->action(fn ($record) => \Artisan::call('queue:retry', ['id' => $record->uuid]))
                    ->icon('heroicon-o-document-text'),
    
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobFailures::route('/'),
            'create' => Pages\CreateJobFailure::route('/create'),
            'edit' => Pages\EditJobFailure::route('/{record}/edit'),
        ];
    }
}
