<?php

namespace Source\Support;

class Asset
{
    /**
     * Gera as tags de assets (CSS e JS) a partir do manifest.json do Vite.
     *
     * @param string $entry O arquivo de entrada (main.js')
     * @param string $manifestPath Caminho relativo para o manifest.json.
     * @param string $assetsBaseURL URL base para os assets.
     * @return string As tags HTML para inclusão dos assets.
     */
    public static function assets(
        string $entry = 'main.js',
        string $manifestPath = __DIR__ . '/../../public/dist/.vite/manifest.json',
        string $assetsBaseURL = '/dist/'): ?string
    {

        if (!file_exists($manifestPath)) return null;

        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (empty($manifest)) return null;


        if (!isset($manifest[$entry])) {
            foreach ($manifest as $key => $value) {
                if (strpos($key, $entry) !== false) {
                    $entry = $key;
                    break;
                }
            }
        }

        if (!isset($manifest[$entry])) return null;

        $entryData = $manifest[$entry];
        $tags = '';

        if (isset($entryData['css'])) {
            foreach ($entryData['css'] as $cssFile) {
                $tags .= '<link rel="stylesheet" href="' . $assetsBaseURL . $cssFile . '">' . PHP_EOL;
            }
        }

        if (isset($entryData['file'])) {
            $tags .= '<script type="module" src="' . $assetsBaseURL . $entryData['file'] . '"></script>' . PHP_EOL;
        }

        return $tags;
    }

}