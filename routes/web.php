<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLERS ---

// Controllers da Home e Autenticação
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

// Controllers da Área do Cliente (Mobile-first)
use App\Http\Controllers\Cliente\CaixaSurpresaController as ClienteCaixaSurpresaController;
use App\Http\Controllers\Cliente\CartController;
use App\Http\Controllers\Cliente\CashbackController as ClienteCashbackController;
use App\Http\Controllers\Cliente\CupomController as ClienteCupomController;
use App\Http\Controllers\Cliente\DashboardController as ClienteDashboardController;
use App\Http\Controllers\Cliente\HistoricoController;
use App\Http\Controllers\Cliente\MissaoController as ClienteMissaoController;
use App\Http\Controllers\Cliente\ProdutoController as ClienteProdutoController;
use App\Http\Controllers\Cliente\PromocaoController as ClientePromocaoController;
use App\Http\Controllers\Cliente\QuizController as ClienteQuizController;
use App\Http\Controllers\Cliente\RecompensaController as ClienteRecompensaController;
use App\Http\Controllers\Cliente\RoletaController as ClienteRoletaController;
use App\Http\Controllers\Cliente\ScannerController;


// Controllers da Área do Administrador (Desktop Responsivo)
use App\Http\Controllers\Admin\CaixaSurpresaController;
use App\Http\Controllers\Admin\CaixaSurpresaItemController;
use App\Http\Controllers\Admin\CalculadoraController;
use App\Http\Controllers\Admin\CashbackController;
use App\Http\Controllers\Admin\CategoriaProdutoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\ConfiguracaoController;
use App\Http\Controllers\Admin\CupomController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DestaqueHomeController;
use App\Http\Controllers\Admin\EncarteController;
use App\Http\Controllers\Admin\GuiaDicaController;
use App\Http\Controllers\Admin\JogoController;
use App\Http\Controllers\Admin\MissaoController as AdminMissaoController;
use App\Http\Controllers\Admin\NivelController;
use App\Http\Controllers\Admin\OfertaMesController;
use App\Http\Controllers\Admin\OfertaVipController;
use App\Http\Controllers\Admin\PerguntaController;
use App\Http\Controllers\Admin\ProdutoController;
use App\Http\Controllers\Admin\PromocaoController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\QuizPerguntaController;
use App\Http\Controllers\Admin\RecompensaController as AdminRecompensaController;
use App\Http\Controllers\Admin\RelatorioController;
use App\Http\Controllers\Admin\RoletaController;
use App\Http\Controllers\Admin\RoletaItemController;
use App\Http\Controllers\Admin\UsuarioController as AdminUsuarioController;


