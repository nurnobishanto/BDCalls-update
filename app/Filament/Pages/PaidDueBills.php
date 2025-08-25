<?php

namespace App\Filament\Pages;

use App\Models\DueBill;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

class PaidDueBills extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationLabel = 'Paid Due Bills';
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.paid-due-bills';

    // Query only paid due bills
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return DueBill::query()->where('payment_status', 'paid');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('user.name')->label('User')->sortable()->searchable(),
            TextColumn::make('userIpNumber.number')->label('IP Number')->sortable()->searchable(),
            TextColumn::make('call_rate'),
            TextColumn::make('minutes'),
            TextColumn::make('service_charge'),
            TextColumn::make('month'),
            TextColumn::make('total'),
            BadgeColumn::make('payment_status')
                ->colors([
                    'success' => 'paid',
                ]),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('user_id')
                ->label('User')
                ->relationship('user', 'name'),
            SelectFilter::make('user_ip_number_id')
                ->label('IP Number')
                ->relationship('userIpNumber', 'number'),
        ];
    }
}
