<?php
/**
 *
 * Modern Statistics Extension
 *
 * @copyright (c) 2025 illusion
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace illusion\modernstats\migrations;

class install_acp_module extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return isset($this->config['modernstats_show_latest_posts']);
    }

    public static function depends_on()
    {
        return array('\phpbb\db\migration\data\v320\v320');
    }

    public function update_data()
    {
        return array(
            // Add ACP modules
            array(
                'module.add',
                array(
                    'acp',
                    'ACP_CAT_DOT_MODS',
                    'ACP_MODERNSTATS_TITLE'
                )
            ),
            array(
                'module.add',
                array(
                    'acp',
                    'ACP_MODERNSTATS_TITLE',
                    array(
                        'module_basename' => '\illusion\modernstats\acp\main_module',
                        'modes' => array('settings'),
                    ),
                )
            ),

            // Add config variables
            array('config.add', array('modernstats_show_latest_posts', 1)),
            array('config.add', array('modernstats_show_latest_users', 1)),
            array('config.add', array('modernstats_show_statistics', 1)),
            array('config.add', array('modernstats_latest_posts_limit', 10)),
            array('config.add', array('modernstats_latest_users_limit', 10)),
        );
    }
}