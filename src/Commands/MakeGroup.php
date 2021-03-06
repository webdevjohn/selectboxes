<?php

namespace Webdevjohn\SelectBoxes\Commands;

use Illuminate\Console\GeneratorCommand;
use Webdevjohn\SelectBoxes\Commands\Traits\CustomisableNamespace;

class MakeGroup extends GeneratorCommand
{
    use CustomisableNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group:make {name} {DummyNamespace? : App/Services/SelectBoxes/Groups}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new select box group.';
    
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return realpath(__DIR__ . '/../stubs/Group.stub');
    }

    /**
     * Append the root namespace.
     *
     * @return string
     */
    protected function appendRootNamespace()
    {
        return '\\Services\\SelectBoxes\\Groups';
    }
}
