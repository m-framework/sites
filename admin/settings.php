<?php

namespace modules\sites\admin;

use m\core;
use m\module;
use m\i18n;
use m\registry;
use modules\templates\models\templates;
use m\view;
use m\form;
use m\config;
use modules\sites\models\sites;

class settings extends module {

    protected $css = ['/css/settings.css'];

    public function _init()
    {
        if (!isset($this->view->{'settings_form'})) {
            return false;
        }

        $settings = new \modules\sites\models\settings();
        $settings->import($this->config);

        $default_settings = include(config::get('root_path') . '/m-framework/conf.php');

        $site_types_options = [];
        foreach (sites::types_options_arr() as $option) {
            $site_types_options[] = '<option value="' . $option['value'] . '"' . ($this->site->type == $option['value'] ? ' selected' : '') . '>' . $option['name'] . '</option>';
        }

        $site_templates_options = [];
        foreach (templates::get_templates_options_arr() as $option) {
            $site_templates_options[] = '<option value="' . $option['value'] . '"' . ($this->site->template == $option['value'] ? ' selected' : '') . '>' . $option['name'] . '</option>';
        }

        $default_languages_options = [];
        foreach ($default_settings['available_languages'] as $language) {
            $default_languages_options[] = '<option value="' . $language . '"' . (in_array($language, (array)$default_settings['languages']) ? ' selected' : '') . '>' . $language . '</option>';
        }

        $current_languages_options = [];
        foreach ($this->config->languages as $language) {
            $current_languages_options[] = '<option value="' . $language . '" selected>' . $language . '</option>';
        }

        $default_currencies_options = [];
        foreach ($default_settings['currencies'] as $currency) {
            $default_currencies_options[] = '<option selected>' . $currency . '</option>';
        }

        $current_currencies_options = [];
        foreach ((array)$this->conf->currencies as $currency) {
            $current_currencies_options[] = '<option selected>' . $currency . '</option>';
        }

        $default_developers_ips_options = [];
        foreach ($default_settings['developers_ips'] as $_ip) {
            $default_developers_ips_options[] = '<option selected>' . $_ip . '</option>';
        }

        $current_developers_ips_options = [];
        foreach ((array)$this->config->developers_ips as $_ip) {
            $current_developers_ips_options[] = '<option selected>' . $_ip . '</option>';
        }

        $default_allowed_file_types_options = [];
        foreach ($default_settings['allowed_file_types'] as $allowed_file_type) {
            $default_allowed_file_types_options[] = '<option selected>' . $allowed_file_type . '</option>';
        }

        $current_allowed_file_types_options = [];
        foreach ((array)$this->config->allowed_file_types as $allowed_file_type) {
            $current_allowed_file_types_options[] = '<option selected>' . $allowed_file_type . '</option>';
        }

        $default_ajax_open_actions_options = [];
        foreach ($default_settings['ajax_open_actions'] as $ajax_open_action) {
            $default_ajax_open_actions_options[] = '<option selected>' . $ajax_open_action . '</option>';
        }

        $current_ajax_open_actions_options = [];
        foreach ((array)$this->config->ajax_open_actions as $ajax_open_action) {
            $current_ajax_open_actions_options[] = '<option selected>' . $ajax_open_action . '</option>';
        }

        return new form(
            $settings,
            [
                'site' => [
                    'type' => 'site_options',
                    'field_name' => i18n::get('Site options'),
                    'options' => [
                        'default_site_id' => @$default_settings['site']['id'],
                        'site_id' => $this->site->id,
                        'default_site_type' => $default_settings['site']['type'],
                        'site_types_options' => implode('', $site_types_options),
                        'default_site_template' => $default_settings['site']['template'],
                        'site_template' => $this->site->template,
                        'site_templates_options' => implode('', $site_templates_options),
                        'default_site_host' => $default_settings['site']['host'],
                        'site_host' => $this->site->host,
                        'default_site_title' => $default_settings['site']['title'],
                        'site_title' => $this->site->title,
                        'default_site_slogan' => $default_settings['site']['slogan'],
                        'site_slogan' => $this->site->slogan,
                        'default_site_footer_text' => $default_settings['site']['footer_text'],
                        'site_footer_text' => htmlspecialchars($this->site->footer_text),
                    ],
                ],
                'main_domain' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Main domain'),
                    'options' => [
                        'default_value' => $default_settings['main_domain'],
                    ],
                ],
                'languages' => [
                    'type' => 'settings_array_row',
                    'field_name' => i18n::get('Languages'),
                    'options' => [
                        'default_options' => implode('', $default_languages_options),
                        'current_options' => implode('', $current_languages_options),
                    ]
                ],
                'currencies' => [
                    'type' => 'settings_array_row',
                    'field_name' => i18n::get('Currencies'),
                    'options' => [
                        'default_options' => implode('', $default_currencies_options),
                        'current_options' => implode('', $current_currencies_options),
                    ]
                ],
                'db' => [
                    'type' => 'settings_db_row',
                    'field_name' => i18n::get('DB connections'),
                    'options' => [
                        'default_db_mysqli_db_name' => $default_settings['db']['mysqli']['db_name'],
                        'db_mysqli_db_name' => $this->conf->db['mysqli']['db_name'],
                        'default_db_mysqli_host' => $default_settings['db']['mysqli']['host'],
                        'db_mysqli_host' => $this->conf->db['mysqli']['host'],
                        'default_db_mysqli_user' => $default_settings['db']['mysqli']['user'],
                        'db_mysqli_user' => $this->conf->db['mysqli']['user'],
                        'default_db_mysqli_password' => $default_settings['db']['mysqli']['password'],
                        'db_mysqli_password' => $this->conf->db['mysqli']['password'],
                        'default_db_pgsql_db_name' => $default_settings['db']['pgsql']['db_name'],
                        'db_pgsql_db_name' => $this->conf->db['pgsql']['db_name'],
                        'default_db_pgsql_host' => $default_settings['db']['pgsql']['host'],
                        'db_pgsql_host' => $this->conf->db['pgsql']['host'],
                        'default_db_pgsql_user' => $default_settings['db']['pgsql']['user'],
                        'db_pgsql_user' => $this->conf->db['pgsql']['user'],
                        'default_db_pgsql_password' => $default_settings['db']['pgsql']['password'],
                        'db_pgsql_password' => $this->conf->db['pgsql']['password'],
                        'default_db_mongodb_db_name' => $default_settings['db']['mongodb']['db_name'],
                        'db_mongodb_db_name' => $this->conf->db['mongodb']['db_name'],
                        'default_db_mongodb_host' => $default_settings['db']['mongodb']['host'],
                        'db_mongodb_host' => $this->conf->db['mongodb']['host'],
                        'default_db_mongodb_user' => $default_settings['db']['mongodb']['user'],
                        'db_mongodb_user' => $this->conf->db['mongodb']['user'],
                        'default_db_mongodb_password' => $default_settings['db']['mongodb']['password'],
                        'db_mongodb_password' => $this->conf->db['mongodb']['password'],
                    ]
                ],
                'profile_length' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Profile length'),
                    'options' => ['default_value' => $default_settings['profile_length']],
                ],
                'admin_mail' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Admin mail'),
                    'options' => ['default_value' => $default_settings['admin_mail']],
                ],
                'no_reply_mail' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Do not reply Email'),
                    'options' => ['default_value' => $default_settings['no_reply_mail']],
                ],
                'error_reporting' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Parameter') . ' `error_reporting`',
                    'options' => ['default_value' => $default_settings['error_reporting']],
                ],
                'display_errors' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Parameter') . ' `display_errors`',
                    'options' => ['default_value' => $default_settings['display_errors']],
                ],
                'max_execution_time' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Parameter') . ' `max_execution_time`',
                    'options' => ['default_value' => $default_settings['max_execution_time']],
                ],
                'authorize_expire' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Authorization expire (in sec.)'),
                    'options' => ['default_value' => $default_settings['authorize_expire']],
                ],
                'authorisation_error_expire' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Authorisation error expire (in minutes)'),
                    'options' => ['default_value' => $default_settings['authorisation_error_expire']],
                ],
                'social_api' => [
                    'type' => 'settings_social_row',
                    'field_name' => i18n::get('Social networks API settings'),
                    'options' => [
                        'default_social_api_vk_app_id' => $default_settings['social_api']['vk']['app_id'],
                        'social_api_vk_app_id' => $this->conf->social_api['vk']['app_id'],
                        'default_social_api_vk_app_secret' => $default_settings['social_api']['vk']['app_secret'],
                        'social_api_vk_app_secret' => $this->conf->social_api['vk']['app_secret'],
                        'default_social_api_fb_app_id' => $default_settings['social_api']['fb']['app_id'],
                        'social_api_fb_app_id' => $this->conf->social_api['fb']['app_id'],
                        'default_social_api_fb_app_secret' => $default_settings['social_api']['fb']['app_secret'],
                        'social_api_fb_app_secret' => $this->conf->social_api['fb']['app_secret'],
                        'default_social_api_li_app_id' => $default_settings['social_api']['li']['app_id'],
                        'social_api_li_app_id' => $this->conf->social_api['li']['app_id'],
                        'default_social_api_li_app_secret' => $default_settings['social_api']['li']['app_secret'],
                        'social_api_li_app_secret' => $this->conf->social_api['li']['app_secret'],
                        'default_social_api_tw_app_id' => $default_settings['social_api']['tw']['app_id'],
                        'social_api_tw_app_id' => $this->conf->social_api['tw']['app_id'],
                        'default_social_api_tw_app_secret' => $default_settings['social_api']['tw']['app_secret'],
                        'social_api_tw_app_secret' => $this->conf->social_api['tw']['app_secret'],
                        'default_social_api_go_app_id' => $default_settings['social_api']['go']['app_id'],
                        'social_api_go_app_id' => $this->conf->social_api['go']['app_id'],
                        'default_social_api_go_app_secret' => $default_settings['social_api']['go']['app_secret'],
                        'social_api_go_app_secret' => $this->conf->social_api['go']['app_secret'],
                        'default_social_api_in_app_id' => $default_settings['social_api']['in']['app_id'],
                        'social_api_in_app_id' => $this->conf->social_api['in']['app_id'],
                        'default_social_api_in_app_secret' => $default_settings['social_api']['in']['app_secret'],
                        'social_api_in_app_secret' => $this->conf->social_api['in']['app_secret'],
                    ],
                ],
                'db_logs' => [
                    'type' => 'settings_tinyint_row',
                    'field_name' => i18n::get('Allow logging DB queries'),
                    'options' => [
                        'checked_default' => empty($default_settings['db_logs']) ? '' : 'checked',
                        'checked_current' => empty($this->config->db_logs) ? '' : 'checked',
                    ],
                ],
                'debug_enable' => [
                    'type' => 'settings_tinyint_row',
                    'field_name' => i18n::get('Allow debugging info'),
                    'options' => [
                        'checked_default' => empty($default_settings['debug_enable']) ? '' : 'checked',
                        'checked_current' => empty($this->config->debug_enable) ? '' : 'checked',
                    ],
                ],
                'developers_ips' => [
                    'type' => 'settings_array_row',
                    'field_name' => i18n::get('Developers IP-addresses'),
                    'options' => [
                        'default_options' => implode('', $default_developers_ips_options),
                        'current_options' => implode('', $current_developers_ips_options),
                    ]
                ],
                'log_to_db' => [
                    'type' => 'settings_tinyint_row',
                    'field_name' => i18n::get('Allow logging to DB'),
                    'options' => [
                        'checked_default' => empty($default_settings['log_to_db']) ? '' : 'checked',
                        'checked_current' => empty($this->config->log_to_db) ? '' : 'checked',
                    ],
                ],
                'log_to_file' => [
                    'type' => 'settings_tinyint_row',
                    'field_name' => i18n::get('Allow logging to file'),
                    'options' => [
                        'checked_default' => empty($default_settings['log_to_file']) ? '' : 'checked',
                        'checked_current' => empty($this->config->log_to_file) ? '' : 'checked',
                    ],
                ],
                'log_path' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Logging file path'),
                    'options' => ['default_value' => $default_settings['log_path']],
                ],
                'data_path' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Data path'),
                    'options' => ['default_value' => $default_settings['data_path']],
                ],
                'templates_path' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Templates path'),
                    'options' => ['default_value' => $default_settings['templates_path']],
                ],
                'fonts_path' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Fonts path'),
                    'options' => ['default_value' => $default_settings['fonts_path']],
                ],
                'i18n_path' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Translations files path'),
                    'options' => ['default_value' => $default_settings['i18n_path']],
                ],
                'backups_path' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Backup files path'),
                    'options' => ['default_value' => $default_settings['backups_path']],
                ],
                'tmp_path' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Temporary files path'),
                    'options' => ['default_value' => $default_settings['tmp_path']],
                ],
                'application_path' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Override (applications) path'),
                    'options' => ['default_value' => $default_settings['application_path']],
                ],
                'cache_enable' => [
                    'type' => 'settings_tinyint_row',
                    'field_name' => i18n::get('Allow caching to files'),
                    'options' => [
                        'checked_default' => empty($default_settings['cache_enable']) ? '' : 'checked',
                        'checked_current' => empty($this->config->cache_enable) ? '' : 'checked',
                    ],
                ],
                'cache_path' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Cache files path'),
                    'options' => ['default_value' => $default_settings['cache_path']],
                ],
                'cache_time' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Caching expire time'),
                    'options' => ['default_value' => $default_settings['cache_time']],
                ],
                'per_page' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Items per page'),
                    'options' => ['default_value' => $default_settings['per_page']],
                ],
                'one_view' => [
                    'type' => 'settings_tinyint_row',
                    'field_name' => i18n::get('One view (no separate views in templates)'),
                    'options' => [
                        'checked_default' => empty($default_settings['one_view']) ? '' : 'checked',
                        'checked_current' => empty($this->config->one_view) ? '' : 'checked',
                    ],
                ],
                'force_https' => [
                    'type' => 'settings_tinyint_row',
                    'field_name' => i18n::get('Force redirect a website to HTTPS'),
                    'options' => [
                        'checked_default' => empty($default_settings['force_https']) ? '' : 'checked',
                        'checked_current' => empty($this->config->force_https) ? '' : 'checked',
                    ],
                ],
                'title_delimiter' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Title\'s delimiter'),
                    'options' => ['default_value' => $default_settings['title_delimiter']],
                ],
                'allow_visitors' => [
                    'type' => 'settings_tinyint_row',
                    'field_name' => i18n::get('Allow visitors on website') . ' (' . i18n::get('be careful, it needs always on commercial websites') . ')',
                    'options' => [
                        'checked_default' => empty($default_settings['allow_visitors']) ? '' : 'checked',
                        'checked_current' => empty($this->config->allow_visitors) ? '' : 'checked',
                    ],
                ],
                'admin_panel_alias' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Admin\'s panel alias'),
                    'options' => ['default_value' => $default_settings['admin_panel_alias']],
                ],
                'allowed_file_types' => [
                    'type' => 'settings_array_row',
                    'field_name' => i18n::get('Allowed files types for uploads'),
                    'options' => [
                        'default_options' => implode('', $default_allowed_file_types_options),
                        'current_options' => implode('', $current_allowed_file_types_options),
                    ]
                ],
                'default_image_width' => [
                    'type' => 'settings_row',
                    'field_name' => i18n::get('Default uploaded image width'),
                    'options' => ['default_value' => $default_settings['default_image_width']],
                ],
                'ajax_open_actions' => [
                    'type' => 'settings_array_row',
                    'field_name' => i18n::get('Modules actions which are allowed to access via Ajax for every customers'),
                    'options' => [
                        'default_options' => implode('', $default_ajax_open_actions_options),
                        'current_options' => implode('', $current_ajax_open_actions_options),
                    ]
                ],
            ],
            [
                'form' => $this->view->{'settings_form'},
                'settings_row' => $this->view->settings_row,
                'site_options' => $this->view->site_options_row,
                'settings_array_row' => $this->view->settings_array_row,
                'settings_db_row' => $this->view->settings_db_row,
                'settings_social_row' => $this->view->settings_social_row,
                'settings_tinyint_row' => $this->view->settings_tinyint_row,
                'saved' => $this->view->div_success,
                'error' => $this->view->div_error,
            ]
        );
    }
}