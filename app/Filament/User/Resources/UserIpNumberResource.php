<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\UserIpNumberResource\Pages;
use App\Filament\User\Resources\UserIpNumberResource\RelationManagers;
use App\Models\UserIpNumber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserIpNumberResource extends Resource
{
    protected static ?string $model = UserIpNumber::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationLabel = 'Ip Numbers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
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
                Tables\Filters\Filter::make('status')
                    ->label('Status')
                    ->query(fn (Builder $query) => $query->where('status', true)),
            ])

            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
