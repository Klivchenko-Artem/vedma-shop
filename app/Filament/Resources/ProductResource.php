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

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Товары';
    protected static ?string $modelLabel = 'Товар';
    protected static ?string $pluralModelLabel = 'Товары';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основное')->schema([
                    Forms\Components\Select::make('category_id')
                        ->label('Категория')
                        ->relationship('category', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('name')
                        ->label('Название')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Forms\Set $set, ?string $state, ?Product $record) {
                            if ($record) return;
                            $slug = \Str::slug($state);
                            $original = $slug;
                            $i = 2;
                            while (Product::where('slug', $slug)->exists()) {
                                $slug = $original . '-' . $i++;
                            }
                            $set('slug', $slug);
                        }),
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Генерируется автоматически из названия'),
                    Forms\Components\TextInput::make('price')
                        ->label('Цена')
                        ->required()
                        ->numeric()
                        ->prefix('₽'),
                    Forms\Components\Toggle::make('is_popular')
                        ->label('Популярный'),
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Сортировка')
                        ->numeric()
                        ->default(0),
                ])->columns(2),

                Forms\Components\Section::make('Описание')->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Описание')
                        ->rows(4)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('composition')
                        ->label('Состав')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

                Forms\Components\Section::make('Изображения')->schema([
                    Forms\Components\FileUpload::make('images')
                        ->label('Фото')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->directory('products')
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->money('RUB')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_popular')
                    ->label('Популярный')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Сорт.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Категория')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_popular')
                    ->label('Популярные'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
