<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Filament\Resources\ProdutoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduto extends CreateRecord
{
    protected static string $resource = ProdutoResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $estoqueData = $data['estoque'] ?? [];
        unset($data['estoque']);
        
        return $data;
    }
    
    protected function afterCreate(): void
    {
        $produto = $this->record;
        
        // Criar o estoque relacionado
        if (isset($this->data['estoque'])) {
            $produto->estoque()->create($this->data['estoque']);
        }
    }
}