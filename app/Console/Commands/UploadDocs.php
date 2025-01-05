<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Storage;

class UploadDocs extends Command
{
    protected $signature = 'app:upload-filament-docs';

    protected $description = 'Uploads the Filament docs to OpenAI';

    public function handle()
    {
        $uploadedFile = OpenAI::files()->upload([
            'file' => Storage::disk('local')->readStream('full-filament-docs.md'),
            'purpose' => 'assistants',
        ]);

        $this->info('File ID: ' . $uploadedFile->id);
    }
}
