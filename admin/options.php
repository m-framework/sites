<?php

namespace modules\sites\admin;

use m\module;
use m\core;
use m\view;
use m\i18n;
use m\registry;
use modules\admin\admin\overview_data;
use modules\sites\models\sites;

class options extends module
{
    public function _init()
    {
        if (empty($this->get->options)) {
            core::redirect('/' . $this->conf->admin_panel_alias . '/sites');
        }

        $site = sites::call_static()->s([],['site' => $this->get->options])->row();

        view::set('page_title', '<h1><i class="fa fa-cog"></i> *Edit site options* `' . $site['host'] . '`</h1>');
        registry::set('title', i18n::get('Edit site options'));

        registry::set('breadcrumbs', [
            '/' . $this->conf->admin_panel_alias . '/sites' => '*Web-sites*',
            '' => '*Edit site options*'
        ]);

        view::set('content', overview_data::items(
            'modules\sites\models\sites_options',
            [
                'parameter' => i18n::get('Parameter'),
                'value' => i18n::get('Value'),
            ],
            ['site' => $this->get->options, 'language' => $this->language_id],
            $this->view->options_overview,
            $this->view->options_overview_item
        ));

        view::set_css($this->module_path . '/css/options_overview.css');
    }
}
