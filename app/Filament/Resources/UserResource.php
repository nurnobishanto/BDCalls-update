<?php

namespace App\Filament\Resources;


use App\Filament\Resources\UserResource\RelationManagers\IpNumbersRelationManager;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use App\Models\User;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return trans('filament-users::user.resource.label');
    }

    public static function getPluralLabel(): string
    {
        return trans('filament-users::user.resource.label');
    }

    public static function getLabel(): string
    {
        return trans('filament-users::user.resource.single');
    }

    public static function getNavigationGroup(): ?string
    {
        return config('filament-users.group');
    }

    public function getTitle(): string
    {
        return trans('filament-users::user.resource.title.resource');
    }

    public static function form(Form $form): Form
    {
        $rows = [
            TextInput::make('name')
                ->required()
                ->label(trans('filament-users::user.resource.name')),
            TextInput::make('email')
                ->email()
                ->required()
                ->label(trans('filament-users::user.resource.email')),


            Fieldset::make('Phone Information')
                ->schema([
                    Select::make('phone_country_code')
                        ->label('Country Code')
                        ->options(function () {
                            return \App\Models\Country::query()
                                ->orderBy('phone_code')
                                ->get()
                                ->mapWithKeys(function ($country) {
                                    return [$country->phone_code => "{$country->phone_code} ({$country->name})"];
                                });
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpan(2),

                    TextInput::make('phone')
                        ->label('Phone Number')
                        ->tel()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->columnSpan(3),
                ])
                ->markAsRequired()
                ->columns(5)->columnSpan(1),
            Fieldset::make('WhatsApp Information')
                ->schema([
                    Select::make('whatsapp_country_code')
                        ->label('Country Code')
                        ->options(function () {
                            return \App\Models\Country::query()
                                ->orderBy('phone_code')
                                ->get()
                                ->mapWithKeys(function ($country) {
                                    return [$country->phone_code => "{$country->phone_code} ({$country->name})"];
                                });
                        })
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->required()
                        ->columnSpan(2),

                    TextInput::make('whatsapp_number')
                        ->label('WhatsApp Number')
                        ->tel()
                        ->nullable()
                        ->columnSpan(3),
                ])
                ->columns(5)
                ->columnSpan(1),

            Toggle::make('whatsapp_sms')
                ->label('Whatsapp SMS')
                ->default(true),
            Toggle::make('phone_sms')
                ->label('Phone SMS')
                ->default(false),
            TextInput::make('password')
                ->label(trans('filament-users::user.resource.password'))
                ->password()
                ->required(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                ->nullable(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
                ->maxLength(255)
                ->same('password_confirmation')
                ->dehydrateStateUsing(static function ($state, $record) use ($form) {
                    return !empty($state)
                        ? Hash::make($state)
                        : $record->password;
                }),
            TextInput::make('password_confirmation')
                ->label('Confirm Password')
                ->password()
                ->maxLength(255)
                ->required(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                ->dehydrated(false), // Don't save this field to database
            Forms\Components\Section::make('NID Information')
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextInput::make('nid_number')->nullable(),
                    FileUpload::make('nid_font_image')
                        ->label('NID Front Image')
                        ->previewable()
                        ->downloadable()
                        ->image()
                        ->imageEditor()
                        ->imagePreviewHeight('150')
                        ->nullable(),

                    FileUpload::make('nid_back_image')
                        ->label('NID Back Image')
                        ->image()
                        ->previewable()
                        ->downloadable()
                        ->imageEditor()
                        ->imagePreviewHeight('150')
                        ->nullable(),
                ]),


            FileUpload::make('image')
                ->label('Profile Image')
                ->image()
                ->previewable()
                ->downloadable()
                ->imageEditor()
                ->imagePreviewHeight('150')
                ->nullable(),

//            FileUpload::make('user_images')
//                ->label('User Images')
//                ->image()
//                ->multiple()
//                ->reorderable()
//                ->imageEditor()
//                ->imagePreviewHeight('150')
//                ->nullable(),


            Toggle::make('is_blocked')
                ->label('Blocked')
                ->default(false),
        ];


        if (config('filament-users.shield') && class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class)) {
            $rows[] = Forms\Components\Select::make('roles')
                ->multiple()
                ->preload()
                ->relationship('roles', 'name')
                ->label(trans('filament-users::user.resource.roles'));
        }

        $form->schema($rows);

        return $form;
    }

    public static function table(Table $table): Table
    {
        if (class_exists(STS\FilamentImpersonate\Tables\Actions\Impersonate::class) && config('filament-users.impersonate')) {
            $table->actions([Impersonate::make('impersonate')]);
        }
        $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label(trans('filament-users::user.resource.name')),
                TextColumn::make('ip_number_list')
                    ->label('IP Numbers')
                    ->sortable()
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('ipNumbers', function ($q) use ($search) {
                            $q->where('number', 'like', "%{$search}%");
                        });
                    })->html(),

                TextColumn::make('contact')
                    ->label('Contact')

                    ->searchable(query: function ($query, $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('phone_country_code', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('whatsapp_full')
                    ->label('WhatsApp')
                    ->searchable(query: function ($query, $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('whatsapp_country_code', 'like', "%{$search}%")
                                ->orWhere('whatsapp_number', 'like', "%{$search}%");
                        });
                    }),



            ])
            ->filters([
                SelectFilter::make('whatsapp_sms')
                    ->label('Whatsapp SMS')
                    ->options([
                        1 => 'Yes',
                        0 => 'No',
                    ]),
                SelectFilter::make('phone_sms')
                    ->label('Phone SMS')
                    ->options([
                        1 => 'Yes',
                        0 => 'No',
                    ]),
                SelectFilter::make('is_blocked')
                    ->label('Blocked User')
                    ->options([
                        1 => 'Blocked',
                        0 => 'Unblocked',
                    ]),
                Tables\Filters\Filter::make('verified')
                    ->label(trans('filament-users::user.resource.verified'))
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('unverified')
                    ->label(trans('filament-users::user.resource.unverified'))
                    ->query(fn(Builder $query): Builder => $query->whereNull('email_verified_at')),
            ])
            ->actions([
                Impersonate::make('impersonate')
                    ->guard('web')
                    ->redirectTo(url('/user')),
                ActionGroup::make([
                    // Enable Phone SMS
                    Action::make('enablePhoneSms')
                        ->label('Enable Phone SMS')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->visible(fn ($record) => !$record->phone_sms)
                        ->requiresConfirmation()
                        ->action(function ($record, $action) {
                            $record->update(['phone_sms' => true]);

                            Notification::make()
                                ->title('Phone SMS Enabled')
                                ->success()
                                ->body("Phone SMS has been enabled for {$record->name}.")
                                ->send();

                            $action->success();
                        }),

                    // Disable Phone SMS
                    Action::make('disablePhoneSms')
                        ->label('Disable Phone SMS')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->visible(fn ($record) => $record->phone_sms)
                        ->requiresConfirmation()
                        ->action(function ($record, $action) {
                            $record->update(['phone_sms' => false]);

                            Notification::make()
                                ->title('Phone SMS Disabled')
                                ->warning()
                                ->body("Phone SMS has been disabled for {$record->name}.")
                                ->send();

                            $action->success();
                        }),

                    // Enable WhatsApp SMS
                    Action::make('enableWhatsapp')
                        ->label('Enable WhatsApp')
                        ->icon('heroicon-o-phone')
                        ->color('success')
                        ->visible(fn ($record) => !$record->whatsapp_sms)
                        ->requiresConfirmation()
                        ->action(function ($record, $action) {
                            $record->update(['whatsapp_sms' => true]);

                            Notification::make()
                                ->title('WhatsApp Enabled')
                                ->success()
                                ->body("WhatsApp has been enabled for {$record->name}.")
                                ->send();

                            $action->success();
                        }),

                    // Disable WhatsApp SMS
                    Action::make('disableWhatsapp')
                        ->label('Disable WhatsApp')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->visible(fn ($record) => $record->whatsapp_sms)
                        ->requiresConfirmation()

                        ->action(function ($record, $action) {
                            $record->update(['whatsapp_sms' => false]);

                            Notification::make()
                                ->title('WhatsApp Disabled')
                                ->warning()
                                ->body("WhatsApp has been disabled for {$record->name}.")
                                ->send();

                            $action->success();
                        }),

                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('enableWhatsappSms')
                    ->label('Enable WhatsApp SMS')
                    ->icon('heroicon-o-check')
                    ->action(fn($records) => $records->each->update(['whatsapp_sms' => true]))
                    ->requiresConfirmation()
                    ->color('success'),

                Tables\Actions\BulkAction::make('disableWhatsappSms')
                    ->label('Disable WhatsApp SMS')
                    ->icon('heroicon-o-x-circle')
                    ->action(fn($records) => $records->each->update(['whatsapp_sms' => false]))
                    ->requiresConfirmation()
                    ->color('danger'),
                // Phone SMS toggles
                Tables\Actions\BulkAction::make('enablePhoneSms')
                    ->label('Enable Phone SMS')
                    ->icon('heroicon-o-check-circle')
                    ->action(fn($records) => $records->each->update(['phone_sms' => true]))
                    ->requiresConfirmation()
                    ->color('success'),

                Tables\Actions\BulkAction::make('disablePhoneSms')
                    ->label('Disable Phone SMS')
                    ->icon('heroicon-o-minus-circle')
                    ->action(fn($records) => $records->each->update(['phone_sms' => false]))
                    ->requiresConfirmation()
                    ->color('danger'),
                Tables\Actions\BulkActionGroup::make([


                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
        return $table;
    }
    public static function getRelations(): array
    {
        return [
            IpNumbersRelationManager::class
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
