<?php

namespace App\Filament\User\Resources\UserIpNumberResource\Pages;

use App\Filament\User\Resources\UserIpNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserIpNumbers extends ListRecords
{
    protected static string $resource = UserIpNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\CreateAction::make(),
        ];
    }
}
