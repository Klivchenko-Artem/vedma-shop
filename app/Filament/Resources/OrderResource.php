<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Заказы';
    protected static ?string $modelLabel = 'Заказ';
    protected static ?string $pluralModelLabel = 'Заказы';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Клиент')->schema([
                    Forms\Components\TextInput::make('customer_name')
                        ->label('Имя')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('customer_phone')
                        ->label('Телефон')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('address')
                        ->label('Адрес доставки')
                        ->required()
                        ->rows(2)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('comment')
                        ->label('Комментарий')
                        ->autosize()
                        ->columnSpanFull(),
                ])->columns(2),

                Forms\Components\Section::make('Состав заказа')->schema([
                    Forms\Components\Repeater::make('items')
                        ->label('')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Товар')
                                ->disabled()
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('quantity')
                                ->label('Кол-во')
                                ->disabled()
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('price')
                                ->label('Цена')
                                ->prefix('₽')
                                ->disabled()
                                ->columnSpan(1),
                        ])
                        ->columns(4)
                        ->disabled()
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->columnSpanFull(),
                ]),

                Forms\Components\Section::make('Заказ')->schema([
                    Forms\Components\Select::make('status')
                        ->label('Статус')
                        ->options([
                            'new' => 'Новый',
                            'processing' => 'В обработке',
                            'delivering' => 'Доставляется',
                            'completed' => 'Выполнен',
                            'cancelled' => 'Отменён',
                        ])
                        ->default('new')
                        ->required(),
                    Forms\Components\TextInput::make('total')
                        ->label('Сумма')
                        ->numeric()
                        ->prefix('₽')
                        ->disabled(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('№')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Клиент')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Телефон')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Адрес')
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Комментарий')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('items')
                    ->label('Товары')
                    ->formatStateUsing(function ($state) {
                        if (!is_array($state)) return '—';
                        return collect($state)
                            ->map(fn ($item) => ($item['name'] ?? '?') . ' × ' . ($item['quantity'] ?? 1))
                            ->join(', ');
                    })
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Сумма')
                    ->money('RUB')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Статус')
                    ->colors([
                        'warning' => 'new',
                        'info' => 'processing',
                        'primary' => 'delivering',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Новый',
                        'processing' => 'В обработке',
                        'delivering' => 'Доставляется',
                        'completed' => 'Выполнен',
                        'cancelled' => 'Отменён',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'processing' => 'В обработке',
                        'delivering' => 'Доставляется',
                        'completed' => 'Выполнен',
                        'cancelled' => 'Отменён',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
