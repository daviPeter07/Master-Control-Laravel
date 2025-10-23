<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Cliente - Representa os clientes do sistema PDV
 * 
 * Relacionamentos:
 * - Um cliente pode ter muitas vendas (hasMany)
 */
class Cliente extends Model
{
    use HasFactory;

    /**
     * Campos que podem ser preenchidos em massa (Mass Assignment)
     * Protege contra ataques de segurança
     */
    protected $fillable = [
        'nome',           // Nome completo do cliente
        'email',          // Email único para contato
        'telefone',       // Telefone de contato
        'cpf_cnpj',       // Documento único (CPF ou CNPJ)
        'endereco',       // Endereço completo
        'cidade',         // Cidade onde mora
        'estado',         // Estado (sigla de 2 letras)
        'cep'             // Código postal
    ];

    /**
     * Relacionamento: Um cliente pode ter muitas vendas
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendas() 
    {
        return $this->hasMany(Venda::class);
    }

    /**
     * Scope para buscar clientes por nome
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $nome
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorNome($query, $nome)
    {
        return $query->where('nome', 'like', "%{$nome}%");
    }
}
