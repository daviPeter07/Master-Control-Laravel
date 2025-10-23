<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ItemVenda - Representa os itens individuais de uma venda
 * 
 * Relacionamentos:
 * - Um item pertence a uma venda (belongsTo)
 * - Um item pertence a um produto (belongsTo)
 */
class ItemVenda extends Model
{
    use HasFactory;

    /**
     * Campos que podem ser preenchidos em massa (Mass Assignment)
     * Protege contra ataques de segurança
     */
    protected $fillable = [
        'venda_id',         // ID da venda a que este item pertence
        'produto_id',       // ID do produto vendido
        'quantidade',       // Quantidade vendida
        'preco_unitario',   // Preço unitário na hora da venda
        'subtotal'          // Subtotal (quantidade × preço_unitario)
    ];

    /**
     * Conversões automáticas de tipos de dados
     * Garante consistência nos dados
     */
    protected $casts = [
        'preco_unitario' => 'decimal:2',    // Converte para decimal com 2 casas
        'subtotal' => 'decimal:2'           // Converte para decimal com 2 casas
    ];

    /**
     * Relacionamento: Um item pertence a uma venda
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    /**
     * Relacionamento: Um item pertence a um produto
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    /**
     * Boot method - Executado quando o model é inicializado
     * Calcula automaticamente o subtotal antes de salvar
     */
    protected static function boot()
    {
        parent::boot();

        // Calcula o subtotal automaticamente antes de salvar
        static::saving(function ($item) {
            $item->subtotal = $item->quantidade * $item->preco_unitario;
        });
    }

    /**
     * Accessor: Retorna o nome do produto
     * 
     * @return string
     */
    public function getNomeProdutoAttribute()
    {
        return $this->produto ? $this->produto->nome : 'Produto não encontrado';
    }
}
