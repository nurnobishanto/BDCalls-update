<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplyNumberResource\Pages;
use App\Filament\Resources\ApplyNumberResource\RelationManagers;
use App\Models\ApplyNumber;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
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
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApplyNumberResource extends Resource
{
    protected static ?string $model = ApplyNumber::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->preload()
                    ->searchable(),
                Select::make('account_type')
                    ->options([
                        'personal' => 'Personal',
                        'business' => 'Business',
                    ])
                    ->default('pending')
                    ->required(),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('company_name')->maxLength(255),
                TextInput::make('email')->email()->maxLength(255),
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
                TextInput::make('ip_number')->maxLength(50),
                TextInput::make('enather_ip_number')->maxLength(50),
                FileUpload::make('nid_font_image')
                    ->previewable()
                    ->downloadable()
                    ->image()
                    ->imageEditor(),
                FileUpload::make('nid_back_image')
                    ->previewable()
                    ->downloadable()
                    ->image()
                    ->imageEditor(),
                FileUpload::make('trade_license')
                    ->previewable()
                    ->downloadable()
                    ->image()
                    ->imageEditor(),
                FileUpload::make('selfie_photo')
                    ->previewable()
                    ->downloadable()
                    ->imageEditor()
                    ->image(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'progress' => 'Progress',
                        'reject' => 'Reject',
                        'resolved' => 'Resolved',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ip_number')->label('IP Number')->searchable()->sortable(),
                TextColumn::make('user.name')->label('User')->searchable()->sortable(),
                TextColumn::make('account_type')->sortable()->searchable(),
                SelectColumn::make('status')->options([
                    'pending' => 'Pending',
                    'progress' => 'Progress',
                    'reject' => 'Reject',
                    'resolved' => 'Resolved',
                ])->sortable()->searchable(),
            ])->defaultSort('id','desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'progress' => 'Progress',
                    'reject' => 'Reject',
                    'resolved' => 'Resolved',
                ]),
                Tables\Filters\SelectFilter::make('account_type')->options([
                    'personal' => 'Personal',
                    'business' => 'Business',
                ]),
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListApplyNumbers::route('/'),
            'create' => Pages\CreateApplyNumber::route('/create'),
            'edit' => Pages\EditApplyNumber::route('/{record}/edit'),
        ];
    }
}
