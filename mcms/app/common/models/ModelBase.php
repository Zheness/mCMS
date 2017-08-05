<?php

namespace Mcms\Models;

use Phalcon\Mvc\Model;

class ModelBase extends Model
{
    public function initialize()
    {
        $this->keepSnapshots(true);
    }
}
