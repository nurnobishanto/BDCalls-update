<?php

namespace App\Filament\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Resources\ManualPaymentGatewayResource\Pages;
use App\Filament\Resources\ManualPaymentGatewayResource\RelationManagers;
use App\Models\ManualPaymentGateway;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManualPaymentGatewayResource extends Resource
{
    protected static ?string $model = ManualPaymentGateway::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Payments';
    protected static ?string $label = 'Manual Payment Gateway';
    protected static ?int $navigationSort =51;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('number')->required()->maxLength(255)->unique(ignorable: fn ($record) => $record),
                Select::make('type')
                    ->required()
                    ->options([
                        'bank' => 'Bank',
                        'mobile' => 'Mobile',
                    ]),
                FileUpload::make('logo')->directory('manual-gateways/logos')->image()->nullable(),
                ColorPicker::make('color')->nullable(),
                TextInput::make('minimum_amount')->numeric()->required(),
                TextInput::make('maximum_amount')->numeric()->required(),
                Toggle::make('status')->default(true),
                TinyEditor::make('details')->label('Instructions')->nullable()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('number'),
                BadgeColumn::make('type')->colors([
                    'primary' => 'bank',
                    'success' => 'mobile',
                ]),
                IconColumn::make('status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x'),
                TextColumn::make('minimum_amount')->money('usd'),
                TextColumn::make('maximum_amount')->money('usd'),
                TextColumn::make('created_at')->dateTime('d M Y'),
            ])
            ->filters([

                // Status filter (active/inactive)
                SelectFilter::make('status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->label('Status'),

                // Type filter (bank/mobile)
                SelectFilter::make('type')
                    ->options([
                        'bank' => 'Bank',
                        'mobile' => 'Mobile',
                    ])
                    ->label('Gateway Type'),
                // Soft delete filter
                TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListManualPaymentGateways::route('/'),
            'create' => Pages\CreateManualPaymentGateway::route('/create'),
            'edit' => Pages\EditManualPaymentGateway::route('/{record}/edit'),
        ];
    }
}
