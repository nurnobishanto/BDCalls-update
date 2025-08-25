<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;

class PaidDueBills extends Page
{
    protected static ?string $navigationLabel = 'Paid Due Bills';
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Billing';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.paid-due-bills';

    use InteractsWithTable;
    protected function getTableQuery()
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
