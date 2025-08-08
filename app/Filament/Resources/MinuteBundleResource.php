<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MinuteBundleResource\Pages;
use App\Filament\Resources\MinuteBundleResource\RelationManagers;
use App\Models\MinuteBundle;
use Filament\Actions\RestoreAction;
use Filament\Forms;
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

class MinuteBundleResource extends Resource
{
    protected static ?string $model = MinuteBundle::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Minute Bundle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('ব্যান্ডেল')
                    ->required(),

                Forms\Components\TextInput::make('incoming_charge')
                    ->label('ইনকামিং চার্জ'),

                Forms\Components\TextInput::make('ip_number_charge')
                    ->label('কল চার্জ আইপি নাম্বার'),

                Forms\Components\TextInput::make('extension_charge')
                    ->label('কল চার্জ এক্সটেনশন'),

                Forms\Components\TextInput::make('outgoing_call_charge')
                    ->label('আউটগোয়িং কল চার্জ'),

                Forms\Components\TextInput::make('pulse')
                    ->label('পালস'),

                Forms\Components\TextInput::make('minutes')
                    ->label('মিনিট')
                    ->numeric(),

                Forms\Components\TextInput::make('validity')
                    ->label('মেয়াদ'),

                Forms\Components\TextInput::make('price')
                    ->label('টাকা')
                    ->numeric(),

                Forms\Components\Toggle::make('status')
                    ->label('স্ট্যাটাস')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('ব্যান্ডেল'),
                Tables\Columns\TextColumn::make('minutes')->label('মিনিট'),
                Tables\Columns\TextColumn::make('validity')->label('মেয়াদ'),
                Tables\Columns\TextColumn::make('price')->label('টাকা'),
                Tables\Columns\IconColumn::make('status')->boolean()->label('স্ট্যাটাস'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('স্ট্যাটাস')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
                Tables\Filters\TernaryFilter::make('status')->label('স্ট্যাটাস'),
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
            'index' => Pages\ListMinuteBundles::route('/'),
            'create' => Pages\CreateMinuteBundle::route('/create'),
            'edit' => Pages\EditMinuteBundle::route('/{record}/edit'),
        ];
    }
}
