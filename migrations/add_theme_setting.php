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

class add_theme_setting extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return isset($this->config['modernstats_theme']);
    }

    public static function depends_on()
    {
        return array('\illusion\modernstats\migrations\install_acp_module');
    }

    public function update_data()
    {
        return array(
            // Add theme config variable - default is 'light'
            array('config.add', array('modernstats_theme', 'light')),
        );
    }
}
