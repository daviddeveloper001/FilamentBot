<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenAI\Laravel\Facades\OpenAI;

class CreateAssistant extends Command
{
    protected $signature = 'app:create-filament-assistant {file_id}';

    protected $description = 'Create an assistant for Filament documentation';

    public function handle()
    {
        $fileId = $this->argument('file_id');

        $assistant = OpenAI::assistants()->create([
            'name' => 'FilamentBot',
            'tools' => [
                [
                    'type' => 'code_interpreter',
                ],
            ],
            'tool_resources' => [
                'code_interpreter' => [
                    'file_ids' => [$fileId],
                ],
            ],
            'instructions' => 'You are a helpful bot that helps developers using Filament 3. 
                You can answer questions about the framework and help them find the right documentation. 
                Use the uploaded files to answer the questions. The answers should always be in Spanish.',
            'model' => 'gpt-4o-mini-2024-07-18',
        ]);

        $this->info('Assistant ID: ' . $assistant->id);
    }
}
