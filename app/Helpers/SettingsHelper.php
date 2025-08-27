<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    public static function get($key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * CORREÇÃO: Adicionado método para buscar múltiplas chaves de uma vez.
     * Isso otimiza as consultas ao banco de dados.
     *
     * @param array $keys
     * @return array
     */
    public static function getMany(array $keys): array
    {
        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key');
        
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $settings[$key] ?? null;
        }
        
        return $result;
    }
}