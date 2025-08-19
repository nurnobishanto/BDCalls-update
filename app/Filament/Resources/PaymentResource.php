<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
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

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('User'),

                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'invoice_no')
                    ->required()
                    ->label('Order'),

                Forms\Components\TextInput::make('transaction_id')
                    ->label('Transaction ID')
                    ->disabled(),

                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('payment_method')
                    ->options([
                        'manual' => 'Manual',
                        'pay_station' => 'PayStation',
                        'eps' => 'EPS',
                    ])
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->required(),

                KeyValue::make('request')
                    ->label('Request Data')
                    ->disabled()
                    ->afterStateHydrated(function (KeyValue $component, $state) {
                        if (is_string($state)) {
                            $component->state(json_decode($state, true));
                        }
                    }),

                KeyValue::make('response')
                    ->label('Response Data')
                    ->disabled()
                    ->afterStateHydrated(function (KeyValue $component, $state) {
                        if (is_string($state)) {
                            $component->state(json_decode($state, true));
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_id')->label('Transaction')->sortable()->searchable(),
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('order.invoice_no')->label('Order')->sortable(),
                TextColumn::make('amount')->label('Amount')->money('BDT', true),
                TextColumn::make('payment_method')->label('Method')->sortable(),
                TextColumn::make('status')->label('Status')->sortable(),
                TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
                SelectFilter::make('payment_method')
                    ->options([
                        'manual' => 'Manual',
                        'pay_station' => 'PayStation',
                        'eps' => 'EPS',

                    ]),
            ])->defaultSort('created_at', 'desc')
            ->actions([
                // ✅ Approve Payment Button
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(Payment $record) => $record->status !== 'paid')
                    ->action(function (Payment $record, \Filament\Tables\Actions\Action $action) {
                        // 1️⃣ Update payment status to 'paid'
                        $record->update([
                            'status' => 'completed',
                        ]);

                        // 2️⃣ Call your OrderController logic
                        app(\App\Http\Controllers\OrderController::class)
                            ->order_paid($record);

                        // 3️⃣ Show success notification
                        $action->successNotification('Payment marked as paid and order updated.');
                    }),

                ActionGroup::make([
                    ViewAction::make(),
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
            'index' => Pages\ListPayments::route('/'),
            //'create' => Pages\CreatePayment::route('/create'),
            //'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
