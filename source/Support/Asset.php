<?php

namespace Source\Support;

use Source\Core\Router;

class Asset
{
    /**
     * Gera as tags de assets (CSS e JS) a partir do manifest.json do Vite.
     *
     * @param string $entry Arquivo de entrada (ex: 'main.js')
     * @param string $manifestPath Caminho absoluto para o manifest.json.
     * @param Router|null $router Instância opcional do Router para obter basePath.
     * @return string|null HTML com tags <link> e <script>
     */
    public static function assets(
        string $entry = 'main.js',
        string $manifestPath = __DIR__ . '/../../public/dist/.vite/manifest.json',
        ?Router $router = null
    ): ?string {
        if (!file_exists($manifestPath)) {
            return null;
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (empty($manifest)) {
            return null;
        }

        // Corrige o entry se não encontrado diretamente
        if (!isset($manifest[$entry])) {
            foreach ($manifest as $key => $value) {
                if (str_contains($key, $entry)) {
                    $entry = $key;
                    break;
                }
            }
        }

        if (!isset($manifest[$entry])) {
            return null;
        }

        $entryData = $manifest[$entry];

        // Base URL considerando o basePath
        $baseURL = ($router?->basePath() ?? '') . '/dist/';
        $tags = '';

        if (!empty($entryData['css'])) {
            foreach ($entryData['css'] as $cssFile) {
                $tags .= '<link rel="stylesheet" href="' . $baseURL . $cssFile . '">' . PHP_EOL;
            }
        }

        if (!empty($entryData['file'])) {
            $tags .= '<script type="module" src="' . $baseURL . $entryData['file'] . '"></script>' . PHP_EOL;
        }

        return $tags;
    }
}
