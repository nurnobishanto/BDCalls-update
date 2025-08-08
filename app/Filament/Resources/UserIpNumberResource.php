<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserIpNumberResource\Pages;
use App\Filament\Resources\UserIpNumberResource\RelationManagers;
use App\Models\UserIpNumber;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserIpNumberResource extends Resource
{
    protected static ?string $model = UserIpNumber::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Settings';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Select::make('package_id')
                    ->label('Package')
                    ->relationship('package', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('number')
                    ->label('IP Number')
                    ->numeric()
                    ->required(),

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
                Tables\Filters\Filter::make('status')
                    ->label('Status')
                    ->query(fn (Builder $query) => $query->where('status', true)),
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
            'index' => Pages\ListUserIpNumbers::route('/'),
            'create' => Pages\CreateUserIpNumber::route('/create'),
            'edit' => Pages\EditUserIpNumber::route('/{record}/edit'),
        ];
    }
}
