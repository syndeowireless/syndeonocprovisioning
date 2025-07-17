<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'categoria',
        'prioridade',
        'status',
        'user_id',
        'resposta_admin',
        'respondido_por',
        'respondido_em',
    ];

    protected $casts = [
        'respondido_em' => 'datetime',
    ];

    /**
     * Relacionamento com o usuário que criou o formulário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o admin que respondeu
     */
    public function respondidoPor()
    {
        return $this->belongsTo(User::class, 'respondido_por');
    }

    /**
     * Scope para filtrar por status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para filtrar por categoria
     */
    public function scopeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Accessor para formatar o status
     */
    public function getStatusFormatadoAttribute()
    {
        $statuses = [
            'pendente' => 'Pendente',
            'em_andamento' => 'Em Andamento',
            'resolvido' => 'Resolvido',
            'rejeitado' => 'Rejeitado',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Accessor para formatar a prioridade
     */
    public function getPrioridadeFormatadaAttribute()
    {
        $prioridades = [
            'baixa' => 'Baixa',
            'media' => 'Média',
            'alta' => 'Alta',
        ];

        return $prioridades[$this->prioridade] ?? $this->prioridade;
    }

    /**
     * Accessor para formatar a categoria
     */
    public function getCategoriaFormatadaAttribute()
    {
        $categorias = [
            'geral' => 'Geral',
            'suporte' => 'Suporte',
            'sugestao' => 'Sugestão',
            'reclamacao' => 'Reclamação',
        ];

        return $categorias[$this->categoria] ?? $this->categoria;
    }
}

