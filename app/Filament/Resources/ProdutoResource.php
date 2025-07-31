<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdutoResource\Pages;
use App\Models\Produto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Produtos';
    
    protected static ?string $modelLabel = 'Produto';
    
    protected static ?string $pluralModelLabel = 'Produtos';
    
    // Adicionar esta linha para agrupar na navegação
    protected static ?string $navigationGroup = 'Vendas';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Produto')
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('sku')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('preco')
                            ->required()
                            ->numeric()
                            ->prefix('R$'),
                            
                        Forms\Components\Textarea::make('descricao')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                            
                        Forms\Components\FileUpload::make('imagem')
                            ->image()
                            ->directory('produtos')
                            ->columnSpanFull(),
                            
                        Forms\Components\Toggle::make('ativo')
                            ->default(true),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Estoque')
                    ->schema([
                        Forms\Components\TextInput::make('estoque.quantidade')
                            ->numeric()
                            ->default(0)
                            ->required(),
                            
                        Forms\Components\TextInput::make('estoque.quantidade_minima')
                            ->numeric()
                            ->default(5),
                            
                        Forms\Components\TextInput::make('estoque.localizacao')
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('sku')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('preco')
                    ->money('BRL')
                    ->sortable(),
                    
                Tables\Columns\ImageColumn::make('imagem'),
                
                Tables\Columns\TextColumn::make('estoque.quantidade')
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('ativo')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ativo')
                    ->options([
                        '1' => 'Ativo',
                        '0' => 'Inativo',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProdutos::route('/'),
            'create' => Pages\CreateProduto::route('/create'),
            'edit' => Pages\EditProduto::route('/{record}/edit'),
        ];
    }
}