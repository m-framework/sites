<?php

namespace modules\sites\models;

use m\model;
use m\config;
use m\registry;
use m\cache;
use modules\sites\models\sites_options;

class sites extends model
{
    protected $fields = [
        'id' => 'int',
        'site' => 'int',
        'host' => 'varchar',
        'ip' => 'varchar',
        'owner_email' => 'varchar',
        'owner_phone' => 'varchar',
        'key' => 'varchar',
        'date' => 'timestamp',
    ];

    public static $types = [
        'card','shop','institution','board'
//        'blog','education','forum','landing','mail','media','portal','social',
    ];

    public function _init()
    {
        if (stripos($this->host, 'xn--') !== false) {
            $this->host = idn_to_ascii($this->host, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
        }
    }

    public function _before_destroy()
    {
        // TODO: destroy site options
    }

    public function get_by_host($host)
    {
        return $this->s(['*'], ['host' => $host])->obj();
    }

    public function init_options()
    {
        $options = sites_options::call_static()
            ->s(['parameter','value'], [
                'site' => $this->id, [['language' => registry::get('language_id')], ['language' => null]]], [1000])
            ->all();

        if (!empty($options)) {
            foreach ($options as $option) {
                $param = $option['parameter'];
                $this->$param = stripslashes(htmlspecialchars_decode($option['value']));
            }
        }
    }

    public static function types_options_arr()
    {
        $options = [];

        foreach (static::$types as $k => $type) {
            $options[] = [
                'value' => $type,
                'name' => $type,
            ];
        }

        return $options;
    }

    public static function sites_arr()
    {
        $sites = [];

        $_sites = static::call_static()->s([], [], [1000])->all();

        if (!empty($_sites)) {
            foreach ($_sites as $_site) {
                $sites[$_site['site']] = [
                    'value' => $_site['site'],
                    'name' => $_site['host'],
                ];
            }
        }

        return $sites;
    }
}