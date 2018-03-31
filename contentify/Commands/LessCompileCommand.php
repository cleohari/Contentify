<?php

namespace Contentify\Commands;

use Config;
use HTML;
use Illuminate\Console\Command;
use Less_Parser;

class LessCompileCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'less:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Compile the theme's frontend and the backend LESS files to CSS files";

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $this->info('Compiling LESS files...');

        $theme = Config::get('app.theme');

        // Key = path, value = filename
        $lessFiles = [
            Config::get('modules.path').'/'.$theme.'/Resources/Assets/less/' => 'frontend',
            resource_path('assets/less/') => 'backend',
        ];

        foreach ($lessFiles as $sourcePath => $sourceFilename) {
            $this->compileLessFile($sourcePath, $sourceFilename);
        }

        HTML::refreshAssetPaths();
    }

    /**
     * Compiles a LESS file to a CSS file
     *
     * @param string $sourcePath
     * @param string $sourceFilename
     * @return void
     */
    protected function compileLessFile($sourcePath, $sourceFilename)
    {
        $debug = Config::get('app.debug');

        // Create a new instance for each file - or call the reset method
        $parser = new Less_Parser(['compress' => ! $debug]);

        $source = $sourcePath.$sourceFilename.'.less';
        $parser->parseFile($source);

        $target = public_path('css/'.$sourceFilename.'.css');
        file_put_contents($target, $parser->getCss());

        $this->info('CSS files has been compiled: '.$source.' -> '.$target."\n");
    }

}
