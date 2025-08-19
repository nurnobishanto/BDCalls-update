<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\Section::make('Order Info')
                        ->schema([
                            Forms\Components\TextInput::make('invoice_no')
                                ->disabled()
                                ->label('Invoice No'),
                            Forms\Components\Select::make('user_id')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->required(),
                            Forms\Components\Select::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'processing' => 'Processing',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('total')
                                ->numeric()
                                ->required(),
                        ]),
                    Forms\Components\Section::make('Payment Info')
                        ->schema([
                            Forms\Components\Select::make('payment_method')
                                ->options([
                                    'eps' => 'EPS',
                                    'pay_station' => 'PayStation',
                                    'manual' => 'Manual',
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('transaction_id')->disabled(),
                            Forms\Components\DateTimePicker::make('paid_at'),
                        ]),
                    Forms\Components\Section::make('Billing Details')
                        ->schema([
                            Forms\Components\Textarea::make('billing_details')
                                ->json()
                                ->label('Billing Details'),
                        ]),
                ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('invoice_no')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('total')->money('usd', true),
                Tables\Columns\TextColumn::make('status')->badge([
                    'pending' => 'warning',
                    'processing' => 'primary',
                    'completed' => 'success',
                    'cancelled' => 'danger',
                ]),
                Tables\Columns\TextColumn::make('payment_method')->label('Payment Method'),
                Tables\Columns\TextColumn::make('paid_at')->dateTime(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ]),
                Tables\Filters\SelectFilter::make('payment_method')->options([
                    'eps' => 'EPS',
                    'pay_station' => 'PayStation',
                    'manual' => 'Manual',
                ]),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    ReplicateAction::make()
                        ->using(function ($record) {
                            $copy = $record->replicate();

                            // Modify the name attribute by appending " copy"
                            if ($copy->name) {
                                $copy->name = $copy->name . ' copy';
                            }

                            $copy->save();

                            return $copy;
                        }),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
