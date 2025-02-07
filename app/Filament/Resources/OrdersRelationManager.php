<?php

namespace App\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\Concerns\Translatable;
use Filament\Tables;
use Filament\Forms;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Builder;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders'; // Must match Customer model relation

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->disabled(),
                Forms\Components\TextInput::make('total')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'shipped' => 'Shipped',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'pending' => 'warning',
                        'approved' => 'success',
                        'shipped' => 'info',
                        'cancelled' => 'danger',
                    ])
                    ->sortable(),
            ]);
    }
}
