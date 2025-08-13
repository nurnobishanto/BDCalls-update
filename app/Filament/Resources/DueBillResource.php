<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DueBillResource\Pages;
use App\Filament\Resources\DueBillResource\RelationManagers;
use App\Models\DueBill;
use App\Models\User;
use App\Models\UserIpNumber;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DueBillResource extends Resource
{
    protected static ?string $model = DueBill::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static function calculateTotal(callable $get)
    {
        $callRate = (float) $get('call_rate');
        $minutes = (float) $get('minutes');
        $serviceCharge = (float) $get('service_charge');

        return $serviceCharge + (($callRate/100) * $minutes);
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->required(),

                Forms\Components\Select::make('user_ip_number_id')
                    ->label('User IP Number')
                    ->options(function (callable $get) {
                        $userId = $get('user_id');
                        if (!$userId) {
                            return [];
                        }
                        return UserIpNumber::where('user_id', $userId)
                            ->pluck('number', 'id');
                    })
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set, $livewire) {
                        if ($state) {
                            $ipNumber = UserIpNumber::with('package')->find($state);
                            if ($ipNumber && $ipNumber->package) {
                                $set('call_rate', $ipNumber->package->call_rate);
                                $set('service_charge', $ipNumber->package->price);
                                $set('total', self::calculateTotal($get));
                            }

                            // Live duplicate check
                            $month = $get('month');
                            if ($month) {
                                $exists = DueBill::where('user_ip_number_id', $state)
                                    ->where('id', '!=', $livewire->record->id)
                                    ->where('month', $month)
                                    ->exists();

                                if ($exists) {
                                    Notification::make()
                                        ->title('Duplicate Due Bill')
                                        ->body('A due bill already exists for this IP number and month.')
                                        ->warning()
                                        ->send();
                                    $set('month', null);
                                }
                            }
                        }
                    })
                    ->required(),


        Forms\Components\TextInput::make('call_rate')
                    ->numeric()
                    ->readOnly()
                    ->reactive()
                    ->required()
                    ->visible(fn (callable $get) => $get('call_rate') != 0)
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $set('total', self::calculateTotal($get));
                    }),

                Forms\Components\TextInput::make('minutes')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->visible(fn (callable $get) => $get('call_rate') != 0)
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $set('total', self::calculateTotal($get));
                    }),

                Forms\Components\TextInput::make('service_charge')
                    ->numeric()
                    ->reactive()
                    ->required()
                    ->readOnly()
                    ->default(0)
                    ->visible(fn (callable $get) => $get('service_charge') != 0)
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $set('total', self::calculateTotal($get));
                    }),

                Forms\Components\TextInput::make('month')
                    ->type('month')
                    ->default(now()->format('Y-m'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set, $livewire) {
                        $ipId = $get('user_ip_number_id');
                        if ($state && $ipId) {
                            $exists = DueBill::where('user_ip_number_id', $ipId)
                                ->where('id', '!=', $livewire->record->id)
                                ->where('month', $state)
                                ->exists();

                            if ($exists) {
                                Notification::make()
                                    ->title('Duplicate Due Bill')
                                    ->body('A due bill already exists for this IP number and month.')
                                    ->warning()
                                    ->send();

                                $set('month', null);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('total')
                    ->numeric()
                    ->reactive()
                    ->readOnly()
                    ->required(),

                Forms\Components\Select::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                    ])
                    ->default('unpaid')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('userIpNumber.number')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('call_rate'),
                Tables\Columns\TextColumn::make('minutes'),
                Tables\Columns\TextColumn::make('service_charge'),
                Tables\Columns\TextColumn::make('month'),
                Tables\Columns\TextColumn::make('total'),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'danger' => 'unpaid',
                        'success' => 'paid',
                    ]),
            ])
            ->filters([
                // Filter by User
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name'),

                // Filter by IP Number
                Tables\Filters\SelectFilter::make('user_ip_number_id')
                    ->label('IP Number')
                    ->relationship('userIpNumber', 'number'),

                // Filter by Payment Status
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                    ]),
                Tables\Filters\TrashedFilter::make()
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
            'index' => Pages\ListDueBills::route('/'),
            'create' => Pages\CreateDueBill::route('/create'),
            'edit' => Pages\EditDueBill::route('/{record}/edit'),
        ];
    }
}
