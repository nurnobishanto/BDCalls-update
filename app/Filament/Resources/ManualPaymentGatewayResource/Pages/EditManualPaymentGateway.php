<?php

namespace App\Filament\Resources\ManualPaymentGatewayResource\Pages;

use App\Filament\Resources\ManualPaymentGatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManualPaymentGateway extends EditRecord
{
    protected static string $resource = ManualPaymentGatewayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
