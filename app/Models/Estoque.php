<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estoque extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estoque';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produto_id',
        'quantidade',
        'quantidade_minima',
        'localizacao',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantidade' => 'integer',
        'quantidade_minima' => 'integer',
    ];

    /**
     * Get the produto that owns the estoque.
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}