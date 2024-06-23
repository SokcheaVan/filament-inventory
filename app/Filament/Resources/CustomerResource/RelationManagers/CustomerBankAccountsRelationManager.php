<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Models\CustomerBankAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class CustomerBankAccountsRelationManager extends RelationManager
{
    protected static string $relationship = 'customer_bank_accounts';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('labels.customer_bank_account');
    }

    /**
     * @deprecated Override the `table()` method to configure the table.
     */
    protected static function getModelLabel(): ?string
    {
        return __('labels.customer_bank_account');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('bank_name')
                    ->label(__('translations.bank_name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('account_name')
                    ->label(__('translations.account_name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('account_number')
                    ->label(__('translations.account_number'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        $headerActions = [
            Tables\Actions\CreateAction::make()
        ];

        if(auth()->user()->can('export', new CustomerBankAccount())) {
            $headerActions[] = ExportAction::make()->label(__('translations.export_excel'))->exports([
                ExcelExport::make()->withColumns([
                    Column::make('customer.name')->heading('Customer'),
                    Column::make('bank_name')->heading('Bank Name'),
                    Column::make('account_name')->heading('Account Name'),
                    Column::make('account_number')->heading('Account Number'),
                ]),
            ]);
        }

        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('bank_name')->label(__('translations.bank_name')),
                Tables\Columns\TextColumn::make('account_name')->label(__('translations.account_name')),
                Tables\Columns\TextColumn::make('account_number')->label(__('translations.account_number')),
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
