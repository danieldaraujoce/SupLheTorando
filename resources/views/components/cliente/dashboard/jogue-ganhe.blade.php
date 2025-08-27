<section class="mb-8">
    <h2 class="text-xl font-bold text-black mb-4">🎉 Jogue e Ganhe</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        
        <a href="{{ route('cliente.quizzes.index') }}" class="bg-white rounded-2xl p-6 shadow-md flex items-center gap-4 transition-transform hover:scale-105">
            <div class="flex-shrink-0 h-14 w-14 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                <p style="font-size:30px">&#10067;</p>
            </div>
            <div>
                <h3 class="font-bold text-lg text-black">Quizzes</h3>
                <p class="text-sm text-gray-600">Responda e ganhe!</p>
            </div>
        </a>

        <a href="{{ route('cliente.roletas.index') }}" class="bg-white rounded-2xl p-6 shadow-md flex items-center gap-4 transition-transform hover:scale-105">
            <div class="flex-shrink-0 h-14 w-14 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                <p style="font-size:30px">&#127919;</p>
            </div>
            <div>
                <h3 class="font-bold text-lg text-black">Roleta da Sorte</h3>
                <p class="text-sm text-gray-600">Teste sua sorte.</p>
            </div>
        </a>

        <a href="{{ route('cliente.caixas-surpresa.index') }}" class="bg-white rounded-2xl p-6 shadow-md flex items-center gap-4 transition-transform hover:scale-105">
            <div class="flex-shrink-0 h-14 w-14 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                <p style="font-size:30px">&#127873;</p>
            </div>
            <div>
                <h3 class="font-bold text-lg text-black">Caixas Surpresa</h3>
                <p class="text-sm text-gray-600">Abra e ganhe prêmios!</p>
            </div>
        </a>

        <a href="{{ route('cliente.ranking.index') }}" class="bg-white rounded-2xl p-6 shadow-md flex items-center gap-4 transition-transform hover:scale-105">
            <div class="flex-shrink-0 h-14 w-14 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                <p style="font-size:30px">&#127942</p>
            </div>
            <div>
                <h3 class="font-bold text-lg text-black">Ranking do Mês</h3>
                <p class="text-sm text-gray-600">Veja os melhores!</p>
            </div>
        </a>

    </div>
</section>

