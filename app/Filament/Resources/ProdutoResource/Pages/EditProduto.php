<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Filament\Resources\ProdutoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduto extends EditRecord
{
    protected static string $resource = ProdutoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $estoqueData = $data['estoque'] ?? [];
        unset($data['estoque']);
        
        return $data;
    }
    
    protected function afterSave(): void
    {
        $produto = $this->record;
        
        // Atualizar o estoque relacionado
        if (isset($this->data['estoque'])) {
            if ($produto->estoque) {
                $produto->estoque->update($this->data['estoque']);
            } else {
                $produto->estoque()->create($this->data['estoque']);
            }
        }
    }
}