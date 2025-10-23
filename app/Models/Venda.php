<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Venda - Representa as vendas do sistema PDV
 * 
 * Relacionamentos:
 * - Uma venda pertence a um cliente (belongsTo)
 * - Uma venda pertence a um usuário (belongsTo)
 * - Uma venda pode ter muitos itens (hasMany)
 */
class Venda extends Model
{
    use HasFactory;

    /**
     * Campos que podem ser preenchidos em massa (Mass Assignment)
     * Protege contra ataques de segurança
     */
    protected $fillable = [
        'cliente_id',        // ID do cliente que fez a compra
        'user_id',           // ID do usuário que processou a venda
        'data_venda',        // Data em que a venda foi realizada
        'total',             // Valor total da venda
        'desconto',          // Valor do desconto aplicado
        'forma_pagamento',   // Forma de pagamento (dinheiro, cartão, etc.)
        'status',            // Status da venda (pendente, concluida, cancelada)
        'observacoes'        // Observações adicionais
    ];

    /**
     * Conversões automáticas de tipos de dados
     * Garante consistência nos dados
     */
    protected $casts = [
        'data_venda' => 'date',      // Converte para objeto Carbon (data)
        'total' => 'decimal:2',       // Converte para decimal com 2 casas
        'desconto' => 'decimal:2'     // Converte para decimal com 2 casas
    ];

    /**
     * Relacionamento: Uma venda pertence a um cliente
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relacionamento: Uma venda pertence a um usuário
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Uma venda pode ter muitos itens
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemVendas()
    {
        return $this->hasMany(ItemVenda::class);
    }

    /**
     * Scope para buscar apenas vendas concluídas
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConcluidas($query)
    {
        return $query->where('status', 'concluida');
    }

    /**
     * Scope para buscar vendas por período
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dataInicio
     * @param string $dataFim
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorPeriodo($query, $dataInicio, $dataFim)
    {
        return $query->whereBetween('data_venda', [$dataInicio, $dataFim]);
    }

    /**
     * Scope para buscar vendas de hoje
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHoje($query)
    {
        return $query->whereDate('data_venda', today());
    }

    /**
     * Accessor: Calcula o total líquido (total - desconto)
     * 
     * @return float
     */
    public function getTotalLiquidoAttribute()
    {
        return $this->total - $this->desconto;
    }
}
