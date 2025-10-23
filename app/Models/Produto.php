<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Produto - Representa os produtos do sistema PDV
 * 
 * Relacionamentos:
 * - Um produto pode estar em muitos itens de venda (hasMany)
 */
class Produto extends Model
{
    use HasFactory;

    /**
     * Campos que podem ser preenchidos em massa (Mass Assignment)
     * Protege contra ataques de segurança
     */
    protected $fillable = [
        'nome',           // Nome do produto
        'descricao',      // Descrição detalhada
        'preco',          // Preço de venda
        'estoque',        // Quantidade em estoque
        'categoria',      // Categoria do produto
        'codigo_barras',  // Código de barras único
        'ativo'           // Se o produto está ativo
    ];

    /**
     * Conversões automáticas de tipos de dados
     * Garante consistência nos dados
     */
    protected $casts = [
        'preco' => 'decimal:2',    // Converte para decimal com 2 casas
        'ativo' => 'boolean'        // Converte para true/false
    ];

    /**
     * Relacionamento: Um produto pode estar em muitos itens de venda
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemVendas()
    {
        return $this->hasMany(ItemVenda::class);
    }

    /**
     * Scope para buscar apenas produtos ativos
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para buscar produtos por categoria
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $categoria
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Scope para buscar produtos com estoque baixo
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limite
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEstoqueBaixo($query, $limite = 10)
    {
        return $query->where('estoque', '<=', $limite);
    }
}
