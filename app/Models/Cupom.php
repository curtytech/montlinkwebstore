<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cupom extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cupons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'codigo',
        'tipo',
        'valor',
        'valor_minimo',
        'quantidade',
        'quantidade_usada',
        'data_inicio',
        'data_fim',
        'ativo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'valor' => 'decimal:2',
        'valor_minimo' => 'decimal:2',
        'quantidade' => 'integer',
        'quantidade_usada' => 'integer',
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'ativo' => 'boolean',
    ];

    /**
     * Verifica se o cupom está válido
     */
    public function isValid(): bool
    {
        $now = Carbon::now();
        
        // Verifica se está ativo
        if (!$this->ativo) {
            return false;
        }
        
        // Verifica se está dentro do período
        if ($now->lt($this->data_inicio) || $now->gt($this->data_fim)) {
            return false;
        }
        
        // Verifica se ainda tem quantidade disponível
        if ($this->quantidade && $this->quantidade_usada >= $this->quantidade) {
            return false;
        }
        
        return true;
    }

    /**
     * Calcula o desconto baseado no subtotal
     */
    public function calcularDesconto(float $subtotal): float
    {
        // Verifica valor mínimo
        if ($this->valor_minimo && $subtotal < $this->valor_minimo) {
            return 0;
        }
        
        if ($this->tipo === 'percentual') {
            return ($subtotal * $this->valor) / 100;
        }
        
        return $this->valor;
    }

    /**
     * Incrementa a quantidade usada
     */
    public function incrementarUso(): void
    {
        $this->increment('quantidade_usada');
    }

    /**
     * Scope para cupons ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para cupons válidos
     */
    public function scopeValidos($query)
    {
        $now = Carbon::now();
        return $query->where('ativo', true)
                    ->where('data_inicio', '<=', $now)
                    ->where('data_fim', '>=', $now)
                    ->where(function($q) {
                        $q->whereNull('quantidade')
                          ->orWhereRaw('quantidade_usada < quantidade');
                    });
    }
}