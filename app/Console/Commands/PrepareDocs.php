<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PrepareDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prepare-filament-docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create one file containing the full Filament documentation';

    /**
     * Execute the console command.
     */

     private static array $FILES_TO_IGNORE = [
        'LICENSE.md',
        'README.md',
    ];
    public function handle()
    {
        ini_set('max_execution_time', '10000000');

        // Obtener los archivos del repositorio de documentación de Filament
        $files = Http::get('https://api.github.com/repos/filamentphp/filament/contents')->collect();

        $fullDocs = $files->filter(fn (array $file) => $file['download_url'] != null)
            ->filter(fn (array $file) => ! in_array($file['name'], self::$FILES_TO_IGNORE))
            ->map(fn (array $file) => Http::get($file['download_url'])->body())
            ->implode(PHP_EOL.PHP_EOL);

        // Guardar la documentación completa en un archivo local
        Storage::disk('local')->put('full-filament-docs.md', $fullDocs);

        $this->info('Filament documentation has been prepared successfully.');
    }
}
