<?php
namespace Mcms\Modules\Cli\Tasks;

use Phalcon\Cli\Task;

class MainTask extends Task
{
    public function mainAction()
    {
        echo "Welcome to the mCMS installer!", PHP_EOL;
        echo "Type `./run install` to install and configure everything for your first installation.";
    }

}
