<?php

namespace App\Filament\Resources\CupomResource\Pages;

use App\Filament\Resources\CupomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCupons extends ListRecords
{
    protected static string $resource = CupomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo Cupom'),
        ];
    }
}