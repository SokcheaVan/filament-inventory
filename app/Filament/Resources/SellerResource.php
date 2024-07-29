<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerResource\Pages;
use App\Filament\Resources\SellerResource\RelationManagers;
use App\Models\Seller;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class SellerResource extends Resource
{
    protected static ?string $model = Seller::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static $provinces = [
        'banteay_meanchey',
        'battambang',
        'kampong_cham',
        'kampong_chhnang',
        'kampong_speu',
        'kampong_thom',
        'kampot',
        'kandal',
        'kep',
        'koh_kong',
        'kratie',
        'mondulkiri',
        'oddar_meanchey',
        'pailin',
        'phnom_penh',
        'preah_sihanouk',
        'preah_vihear',
        'prey_veng',
        'pursat',
        'ratanakiri',
        'siem_Reap',
        'stung_treng',
        'svay_rieng',
        'takeo',
        'tboung_khmum',
    ];

    public static function getLabel(): ?string
    {
        return __('labels.seller');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('translations.sell');
    }

    public static function form(Form $form): Form
    {
        $province_options = [];
        foreach(self::$provinces as $province) {
            $province_options[$province] = __('translations.' . $province);
        }

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label(__('translations.name'))
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                ->label(__('translations.phone'))
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('other_phones')
                ->label(__('translations.other_phones'))
                ->required()
                ->maxLength(255),

                Forms\Components\Select::make('province')
                ->label(__('translations.province_or_city'))
                ->options($province_options)
                ->searchable()
                ->required(),

                Forms\Components\Textarea::make('address')
                ->columnSpanFull()
                ->label(__('translations.address')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label(__('translations.name'))
                ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                ->label(__('translations.phone'))
                ->description(fn (Seller $record): string | null => $record->other_phones)
                ->searchable(),

                Tables\Columns\TextColumn::make('province_label')
                ->label(__('translations.province_or_city'))
                ->description(function (Seller $record): string | null {
                    $str = $record->address;
                    if (strlen($str) > 50) {
                        $str = substr($str, 0, 47) . '...';
                    }
                    return $str;
                })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions(auth()->user()->can('export', new Seller) ? [
                ExportAction::make()->label(__('translations.export_excel'))->exports([
                    ExcelExport::make()->fromTable(),
                ])
            ] : [])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellers::route('/'),
            'create' => Pages\CreateSeller::route('/create'),
            'edit' => Pages\EditSeller::route('/{record}/edit'),
        ];
    }
}
