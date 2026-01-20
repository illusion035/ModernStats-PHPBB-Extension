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

class main_module
{
    public $u_action;
    public $tpl_name;
    public $page_title;

    public function main($id, $mode)
    {
        global $phpbb_container;

        $config = $phpbb_container->get('config');
        $request = $phpbb_container->get('request');
        $template = $phpbb_container->get('template');
        $user = $phpbb_container->get('user');
        $language = $phpbb_container->get('language');
        $db = $phpbb_container->get('dbal.conn');

        $language->add_lang('mods/modernstats_acp', 'illusion/modernstats');

        $this->tpl_name = 'acp_modernstats_body';
        $this->page_title = $language->lang('ACP_MODERNSTATS_TITLE');

        if ($request->is_set_post('submit')) {
            if (!check_form_key('illusion/modernstats')) {
                trigger_error('FORM_INVALID');
            }

            // Get excluded groups as array and convert to comma-separated string
            $excluded_groups = $request->variable('modernstats_excluded_groups', array(0));
            $excluded_groups_str = implode(',', array_filter($excluded_groups));

            // Save settings
            $config->set('modernstats_show_latest_posts', $request->variable('modernstats_show_latest_posts', 1));
            $config->set('modernstats_show_latest_users', $request->variable('modernstats_show_latest_users', 1));
            $config->set('modernstats_show_statistics', $request->variable('modernstats_show_statistics', 1));
            $config->set('modernstats_latest_posts_limit', $request->variable('modernstats_latest_posts_limit', 10));
            $config->set('modernstats_latest_users_limit', $request->variable('modernstats_latest_users_limit', 10));
            $config->set('modernstats_display_location', $request->variable('modernstats_display_location', 'after_forumlist'));
            $config->set('modernstats_theme', $request->variable('modernstats_theme', 'light'));
            $config->set('modernstats_excluded_groups', $excluded_groups_str);

            trigger_error($language->lang('ACP_MODERNSTATS_SETTINGS_SAVED') . adm_back_link($this->u_action));
        }

        add_form_key('illusion/modernstats');

        $display_locations = array(
            'before_forumlist' => $language->lang('ACP_MODERNSTATS_LOCATION_BEFORE_FORUMLIST'),
            'after_forumlist' => $language->lang('ACP_MODERNSTATS_LOCATION_AFTER_FORUMLIST'),
            'before_markforums' => $language->lang('ACP_MODERNSTATS_LOCATION_BEFORE_MARKFORUMS'),
            'after_markforums' => $language->lang('ACP_MODERNSTATS_LOCATION_AFTER_MARKFORUMS'),
        );

        $theme_options = array(
            'light' => $language->lang('ACP_MODERNSTATS_THEME_LIGHT'),
            'dark' => $language->lang('ACP_MODERNSTATS_THEME_DARK'),
        );

        // Get currently excluded groups
        $excluded_groups = isset($config['modernstats_excluded_groups']) ? explode(',', $config['modernstats_excluded_groups']) : array();
        $excluded_groups = array_filter($excluded_groups);

        // Load all groups from database
        $sql = 'SELECT group_id, group_name, group_type
				FROM ' . \GROUPS_TABLE . '
				ORDER BY group_name ASC';
        $result = $db->sql_query($sql);

        while ($row = $db->sql_fetchrow($result)) {
            $group_name = ($row['group_type'] == \GROUP_SPECIAL) ? $language->lang('G_' . $row['group_name']) : $row['group_name'];

            $template->assign_block_vars('groups', array(
                'GROUP_ID' => $row['group_id'],
                'GROUP_NAME' => $group_name,
                'S_SELECTED' => in_array($row['group_id'], $excluded_groups),
            ));
        }
        $db->sql_freeresult($result);

        $template->assign_vars(array(
            'U_ACTION' => $this->u_action,
            'MODERNSTATS_SHOW_LATEST_POSTS' => $config['modernstats_show_latest_posts'],
            'MODERNSTATS_SHOW_LATEST_USERS' => $config['modernstats_show_latest_users'],
            'MODERNSTATS_SHOW_STATISTICS' => $config['modernstats_show_statistics'],
            'MODERNSTATS_LATEST_POSTS_LIMIT' => $config['modernstats_latest_posts_limit'],
            'MODERNSTATS_LATEST_USERS_LIMIT' => $config['modernstats_latest_users_limit'],
            'MODERNSTATS_DISPLAY_LOCATION' => $config['modernstats_display_location'],
            'MODERNSTATS_THEME' => isset($config['modernstats_theme']) ? $config['modernstats_theme'] : 'light',
            'S_DISPLAY_LOCATIONS' => $display_locations,
            'S_THEME_OPTIONS' => $theme_options,
        ));
    }
}
