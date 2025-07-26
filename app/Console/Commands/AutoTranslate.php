<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AutoTranslate extends Command
{
    protected $signature = 'auto:translate';
    protected $description = 'Traduire automatiquement les fichiers de langue';

    public function handle()
    {
        $fromLang = 'fr';
        $targetLangs = ['en', 'ar'];

        // Récupère tous les fichiers php dans lang/fr
        $sourceFiles = glob(resource_path("lang/{$fromLang}/*.php"));

        foreach ($sourceFiles as $sourceFile) {

            // Charge le tableau de traductions source
            $source = require $sourceFile;

            // Récupère le nom du fichier (ex: messages.php, demandes.php)
            $filename = basename($sourceFile);

            foreach ($targetLangs as $lang) {
                $tr = new GoogleTranslate($lang);

                $dir = resource_path("lang/{$lang}");
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                $translated = $this->translateArray($source, $tr);

                $path = resource_path("lang/{$lang}/{$filename}");
                file_put_contents($path, "<?php\n\nreturn " . var_export($translated, true) . ";\n");

                $this->info("✅ Traduction du fichier [$filename] vers [$lang] terminée !");
            }
        }
    }

    private function translateArray(array $array, GoogleTranslate $tr): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_string($value)) {
                try {
                    $result[$key] = $tr->translate($value);
                } catch (\Exception $e) {
                    $result[$key] = $value;
                    $this->warn("Erreur traduction clé '$key' : " . $e->getMessage());
                }
            } elseif (is_array($value)) {
                $result[$key] = $this->translateArray($value, $tr);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
