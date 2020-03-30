<?php

namespace modules\sites\models;

use m\model;

class sites_options extends model
{
    public $_table = 'sites_options';

    protected $fields = [
        'id' => 'int',
        'site' => 'int',
        'language' => 'int',
        'parameter' => 'varchar',
        'value' => 'varchar',
    ];
}