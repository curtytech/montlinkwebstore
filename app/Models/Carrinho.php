<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Carrinho extends Model
{
    use HasFactory;

    protected $table = 'carrinho';

    protected $fillable = [
        'session_id',
        'user_id',
        'produto_id',
        'quantidade',
        'preco_unitario',
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'preco_unitario' => 'decimal:2',
    ];

    /**
     * Get the produto associated with the carrinho.
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    /**
     * Get the user associated with the carrinho.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate subtotal for this item.
     */
    public function getSubtotalAttribute()
    {
        return $this->quantidade * $this->preco_unitario;
    }
}