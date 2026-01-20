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

class add_excluded_groups extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return isset($this->config['modernstats_excluded_groups']);
    }

    public static function depends_on()
    {
        return array('\illusion\modernstats\migrations\install_acp_module');
    }

    public function update_data()
    {
        return array(
            // Add config variable for excluded groups (empty by default - no groups excluded)
            array('config.add', array('modernstats_excluded_groups', '')),
        );
    }
}
