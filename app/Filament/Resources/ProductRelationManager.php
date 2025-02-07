<?php

namespace App\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class ProductRelationManager extends RelationManager
{
    protected static string $relationship = 'products'; // ✅ Ensure this matches the Order model

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('brand')->sortable(),
                TextColumn::make('price')
                    ->label('Unit Price')
                    ->money('USD') // Adjust currency if needed
                    ->sortable(),
                TextColumn::make('pivot.quantity') // ✅ Get quantity from pivot table
                    ->sortable(),
                TextColumn::make('total_price')
                    ->label('Total Price')
                    ->getStateUsing(fn ($record) => $record->price * ($record->pivot->quantity ?? 1)) // ✅ Multiply price by quantity
                    ->money('USD')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->options(Product::all()->pluck('name', 'id')) // ✅ Ensure correct retrieval
                    ->searchable()
                    ->required(),
                TextInput::make('quantity')
                    ->numeric()
                    ->default(1)
                    ->required(),
            ]);
    }
}