/*
|--------------------------------------------------------------------------
| ROTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| ROTAS DO CLIENTE AUTENTICADO
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->nivel_acesso == 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return (new ClienteDashboardController())->index();
    })->name('dashboard');

    // --- CORREÇÃO APLICADA AQUI ---
    // A rota GET para /profile agora aponta apenas para o método 'edit'.
    // A rota duplicada que apontava para o método 'show' foi removida para eliminar o conflito.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Funcionalidades do Cliente ---
    Route::get('/missoes', [ClienteMissaoController::class, 'index'])->name('cliente.missoes.index');
    Route::post('/missoes/{missao}/aceitar', [ClienteMissaoController::class, 'aceitar'])->name('cliente.missoes.aceitar');
    Route::get('/missoes/{missao}/comprovar', [ClienteMissaoController::class, 'showComprovar'])->name('cliente.missoes.comprovar');
    Route::post('/missoes/{missao}/comprovar', [ClienteMissaoController::class, 'storeComprovante'])->name('cliente.missoes.storeComprovante');

    Route::get('/quizzes', [ClienteQuizController::class, 'index'])->name('cliente.quizzes.index');
    Route::get('/quizzes/{quiz}', [ClienteQuizController::class, 'show'])->name('cliente.quizzes.show');
    Route::post('/quizzes/perguntas/{pergunta}/responder', [ClienteQuizController::class, 'responder'])->name('cliente.quizzes.responder');

    Route::get('/recompensas', [ClienteRecompensaController::class, 'index'])->name('cliente.recompensas.index');
    Route::get('/recompensas/resgatadas', [ClienteRecompensaController::class, 'resgatadas'])->name('cliente.recompensas.resgatadas');
    Route::post('/recompensas/{recompensa}/resgatar', [ClienteRecompensaController::class, 'resgatar'])->name('cliente.recompensas.resgatar');

    Route::post('/cupons/{cupom}/resgatar', [ClienteCupomController::class, 'resgatar'])->name('cliente.cupons.resgatar');
    Route::post('/cashback/{cashback}/resgatar', [ClienteCashbackController::class, 'resgatar'])->name('cliente.cashback.resgatar');

    // --- Rotas do Carrinho de Compras ---
    Route::get('/carrinho', [CartController::class, 'index'])->name('cliente.carrinho.index');
    Route::post('/carrinho/adicionar', [CartController::class, 'adicionar'])->name('cliente.carrinho.adicionar');
    Route::post('/carrinho/adicionar-combo/{promocao}', [CartController::class, 'adicionarCombo'])->name('cliente.carrinho.adicionarCombo');
    Route::put('/carrinho/item/{item}/update', [CartController::class, 'updateItem'])->name('cliente.carrinho.updateItem');
    Route::delete('/carrinho/item/{item}/remove', [CartController::class, 'removerItem'])->name('cliente.carrinho.removerItem');
    Route::post('/carrinho/finalizar', [CartController::class, 'finalizarCompra'])->name('cliente.carrinho.finalizar');
    Route::post('/carrinho/adicionar-por-codigo-barras', [CartController::class, 'adicionarPorCodigoBarras'])->name('cliente.carrinho.adicionarPorCodigoBarras');

    // --- Rotas de Scanner e Produtos ---
    Route::get('/historico', [HistoricoController::class, 'index'])->name('cliente.historico.index');
    Route::get('/historico/{pedido}', [HistoricoController::class, 'show'])->name('cliente.historico.show');
    Route::get('/scanner', [ScannerController::class, 'index'])->name('cliente.scanner.index');
    Route::get('/promocoes/{promocao}', [ClientePromocaoController::class, 'show'])->name('cliente.promocoes.show');
    Route::get('/produtos', [ClienteProdutoController::class, 'index'])->name('cliente.produtos.index');
    Route::get('/api/produto/buscar/{codigo_barras}', [ClienteProdutoController::class, 'buscarPorCodigoBarras'])->name('api.produto.buscar');
    Route::get('/ranking', [App\Http\Controllers\Cliente\RankingController::class, 'index'])->name('cliente.ranking.index');


    // --- Rotas de Jogos (Roleta e Caixas Surpresa) ---
    Route::prefix('roletas')->name('cliente.roletas.')->group(function () {
        Route::get('/', [ClienteRoletaController::class, 'index'])->name('index');
        Route::get('/{roleta}', [ClienteRoletaController::class, 'show'])->name('show');
        Route::post('/{roleta}/girar', [ClienteRoletaController::class, 'girar'])->name('girar');
        Route::post('/comprar-giro', [ClienteRoletaController::class, 'comprarGiro'])->name('comprar-giro');
    });
    Route::prefix('caixas-surpresa')->name('cliente.caixas-surpresa.')->group(function () {
        Route::get('/', [ClienteCaixaSurpresaController::class, 'index'])->name('index');
        Route::get('/{caixaSurpresa}', [ClienteCaixaSurpresaController::class, 'show'])->name('show');
        Route::post('/{caixaSurpresa}/abrir', [ClienteCaixaSurpresaController::class, 'abrir'])->name('abrir');
    });
});

/*
|--------------------------------------------------------------------------
| ROTAS DO PAINEL ADMINISTRATIVO
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // --- Rotas de Recursos (CRUDs) ---
    Route::resource('clientes', ClienteController::class);
    Route::resource('cashbacks', CashbackController::class);
    Route::resource('categorias-produtos', CategoriaProdutoController::class);
    Route::resource('cupons', CupomController::class)->parameters(['cupons' => 'cupom']);
    Route::resource('encartes', EncarteController::class);
    Route::resource('guias-dicas', GuiaDicaController::class);
    Route::resource('missoes', AdminMissaoController::class)->except(['show'])->parameters(['missoes' => 'missao']);
    Route::resource('niveis', NivelController::class);
    Route::resource('ofertas-mes', OfertaMesController::class)->parameters(['ofertas-mes' => 'oferta']);
    Route::resource('ofertas-vip', OfertaVipController::class);
    Route::resource('produtos', ProdutoController::class);
    Route::resource('promocoes', PromocaoController::class)->parameters(['promocoes' => 'promocao']);
    Route::resource('quizzes', AdminQuizController::class);
    Route::resource('recompensas', AdminRecompensaController::class)->except(['show']);
    Route::resource('caixas-surpresa', CaixaSurpresaController::class);
    Route::resource('roletas', RoletaController::class);
    Route::resource('usuarios', AdminUsuarioController::class);
    Route::resource('destaques-home', DestaqueHomeController::class)->parameters(['destaques-home' => 'destaque']);
    Route::resource('quizzes.perguntas', QuizPerguntaController::class)->shallow();

    // --- Outras Rotas de Admin ---
    Route::get('produtos/cadastro-rapido', [ProdutoController::class, 'cadastroRapido'])->name('produtos.cadastro-rapido');
    Route::post('/produtos/gerar-descricao', [ProdutoController::class, 'gerarDescricaoIA'])->name('produtos.gerar-descricao-ia');

    Route::prefix('recompensas')->name('recompensas.')->group(function () {
        Route::get('pendentes', [AdminRecompensaController::class, 'indexPendentes'])->name('pendentes');
        Route::get('resgates', [AdminRecompensaController::class, 'codigosDeResgate'])->name('resgates.index');
        Route::post('resgates/{resgate}/aprovar', [AdminRecompensaController::class, 'aprovarResgate'])->name('resgates.aprovar');
        Route::post('resgates/{resgate}/rejeitar', [AdminRecompensaController::class, 'rejeitarResgate'])->name('resgates.rejeitar');
    });

    Route::get('/missoes/pendentes', [AdminMissaoController::class, 'indexPendentes'])->name('missoes.pendentes');
    Route::post('/missoes/submissoes/{submissao}/aprovar', [AdminMissaoController::class, 'aprovar'])->name('missoes.aprovar');
    Route::post('/missoes/submissoes/{submissao}/rejeitar', [AdminMissaoController::class, 'rejeitar'])->name('missoes.rejeitar');
    
    Route::get('/quizzes/{quiz}/perguntas/create', [PerguntaController::class, 'create'])->name('perguntas.create');

    Route::post('caixas-surpresa/{caixaSurpresa}/itens', [CaixaSurpresaItemController::class, 'store'])->name('caixas-surpresa.itens.store');
    Route::put('caixas-surpresa/itens/{item}', [CaixaSurpresaItemController::class, 'update'])->name('caixas-surpresa.itens.update');
    Route::delete('caixas-surpresa/itens/{item}', [CaixaSurpresaItemController::class, 'destroy'])->name('caixas-surpresa.itens.destroy');

    Route::post('roletas/{roleta}/itens', [RoletaItemController::class, 'store'])->name('roletas.itens.store');
    Route::get('roletas/itens/{item}/edit', [RoletaItemController::class, 'edit'])->name('roletas.itens.edit');
    Route::put('roletas/itens/{item}', [RoletaItemController::class, 'update'])->name('roletas.itens.update');
    Route::delete('roletas/itens/{item}', [RoletaItemController::class, 'destroy'])->name('roletas.itens.destroy');

    Route::get('jogos', [JogoController::class, 'index'])->name('jogos.index');
    Route::get('jogos/{jogo}/edit', [JogoController::class, 'edit'])->name('jogos.edit');
    Route::put('jogos/{jogo}', [JogoController::class, 'update'])->name('jogos.update');
    Route::post('jogos/{jogo}/premios', [JogoController::class, 'storePremio'])->name('jogos.premios.store');
    Route::delete('jogos/premios/{premio}', [JogoController::class, 'destroyPremio'])->name('jogos.premios.destroy');

    Route::get('configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');
    Route::post('configuracoes', [ConfiguracaoController::class, 'store'])->name('configuracoes.store');

    Route::get('calculadora', [CalculadoraController::class, 'index'])->name('calculadora.index');

    Route::get('relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('relatorios/usuarios', [RelatorioController::class, 'relatorioUsuarios'])->name('relatorios.usuarios');
    Route::get('relatorios/recompensas', [RelatorioController::class, 'relatorioRecompensas'])->name('relatorios.recompensas');
    Route::get('/relatorios/templates/cupons', [RelatorioController::class, 'indexCupons'])->name('relatorios.cupons');

    
});

Route::middleware('auth')->prefix('carrinho')->name('cliente.carrinho.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    // Adicionar rotas para update e remove aqui...
    Route::post('/finalize', [CartController::class, 'finalize'])->name('finalize');
    
    // Nova rota para a página que mostra o QR Code
    Route::get('/checkout/{uuid}', function ($uuid) {
        return view('cliente.carrinho.checkout', ['uuid' => $uuid]);
    })->name('checkout');
});

require __DIR__.'/auth.php';