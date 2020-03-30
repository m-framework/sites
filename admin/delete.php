<?php

namespace modules\sites\admin;

use m\module;
use m\core;
use modules\sites\models\sites;

class delete extends module {

    public function _init()
    {
        $site = new sites(!empty($this->get->delete) ? $this->get->delete : null);

        if (!empty($site->id) && !empty($this->user->profile) && $this->user->is_admin() && $site->destroy()) {
            core::redirect('/' . $this->conf->admin_panel_alias . '/sites');
        }
    }
}
