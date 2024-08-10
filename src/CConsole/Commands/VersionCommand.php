<?php
namespace Cresenity\Laravel\CConsole\Commands;

use Cresenity\Laravel\CF;
use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class VersionCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cresenity:version';

    public function handle() {
        $this->info(CF::version());
    }
}
