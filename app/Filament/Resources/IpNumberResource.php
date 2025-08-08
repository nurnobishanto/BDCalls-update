<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IpNumberResource\Pages;
use App\Filament\Resources\IpNumberResource\RelationManagers;
use App\Models\IpNumber;
use App\Services\IpNumberService;
use Filament\Actions\RestoreAction;
use Filament\Forms;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Artisan;

class IpNumberResource extends Resource
{
    protected static ?string $model = IpNumber::class;

    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('number')
                    ->label('IP Number')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->required()
                    ->default(0),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Available',
                        'unavailable' => 'Unavailable',
                    ])
                    ->required()
                    ->default('available'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->label('IP Number')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price')->label('Price')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => match ($record->status) {
                        'available' => 'Available',
                        'unavailable' => 'Unavailable',
                        default => $record->status,
                    })
                    ->colors([
                        'success' => 'available',
                        'danger' => 'unavailable',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Available',
                        'unavailable' => 'Unavailable',
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
            'index' => Pages\ListIpNumbers::route('/'),
            'create' => Pages\CreateIpNumber::route('/create'),
            'edit' => Pages\EditIpNumber::route('/{record}/edit'),
        ];
    }
}
