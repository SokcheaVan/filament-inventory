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

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Security';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

                Forms\Components\Section::make('Permissions')
                ->description('the action of officially allowing someone to do a particular thing; consent or authorization.')
                ->columns([
                    'sm' => 2,
                    'xl' => 3
                ])
                ->schema([


                    Forms\Components\Section::make('General')
                    // ->description('the action of officially allowing someone to do a particular thing; consent or authorization.')
                    ->columns([
                        'sm' => 2,
                        'xl' => 3
                    ])
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                        ->label('Dashboard')
                        ->options([
                            'dashboard-read' => 'View',
                        ])
                        ->required(),
                    ]),


                    Forms\Components\Section::make('Security')
                    // ->description('the action of officially allowing someone to do a particular thing; consent or authorization.')
                    ->columns([
                        'sm' => 2,
                        'xl' => 3
                    ])
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                        ->label('User')
                        ->options([
                            'user-read' => 'Read',
                            'user-create' => 'Create',
                            'user-update' => 'Update',
                            'user-delete' => 'Delete',
                        ])
                        ->required(),

                        Forms\Components\CheckboxList::make('permissions')
                        ->label('Role')
                        ->options([
                            'role-read' => 'Read',
                            'role-create' => 'Create',
                            'role-update' => 'Update',
                            'role-delete' => 'Delete',
                        ])
                        ->required(),
                    ]),




                    Forms\Components\Section::make('Inventory')
                    // ->description('the action of officially allowing someone to do a particular thing; consent or authorization.')
                    ->columns([
                        'sm' => 2,
                        'xl' => 3
                    ])
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                        ->label('Category')
                        ->options([
                            'category-read' => 'Read',
                            'category-create' => 'Create',
                            'category-update' => 'Update',
                            'category-delete' => 'Delete',
                        ])
                        ->required(),

                        Forms\Components\CheckboxList::make('permissions')
                        ->label('Sub Category')
                        ->options([
                            'subcategory-read' => 'Read',
                            'subcategory-create' => 'Create',
                            'subcategory-update' => 'Update',
                            'subcategory-delete' => 'Delete',
                        ])
                        ->required(),

                        Forms\Components\CheckboxList::make('permissions')
                        ->label('Product')
                        ->options([
                            'product-read' => 'Read',
                            'product-create' => 'Create',
                            'product-update' => 'Update',
                            'product-delete' => 'Delete',
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
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('permission_labels')
                    ->label('Permissions')
                    ->badge()
                    ->separator(','),
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
