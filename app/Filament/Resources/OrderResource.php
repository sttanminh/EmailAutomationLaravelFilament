<?php
namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->unique(ignoreRecord: true), // ✅ Ignore the current order when editing
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Forms\Components\Select::make('products')
                    ->label('Products')
                    ->multiple()
                    ->relationship('products', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
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
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'pending' => 'warning',
                        'approved' => 'success',
                        'shipped' => 'info',
                        'cancelled' => 'danger',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Order Price')
                    ->getStateUsing(fn ($record) => $record->products->sum(fn ($product) => $product->price * $product->pivot->quantity))
                    ->money('USD')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'shipped' => 'Shipped',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Filter by Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    

    public static function getRelations(): array
    {
        return [
            ProductRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
