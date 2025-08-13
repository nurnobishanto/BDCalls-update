<?php

namespace App\Filament\Resources\DueBillResource\Pages;

use App\Filament\Resources\DueBillResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDueBills extends ListRecords
{
    protected static string $resource = DueBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
