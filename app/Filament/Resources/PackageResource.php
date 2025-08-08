<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers;
use App\Models\Package;
use Filament\Actions\RestoreAction;
use Filament\Forms;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Settings';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('প্যাকেজ')
                    ->required()
                    ->maxLength(255),

                TextInput::make('registration_number')
                    ->label('নিবন্ধন নম্বর')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('user')
                    ->label('ব্যবহারকারী')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('call_channel')
                    ->label('কল চ্যানেল')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('call_channel_charges')
                    ->label('কল চ্যানেল চার্জ')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('additional_extensions')
                    ->label('অতিরিক্ত এক্সটেনশন')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('ivr_support')
                    ->label('আইভিআর সাপোর্ট')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('web_space')
                    ->label('ওয়েব স্পেস')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('ram')
                    ->label('RAM')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('call_record')
                    ->label('কল রেকর্ড')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('voice_mail')
                    ->label('ভয়েস মেইল')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('call_forward')
                    ->label('কল ফরওয়ার্ড')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('call_transfer')
                    ->label('কল ট্রান্সফার')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('data_backup')
                    ->label('ডাটা ব্যাকআপ')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('recovery')
                    ->label('রিকভারি/রিস্টোর')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('ring_group')
                    ->label('রিং গ্রুপ')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('amber_blacklist')
                    ->label('নাম্বার ব্ল্যাকলিস্ট')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('call_charge_mobile_tnt')
                    ->label('কল চার্জ (মোবাইল & TNT)')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('pulse')
                    ->label('পালস')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('call_charges_ivr_number')
                    ->label('কল চার্জ (আইভিআর নাম্বার)')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('call_charges_own_network')
                    ->label('কল চার্জ (নিজস্ব নেটওয়ার্ক)')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('incoming_charges')
                    ->label('ইনকামিং চার্জ')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('supported_devices')
                    ->label('সাপোর্টেড ডিভাইস')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('spam_filter')
                    ->label('স্প্যাম ফিল্টার')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('connection_type')
                    ->label('সংযোগ ধরন')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('connection_method')
                    ->label('সংযোগ পদ্ধতি')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('custom_configuration')
                    ->label('কাস্টম কনফিগারেশন')
                    ->nullable(),

                TextInput::make('connection_charges')
                    ->label('সংযোগ চার্জ')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('uptime_guarantee')
                    ->label('আপটাইম গ্যারান্টি')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('control_panel')
                    ->label('কন্ট্রোল প্যানেল')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('account_will_remain_day')
                    ->label('একাউন্ট অ-ব্যবহৃত অবস্থায় সক্রিয় থাকবে')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('automatic_termination_day')
                    ->label('স্বয়ংক্রিয় টার্মিনেশন (অব্যবহৃত)')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('call_rate')
                    ->label('কল রেট')
                    ->numeric()
                    ->default(0),

                TextInput::make('price')
                    ->label('মূল্য')
                    ->numeric()
                    ->required()
                    ->default(0),

                Toggle::make('status')
                    ->label('স্ট্যাটাস')
                    ->default(true),
                Toggle::make('is_hidden')
                    ->label('Hidden')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('প্যাকেজ ')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price')->label('মূল্য')->sortable(),
                Tables\Columns\TextColumn::make('call_rate')->label('কল রেট')->sortable(),
                Tables\Columns\IconColumn::make('status')->label('স্ট্যাটাস')->boolean(),
                Tables\Columns\IconColumn::make('is_hidden')->label('Hidden Package')->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('স্ট্যাটাস')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
                SelectFilter::make('is_hidden')
                    ->label('Hidden Package')
                    ->options([
                        1 => 'Hidden',
                        0 => 'Public',
                    ]),
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
