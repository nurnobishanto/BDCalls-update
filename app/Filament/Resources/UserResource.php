<?php

namespace App\Filament\Resources;

use Filament\Actions\RestoreAction;
use Filament\Forms;
use App\Models\User;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Actions\ForceDeleteAction;
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


            TextInput::make('password')
                ->label(trans('filament-users::user.resource.password'))
                ->password()
                ->maxLength(255)
                ->dehydrateStateUsing(static function ($state, $record) use ($form) {
                    return !empty($state)
                        ? Hash::make($state)
                        : $record->password;
                }),
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

            Select::make('is_blocked')
                ->label('Blocked')
                ->options([
                    false => 'No',
                    true => 'Yes',
                ])->default(false),
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
        if(class_exists( STS\FilamentImpersonate\Tables\Actions\Impersonate::class) && config('filament-users.impersonate')){
            $table->actions([Impersonate::make('impersonate')]);
        }
        $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label(trans('filament-users::user.resource.id')),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label(trans('filament-users::user.resource.name')),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label(trans('filament-users::user.resource.email')),
                IconColumn::make('email_verified_at')
                    ->boolean()
                    ->sortable()
                    ->searchable()
                    ->label(trans('filament-users::user.resource.email_verified_at')),
                TextColumn::make('created_at')
                    ->label(trans('filament-users::user.resource.created_at'))
                    ->dateTime('M j, Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(trans('filament-users::user.resource.updated_at'))
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
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
        return $table;
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
