<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DestaqueHome extends Model {
    use HasFactory;
    protected $table = 'destaques_home';
    
    // ADICIONADO 'status' de volta à lista fillable
    protected $fillable = [
        'imagem', 
        'titulo', 
        'subtitulo', 
        'descricao', 
        'valor_moedas', 
        'texto_botao', 
        'link_botao', 
        'ordem',
        'status' // <-- CORREÇÃO
    ];

    /**
     * Acessor para obter a URL completa da imagem.
     * Isso lida com imagens salvas no método antigo (storage) e no novo (public).
     */
    public function getImageUrlAttribute()
    {
        if (!$this->imagem) {
            // Retorna nulo ou um placeholder se não houver imagem
            return null; 
        }

        // Verifica se o caminho da imagem é o novo (na pasta public/uploads)
        if (str_starts_with($this->imagem, 'uploads/')) {
            return asset($this->imagem);
        }

        // Se não, assume que é o caminho antigo e usa Storage::url()
        return Storage::url($this->imagem);
    }
}