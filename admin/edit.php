<?php

namespace modules\sites\admin;

use m\module;
use m\i18n;
use m\registry;
use m\view;
use m\form;
use modules\sites\models\sites;

class edit extends module {

    public function _init()
    {
        if (!isset($this->view->{'site_' . $this->name . '_form'})) {
            return false;
        }

        $site = new sites(!empty($this->get->edit) ? $this->get->edit : null);

        if (!empty($site->id)) {
            view::set('page_title', '<h1><i class="fa fa-file-text-o"></i> ' . i18n::get('Edit site host') . ' `' . $site->host . '`</h1>');
            registry::set('title', i18n::get('Edit site host'));
        }

        new form(
            $site,
            [
                'site' => [
                    'type' => 'int',
                    'field_name' => i18n::get('Site id'),
                ],
                'host' => [
                    'type' => 'varchar',
                    'field_name' => i18n::get('Host'),
                ],
            ],
            [
                'form' => $this->view->{'site_' . $this->name . '_form'},
                'varchar' => $this->view->edit_row_varchar,
                'int' => $this->view->edit_row_int,
                'saved' => $this->view->edit_row_saved,
                'error' => $this->view->edit_row_error,
            ]
        );
    }
}