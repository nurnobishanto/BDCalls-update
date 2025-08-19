<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RechargeResource\Pages;
use App\Filament\Resources\RechargeResource\RelationManagers;
use App\Models\Recharge;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RechargeResource extends Resource
{
    protected static ?string $model = Recharge::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->required(),

                TextInput::make('number')
                    ->label('IP Number')
                    ->required(),

                TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->required(),

                Select::make('payment_method')
                    ->options([
                        'manual' => 'Manual',
                        'automatic' => 'Automatic',
                    ])
                    ->label('Payment Method')
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in-progress' => 'In Progress',
                        'complete' => 'Complete',
                        'reject' => 'Rejected',
                    ])
                    ->required(),

                Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'cancel' => 'Cancel',
                        'failed' => 'Failed',
                        'paid' => 'Paid',
                    ])
                    ->required(),

                KeyValue::make('payment_request')
                    ->label('Payment Request')
                    ->disabled()
                    ->json(),

                KeyValue::make('payment_response')
                    ->label('Payment Response')
                    ->disabled()
                    ->json(),

                Forms\Components\Textarea::make('note')
                    ->label('Note'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('number')->label('IP Number')->sortable()->searchable(),
                TextColumn::make('amount')->label('Amount')->money('BDT', true),
                TextColumn::make('payment_method')->label('Payment Method')->sortable(),
                TextColumn::make('status')->label('Status')->sortable(),
                TextColumn::make('payment_status')->label('Payment Status')->sortable(),
                TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in-progress' => 'In Progress',
                        'complete' => 'Complete',
                        'reject' => 'Rejected',
                    ]),
                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'cancel' => 'Cancel',
                        'failed' => 'Failed',
                        'paid' => 'Paid',
                    ]),
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
            'index' => Pages\ListRecharges::route('/'),
            'create' => Pages\CreateRecharge::route('/create'),
            'edit' => Pages\EditRecharge::route('/{record}/edit'),
        ];
    }
}
