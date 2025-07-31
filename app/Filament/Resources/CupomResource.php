<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CupomResource\Pages;
use App\Models\Cupom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Carbon\Carbon;

class CupomResource extends Resource
{
    protected static ?string $model = Cupom::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    
    protected static ?string $navigationLabel = 'Cupons';
    
    protected static ?string $modelLabel = 'Cupom';
    
    protected static ?string $pluralModelLabel = 'Cupons';
    
    protected static ?string $navigationGroup = 'Vendas';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações do Cupom')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('codigo')
                                    ->label('Código do Cupom')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('Ex: DESCONTO10')
                                    ->helperText('Código único que o cliente irá digitar'),
                                    
                                Forms\Components\Select::make('tipo')
                                    ->label('Tipo de Desconto')
                                    ->required()
                                    ->options([
                                        'percentual' => 'Percentual (%)',
                                        'valor_fixo' => 'Valor Fixo (R$)',
                                    ])
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('valor', null)),
                            ]),
                            
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('valor')
                                    ->label(fn (callable $get) => $get('tipo') === 'percentual' ? 'Percentual de Desconto (%)' : 'Valor do Desconto (R$)')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(fn (callable $get) => $get('tipo') === 'percentual' ? 100 : null)
                                    ->step(0.01)
                                    ->prefix(fn (callable $get) => $get('tipo') === 'valor_fixo' ? 'R$' : null)
                                    ->suffix(fn (callable $get) => $get('tipo') === 'percentual' ? '%' : null),
                                    
                                Forms\Components\TextInput::make('valor_minimo')
                                    ->label('Valor Mínimo do Pedido (R$)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->prefix('R$')
                                    ->helperText('Valor mínimo do carrinho para usar o cupom (opcional)'),
                            ]),
                    ]),
                    
                Section::make('Limitações e Período')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('quantidade')
                                    ->label('Quantidade de Usos')
                                    ->numeric()
                                    ->minValue(1)
                                    ->helperText('Deixe vazio para uso ilimitado'),
                                    
                                Forms\Components\TextInput::make('quantidade_usada')
                                    ->label('Quantidade Já Usada')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                            
                        Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('data_inicio')
                                    ->label('Data de Início')
                                    ->required()
                                    ->default(now())
                                    ->displayFormat('d/m/Y H:i')
                                    ->helperText('Data e hora que o cupom ficará ativo'),
                                    
                                Forms\Components\DateTimePicker::make('data_fim')
                                    ->label('Data de Fim')
                                    ->required()
                                    ->after('data_inicio')
                                    ->displayFormat('d/m/Y H:i')
                                    ->helperText('Data e hora que o cupom expirará'),
                            ]),
                            
                        Forms\Components\Toggle::make('ativo')
                            ->label('Cupom Ativo')
                            ->default(true)
                            ->helperText('Desative para pausar temporariamente o cupom'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Código copiado!')
                    ->weight('bold'),
                    
                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->colors([
                        'success' => 'percentual',
                        'primary' => 'valor_fixo',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'percentual' => 'Percentual',
                        'valor_fixo' => 'Valor Fixo',
                    }),
                    
                Tables\Columns\TextColumn::make('valor')
                    ->label('Desconto')
                    ->formatStateUsing(function ($record) {
                        if ($record->tipo === 'percentual') {
                            return $record->valor . '%';
                        }
                        return 'R$ ' . number_format($record->valor, 2, ',', '.');
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('valor_minimo')
                    ->label('Valor Mín.')
                    ->money('BRL')
                    ->placeholder('Sem mínimo')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('uso')
                    ->label('Uso')
                    ->formatStateUsing(function ($record) {
                        if ($record->quantidade) {
                            return $record->quantidade_usada . '/' . $record->quantidade;
                        }
                        return $record->quantidade_usada . '/∞';
                    })
                    ->badge()
                    ->color(function ($record) {
                        if (!$record->quantidade) return 'gray';
                        $percentage = ($record->quantidade_usada / $record->quantidade) * 100;
                        if ($percentage >= 100) return 'danger';
                        if ($percentage >= 80) return 'warning';
                        return 'success';
                    }),
                    
                Tables\Columns\TextColumn::make('data_inicio')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('data_fim')
                    ->label('Fim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        $now = Carbon::now();
                        
                        if (!$record->ativo) {
                            return 'Inativo';
                        }
                        
                        if ($now->lt($record->data_inicio)) {
                            return 'Agendado';
                        }
                        
                        if ($now->gt($record->data_fim)) {
                            return 'Expirado';
                        }
                        
                        if ($record->quantidade && $record->quantidade_usada >= $record->quantidade) {
                            return 'Esgotado';
                        }
                        
                        return 'Ativo';
                    })
                    ->colors([
                        'success' => 'Ativo',
                        'warning' => 'Agendado',
                        'danger' => ['Expirado', 'Esgotado', 'Inativo'],
                    ]),
                    
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'percentual' => 'Percentual',
                        'valor_fixo' => 'Valor Fixo',
                    ]),
                    
                SelectFilter::make('ativo')
                    ->label('Status')
                    ->options([
                        '1' => 'Ativo',
                        '0' => 'Inativo',
                    ]),
                    
                Filter::make('validos')
                    ->label('Apenas Válidos')
                    ->query(fn (Builder $query): Builder => $query->validos()),
                    
                Filter::make('expirados')
                    ->label('Expirados')
                    ->query(fn (Builder $query): Builder => $query->where('data_fim', '<', now())),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListCupons::route('/'),
            'create' => Pages\CreateCupom::route('/create'),
            'edit' => Pages\EditCupom::route('/{record}/edit'),
        ];
    }
}