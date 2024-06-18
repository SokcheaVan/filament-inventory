<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\SubCategory;
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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('code')
                ->required()
                ->hintAction(
                    Action::make('generate')
                    ->action(function (Set $set, Get $get): void {
                        $name = $get('name');
                        // Extract first two digits (efficiently)
                        if($name) {
                            $firstTwoDigits = substr($name, 0, 2) ;

                            // Generate the remaining part (optimized for clarity)
                            $remainingPart = random_int(1000, 9999); // Inclusive range

                            // Combine the parts and return the final code
                            $set('code', $firstTwoDigits . $remainingPart);
                        } else {
                            $set('code', random_int(100000, 999999));
                        }
                    })
                )
                ->maxLength(255),

                Forms\Components\Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->required()
                ->live()
                ->afterStateUpdated(function (Get $get, Set $set, \Livewire\Component $livewire) {
                    $set('subcategory_id', null);
                    $livewire->subcategories = SubCategory::where('category_id', $get('category_id'))->pluck('name', 'id')->toArray();
                }),

                Forms\Components\Select::make('subcategory_id')
                ->label('Sub Category')
                ->options(fn (\Livewire\Component $livewire): array => $livewire->subcategories),

                Forms\Components\TextInput::make('cost_price')
                ->required()
                ->numeric(),

                Forms\Components\TextInput::make('retail_price')
                ->required()
                ->numeric(),

                Forms\Components\TextInput::make('minimum_retail_price')
                ->numeric(),

                Forms\Components\Select::make('status')
                ->default(1)
                ->options([
                    0 => 'Inactive',
                    1 => 'Active'
                ]),

                Forms\Components\Textarea::make('description')
                ->columnSpanFull()
                ->required(),

                Forms\Components\FileUpload::make('image')
                ->columnSpanFull()
                ->image()
                ->imageEditor()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->description(fn (Product $record): string => $record->subcategory->name ?? '---'),

                Tables\Columns\TextColumn::make('cost_price'),

                Tables\Columns\TextColumn::make('retail_price'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
