<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserIpNumberResource\Pages;
use App\Filament\Resources\UserIpNumberResource\RelationManagers;
use App\Models\DueBill;
use App\Models\User;
use App\Models\UserIpNumber;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserIpNumberResource extends Resource
{
    protected static ?string $model = UserIpNumber::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?int $navigationSort = 2;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('package_id')
                    ->label('Package')
                    ->relationship('package', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('number')
                    ->unique(ignoreRecord: true)
                    ->label('IP Number')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get) {
                        if ($state) {
                            $assigned = UserIpNumber::where('number', $state)
                                ->where('id', '!=', $get('id') ?? 0) // ignore current record
                                ->first();

                            if ($assigned) {
                                Notification::make()
                                    ->title('IP Already Assigned')
                                    ->body("This IP number is already assigned to {$assigned->user->name}.")
                                    ->warning()
                                    ->send();
                            }
                        }
                    }),

                Textarea::make('note')->nullable(),

                Toggle::make('status')
                    ->label('Status')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->label('IP Number')->sortable()->searchable(),
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('package.name')->label('Package')->sortable()->searchable(),
                BooleanColumn::make('status')->label('Status')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
                TrashedFilter::make(),
            ])
            ->actions([
                Action::make('Add Due Bill')
                    ->button()
                    ->color('success')
                    ->modalHeading('Add Due Bill')
                    ->form([
                            Section::make()
                                ->columns(2)
                                ->schema([
                                    // Hidden fields auto-filled
                                    Forms\Components\Hidden::make('user_id')
                                        ->default(fn($get, $record) => $record->user_id),

                                    Forms\Components\Hidden::make('user_ip_number_id')
                                        ->default(fn($get, $record) => $record->id),

                                    Forms\Components\TextInput::make('call_rate')
                                        ->numeric()
                                        ->readOnly()
                                        ->reactive()
                                        ->required()
                                        ->default(fn($get, $record) => $record->package->call_rate ?? 0)
                                        ->visible(fn(callable $get) => $get('call_rate') != 0),

                                    Forms\Components\TextInput::make('minutes')
                                        ->numeric()
                                        ->required()
                                        ->default(0)
                                        ->reactive()
                                        ->visible(fn(callable $get) => $get('call_rate') != 0),

                                    Forms\Components\TextInput::make('service_charge')
                                        ->numeric()
                                        ->reactive()
                                        ->readOnly()
                                        ->required()
                                        ->default(fn($get, $record) => $record->package->price ?? 0)
                                        ->visible(fn(callable $get) => $get('service_charge') != 0),

                                    Forms\Components\TextInput::make('month')
                                        ->type('month')
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $get, callable $set, $livewire, $record) {

                                            $ipId = $get('user_ip_number_id') ?? $record->id;
                                            if ($state && $ipId) {
                                                $exists = DueBill::where('user_ip_number_id', $ipId)
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

                                    
                                    Forms\Components\Select::make('payment_status')
                                        ->options([
                                            'unpaid' => 'Unpaid',
                                            'paid' => 'Paid',
                                        ])
                                        ->default('unpaid')
                                        ->required(),
                                ]),

                        ]
                    )
                    ->action(function (array $data) {
                        // Save the due bill
                        DueBill::create($data);

                        // Success notification
                        Notification::make()
                            ->title('Due Bill Added')
                            ->body('The due bill has been successfully created.')
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListUserIpNumbers::route('/'),
            'create' => Pages\CreateUserIpNumber::route('/create'),
            'edit' => Pages\EditUserIpNumber::route('/{record}/edit'),
        ];
    }
}
