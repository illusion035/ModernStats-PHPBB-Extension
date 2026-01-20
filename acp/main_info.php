<?php

/**
 *
 * Modern Statistics Extension
 *
 * @copyright (c) 2025 Illusion
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace illusion\modernstats\acp;

class main_info
{
    public function module()
    {
        return array(
            'filename' => '\illusion\modernstats\acp\main_module',
            'title' => 'ACP_MODERNSTATS_TITLE',
            'modes' => array(
                'settings' => array(
                    'title' => 'ACP_MODERNSTATS_SETTINGS',
                    'auth' => 'ext_illusion/modernstats && acl_a_board',
                    'cat' => array('ACP_MODERNSTATS_TITLE')
                ),
            ),
        );
    }
}
