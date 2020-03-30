<?php

namespace modules\sites\models;

use m\model;
use m\core;
use m\config;
use m\registry;

class settings extends model
{

    public function _before_save()
    {
        $settings = (array)include(config::get('root_path') . '/m-framework/conf.php');

        if (empty(registry::get('post')->settings) || !is_array(registry::get('post')->settings) || empty($settings)) {
            return true;
        }

        $valid_settings = true;

        foreach (array_keys((array)(registry::get('post')->settings)) as $key) {
            if (!isset($settings[$key])) {
                $valid_settings = false;
            }
        }

        if ($valid_settings) {

            $config_path = config::get('root_path') . '/' . registry::get('site')->host . '.php'; //  . '_' . time()

            $arr = array_replace_recursive($settings, (array)(registry::get('post')->settings));

            file_put_contents($config_path, '<?php return ' . var_export($arr, true) . ";\n");

            $_SESSION['saved_settings'] = 1;
        }

//        core::out(registry::get('post')->settings);

        core::redirect(config::get('previous'));
    }
}