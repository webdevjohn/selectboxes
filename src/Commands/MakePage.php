<?php

namespace Webdevjohn\SelectBoxes\Commands;

use Illuminate\Console\GeneratorCommand;

class MakePage extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'page:make {name} {App/Services/SelectBoxes/Pages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new page.';
    
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return realpath(__DIR__ . '/../stubs/Page.stub');
    }

    /**
     * Append the root namespace.
     *
     * @return string
     */
    protected function appendRootNamespace()
    {
        return '\\Services\\SelectBoxes\\Pages';
    }
}
