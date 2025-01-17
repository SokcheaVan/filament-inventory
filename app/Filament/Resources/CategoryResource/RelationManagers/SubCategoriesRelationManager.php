<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class SubCategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'sub_categories';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('labels.subcategory');
    }

    /**
     * @deprecated Override the `table()` method to configure the table.
     */
    protected static function getModelLabel(): ?string
    {
        return __('labels.subcategory');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        $headerActions = [
            Tables\Actions\CreateAction::make()
        ];

        if(auth()->user()->can('export', new SubCategory())) {
            $headerActions[] = ExportAction::make()->label(__('translations.export_excel'))->exports([
                ExcelExport::make()->withColumns([
                    Column::make('category.name')->heading('Category'),
                    Column::make('name')->heading('Name'),
                ]),
            ]);
        }

        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('translations.name')),
            ])
            ->filters([
                //
            ])
            ->headerActions($headerActions)
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
}
