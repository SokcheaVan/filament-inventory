<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function getNavigationGroup(): ?string
    {
        return __('translations.security');
    }

    protected static ?int $navigationSort = 1;

    public static function getLabel(): ?string
    {
        return __('labels.user');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label(__('translations.name'))
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('email')
                ->label(__('translations.email'))
                ->email()
                ->required()
                ->maxLength(255),

                Forms\Components\Toggle::make('is_update_password')
                ->label(__('translations.update_password?'))
                ->reactive()
                ->requiredWith('price')
                ->hidden($form->getOperation() == 'create')
                ->afterStateUpdated(
                    fn ($state, callable $set) => $state ? $set('password', null) : $set('password', 'hidden')
                )
                ->columnSpan(12),

                Forms\Components\TextInput::make('password')
                ->label(__('translations.password'))
                ->password()
                ->required()
                ->hidden(
                    fn (Get $get): bool => $form->getOperation() == 'edit' && $get('is_update_password') == false
                )
                ->maxLength(255),

                Forms\Components\Select::make('role')
                ->label(__('labels.role'))
                ->relationship('role', 'name')
                ->required()
                ->searchable(),
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

                Tables\Columns\TextColumn::make('email')
                    ->label(__('translations.email'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role.name')
                    ->label(__('labels.role'))
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
