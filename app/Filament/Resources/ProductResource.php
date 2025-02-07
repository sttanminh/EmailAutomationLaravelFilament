<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('brand')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('visibility')
                    ->options([
                        'visible' => 'Visible',
                        'hidden' => 'Hidden',
                    ])
                    ->default('visible')
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('sku')
                    ->default(fn() => strtoupper(uniqid('PROD-')))
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('brand')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('visibility')
                    ->colors([
                        'visible' => 'success',
                        'hidden' => 'danger',
                    ]),

                Tables\Columns\TextColumn::make('price')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->square(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('visibility')
                    ->options([
                        'visible' => 'Visible',
                        'hidden' => 'Hidden',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
