<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formulario;

class FormularioController extends Controller
{
    /**
     * Exibe a lista de formulários (apenas para admins)
     */
    public function index()
    {
        $formularios = Formulario::with(\'user\')->latest()->paginate(10);
        return view(\'formularios.index\', compact(\'formularios\'));
    }

    /**
     * Exibe o formulário de criação
     */
    public function create()
    {
        return view(\'formularios.create\');
    }

    /**
     * Armazena um novo formulário
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            \'titulo\' => \'required|string|max:255\',
            \'descricao\' => \'required|string\',
            \'categoria\' => \'required|string|in:geral,suporte,sugestao,reclamacao\',
            \'prioridade\' => \'required|string|in:baixa,media,alta\',
        ]);

        $validatedData[\'user_id\'] = auth()->id();
        $validatedData[\'status\'] = \'pendente\';

        Formulario::create($validatedData);

        return redirect()->route(\'formularios.create\')
            ->with(\'success\', \'Formulário enviado com sucesso!\');
    }

    /**
     * Exibe um formulário específico
     */
    public function show(Formulario $formulario)
    {
        // Usuários comuns só podem ver seus próprios formulários
        if (!auth()->user()->isAdmin() && $formulario->user_id !== auth()->id()) {
            abort(403, \'Você não tem permissão para visualizar este formulário.\');
        }

        return view(\'formularios.show\', compact(\'formulario\'));
    }

    /**
     * Exibe os formulários do usuário logado
     */
    public function meus()
    {
        $formularios = Formulario::where(\'user_id\', auth()->id())
            ->latest()
            ->paginate(10);
        
        return view(\'formularios.meus\', compact(\'formularios\'));
    }

    /**
     * Atualiza o status do formulário (apenas admins)
     */
    public function updateStatus(Request $request, Formulario $formulario)
    {
        $request->validate([
            \'status\' => \'required|string|in:pendente,em_andamento,resolvido,rejeitado\',
            \'resposta_admin\' => \'nullable|string\',
        ]);

        $formulario->update([
            \'status\' => $request->status,
            \'resposta_admin\' => $request->resposta_admin,
            \'respondido_por\' => auth()->id(),
            \'respondido_em\' => now(),
        ]);

        return redirect()->route(\'formularios.show\', $formulario)
            ->with(\'success\', \'Status atualizado com sucesso!\');
    }
}

