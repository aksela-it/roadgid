<?php

namespace common\helpers;

use Yii;
use yii\base\Component;

class Console extends Component
{

    public $yii_script = null;
    public $phpexec;

    public function init()
    {
        parent::init();

        set_time_limit(0);

        if($this->yii_script == null) {
            $this->yii_script = "@yii_console";
        }
    }

    public function run($cmd)
    {
        pclose(popen($this->buildCommand($cmd), 'r'));
    }

    protected function buildCommand($cmd)
    {
        $alias = Yii::getAlias($this->yii_script);
        $cmd = "{$this->getPHPExecutable()} {$alias} {$cmd}";
        return "{$cmd} > /dev/null 2>&1 &";
    }

    public function getPHPExecutable()
    {
        if($this->phpexec) {
            return $this->phpexec;
        }

        return strpos(PHP_SAPI, 'apache') !== false ? PHP_BINDIR . '/php' : PHP_BINARY;
    }
}