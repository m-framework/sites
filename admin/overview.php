<?php

namespace modules\sites\admin;

use m\module;
use m\view;
use m\i18n;
use m\config;
use modules\admin\admin\overview_data;

class overview extends module {

    public function _init()
    {
        config::set('per_page', 2000);

        view::set('content', overview_data::items(
            'modules\sites\models\sites',
            [
                'site' => i18n::get('Site id'),
                'host' => i18n::get('Host'),
            ],
            [],
            $this->view->overview,
            $this->view->overview_item
        ));
    }
}
