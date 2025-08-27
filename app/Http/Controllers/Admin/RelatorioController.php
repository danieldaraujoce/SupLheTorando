<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cupom;
use App\Models\UsuarioRecompensa;
use Illuminate\Http\Request;
use League\Csv\Writer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function index()
    {
        // Uma view simples com botões para gerar cada relatório
        return view('admin.relatorios.index');
    }

    public function relatorioUsuarios(Request $request)
    {
        $usuarios = User::where('nivel_acesso', 'cliente')->with('nivelAtual')->get();
        $formato = $request->query('formato', 'pdf'); // pdf ou csv

        if ($formato == 'csv') {
            $csv = Writer::createFromString('');
            $csv->insertOne(['ID', 'Nome', 'Email', 'Moedas', 'Nível']);

            foreach ($usuarios as $usuario) {
                $csv->insertOne([
                    $usuario->id,
                    $usuario->nome,
                    $usuario->email,
                    $usuario->coins,
                    $usuario->nivelAtual->nome ?? 'N/D'
                ]);
            }

            return response((string) $csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="relatorio_usuarios.csv"',
            ]);
        }

        // Se não for CSV, será PDF
        $pdf = Pdf::loadView('admin.relatorios.templates.usuarios', compact('usuarios'));
        return $pdf->download('relatorio_usuarios.pdf');
    }
    
    public function indexCupons()
    {
        $dados['titulo_pagina'] = 'Relatório de Cupons';

        // 1. Cupons mais resgatados
        $dados['cuponsMaisResgatados'] = Cupom::withCount('usuariosQueResgataram')
            ->orderBy('usuarios_que_resgataram_count', 'desc')
            ->limit(10)
            ->get();

        // 2. Total de resgates
        $dados['totalResgates'] = DB::table('usuario_cupons')->count();

        // 3. Últimos resgates
        $dados['ultimosResgates'] = DB::table('usuario_cupons')
            ->join('usuarios', 'usuario_cupons.user_id', '=', 'usuarios.id')
            ->join('cupons', 'usuario_cupons.cupom_id', '=', 'cupons.id')
            ->select('usuarios.nome as nome_usuario', 'cupons.codigo as codigo_cupom', 'usuario_cupons.created_at as data_resgate')
            ->latest('usuario_cupons.created_at')
            ->limit(10)
            ->get();
            
        return view('admin.relatorios.cupons', $dados);
    }
}