<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function getLabel(): ?string
    {
        return __('labels.order');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('translations.sell');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label(__('labels.customer'))
                    ->relationship('customer', 'name')
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('sellers')
                    ->multiple()
                    ->label(__('labels.sellers'))
                    ->relationship('sellers', 'name')
                    ->required()
                    ->searchable(),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Repeater::make('order_products')
                            ->label(__('labels.order_products'))
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->columnspan([
                                        'md' => 3
                                    ])
                                    ->label(__('labels.product'))
                                    ->relationship('product', 'name')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $product = Product::find($get('product_id'));

                                        if($product) {
                                            $set('price', $product->retail_price);
                                            $set('quantity', 1);
                                            $set('subtotal', $product->retail_price);
                                        }
                                    }),

                                Forms\Components\TextInput::make('price')
                                    ->columnspan([
                                        'md' => 3
                                    ])
                                    ->label(__('translations.price'))
                                    ->required()
                                    ->numeric()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $quantity = $get('quantity');
                                        $price = $get('price');
                                        if($quantity && $price) {
                                            $set('subtotal', $quantity * $price);
                                        }
                                    }),

                                Forms\Components\TextInput::make('quantity')
                                    ->columnspan([
                                        'md' => 3
                                    ])
                                    ->label(__('translations.quantity'))
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $quantity = $get('quantity');
                                        $price = $get('price');
                                        if($quantity && $price) {
                                            $set('subtotal', $quantity * $price);
                                        }
                                    }),

                                Forms\Components\TextInput::make('subtotal')
                                    ->live()
                                    ->columns([
                                        'md' => 3
                                    ])
                                    ->label(__('translations.subtotal'))
                                    ->required()
                                    ->readonly()
                                    ->numeric(),
                            ])
                            ->defaultItems(1)
                            ->columns([
                                'md' => 12
                            ])
                            // Repeatable field is live so that it will trigger the state update on each change
                            ->live()
                            // After adding a new row, we need to update the totals
                            ->afterStateUpdated(function (Get $get, Set $set, \Livewire\Component $livewire) {
                                self::updateTotals($get, $set, $livewire);
                            })
                            // After deleting a row, we need to update the totals
                            ->deleteAction(
                                fn(Action $action) => $action->after(fn(Get $get, Set $set, \Livewire\Component $livewire) => self::updateTotals($get, $set, $livewire)),
                            )
                    ]),

                    Forms\Components\TextInput::make('subtotal')
                        ->live()
                        ->columns([
                            'md' => 3
                        ])
                        ->label(__('translations.subtotal'))
                        ->required()
                        ->readonly()
                        ->numeric(),

                    Forms\Components\TextInput::make('total')
                        ->live()
                        ->columns([
                            'md' => 3
                        ])
                        ->label(__('translations.total'))
                        ->required()
                        ->readonly()
                        ->numeric(),

                    Forms\Components\DateTimePicker::make('order_at')
                        ->columns([
                            'md' => 3
                        ])
                        ->label(__('translations.order_at'))
                        ->default(now())
                        ->required(),
            ]);
    }

    // This function updates totals based on the selected products and quantities
    public static function updateTotals(Get $get, Set $set, \Livewire\Component $livewire): void
    {
        // Retrieve the state path of the form. Most likely it's `data` but it could be something else.
        $statePath = $livewire->getFormStatePath();

        $selectedProducts = data_get($livewire, $statePath . '.order_products');
        if (collect($selectedProducts)->isEmpty()) {
            return;
        }

        // // Retrieve all selected products and remove empty rows
        // $selectedProducts = collect($get('order_products'))->filter(fn($item) => !empty($item['product_id']) && !empty($item['quantity']) && !empty($item['price']));

        $subtotal = collect($selectedProducts)->sum('subtotal');

        // Update the state with the new values
        $set('subtotal', $subtotal);
        $set('total', $subtotal);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('labels.customer'))
                    ->searchable()
                    ->sortable()
                    ->description(fn (Order $record): string => $record->customer->phone ?? '---'),

                Tables\Columns\TextColumn::make('total')
                    ->label(__('translations.total')),

                Tables\Columns\TextColumn::make('order_date')
                    ->label(__('translations.date'))
                    ->description(fn (Order $record): string => Carbon::parse($record->order_at)->format('h:i A')),

                // Tables\Columns\TextColumn::make('retail_price')
                // ->label(__('translations.retail_price')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
