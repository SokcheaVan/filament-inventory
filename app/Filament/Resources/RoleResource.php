<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
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

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function getNavigationGroup(): ?string
    {
        return __('translations.security');
    }

    protected static ?int $navigationSort = 2;

    public static function getLabel(): ?string
    {
        return __('labels.role');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label(__('translations.name'))
                ->required()
                ->maxLength(255),

                Forms\Components\Section::make(__('labels.permissions'))
                ->description(__('translations.permission_description'))//'the action of officially allowing someone to do a particular thing; consent or authorization.')
                ->columns([
                    'sm' => 2,
                    'xl' => 3
                ])
                ->schema([


                    Forms\Components\Section::make(__('translations.general'))
                    // ->description('the action of officially allowing someone to do a particular thing; consent or authorization.')
                    ->columns([
                        'sm' => 2,
                        'xl' => 3
                    ])
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                        ->label(__('labels.dashboard'))
                        ->options([
                            'dashboard-read' => __('translations.read'),
                        ])
                        ->required(),
                    ]),


                    Forms\Components\Section::make(__('translations.security'))
                    ->columns([
                        'sm' => 2,
                        'xl' => 3
                    ])
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                        ->label(__('labels.user'))
                        ->options([
                            'user-read' => __('translations.read'),
                            'user-create' => __('translations.create'),
                            'user-update' => __('translations.update'),
                            'user-delete' => __('translations.delete'),
                        ])
                        ->required(),

                        Forms\Components\CheckboxList::make('permissions')
                        ->label(__('labels.role'))
                        ->options([
                            'role-read' => __('translations.read'),
                            'role-create' => __('translations.create'),
                            'role-update' => __('translations.update'),
                            'role-delete' => __('translations.delete'),
                        ])
                        ->required(),
                    ]),

                    Forms\Components\Section::make(__('translations.sell'))
                    ->columns([
                        'sm' => 2,
                        'xl' => 3
                    ])
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                        ->label(__('labels.customer'))
                        ->options([
                            'customer-read' => __('translations.read'),
                            'customer-create' => __('translations.create'),
                            'customer-update' => __('translations.update'),
                            'customer-delete' => __('translations.delete'),
                            'customer-export_excel' => __('translations.export_excel'),
                        ])
                        ->required(),

                        Forms\Components\CheckboxList::make('permissions')
                        ->label(__('labels.customer_bank_account'))
                        ->options([
                            'customer_bank_account-read' => __('translations.read'),
                            'customer_bank_account-create' => __('translations.create'),
                            'customer_bank_account-update' => __('translations.update'),
                            'customer_bank_account-delete' => __('translations.delete'),
                            'customer_bank_account-export_excel' => __('translations.export_excel'),
                        ])
                        ->required(),

                        Forms\Components\CheckboxList::make('permissions')
                        ->label(__('labels.seller'))
                        ->options([
                            'seller-read' => __('translations.read'),
                            'seller-create' => __('translations.create'),
                            'seller-update' => __('translations.update'),
                            'seller-delete' => __('translations.delete'),
                            'seller-export_excel' => __('translations.export_excel'),
                        ])
                        ->required(),

                        // Forms\Components\CheckboxList::make('permissions')
                        // ->label(__('labels.product'))
                        // ->options([
                        //     'product-read' => __('translations.read'),
                        //     'product-create' => __('translations.create'),
                        //     'product-update' => __('translations.update'),
                        //     'product-delete' => __('translations.delete'),
                        // ])
                        // ->required(),
                    ]),

                    Forms\Components\Section::make(__('translations.inventory'))
                    ->columns([
                        'sm' => 2,
                        'xl' => 3
                    ])
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                        ->label(__('labels.category'))
                        ->options([
                            'category-read' => __('translations.read'),
                            'category-create' => __('translations.create'),
                            'category-update' => __('translations.update'),
                            'category-delete' => __('translations.delete'),
                            'category-export_excel' => __('translations.export_excel'),
                        ])
                        ->required(),

                        Forms\Components\CheckboxList::make('permissions')
                        ->label(__('labels.subcategory'))
                        ->options([
                            'subcategory-read' => __('translations.read'),
                            'subcategory-create' => __('translations.create'),
                            'subcategory-update' => __('translations.update'),
                            'subcategory-delete' => __('translations.delete'),
                            'subcategory-export_excel' => __('translations.export_excel'),
                        ])
                        ->required(),

                        Forms\Components\CheckboxList::make('permissions')
                        ->label(__('labels.product'))
                        ->options([
                            'product-read' => __('translations.read'),
                            'product-create' => __('translations.create'),
                            'product-update' => __('translations.update'),
                            'product-delete' => __('translations.delete'),
                            'product-export_excel' => __('translations.export_excel'),
                        ])
                        ->required(),
                    ])
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('translations.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('permission_labels')
                    ->label(__('labels.permissions'))
                    ->badge()
                    ->separator(',')
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
