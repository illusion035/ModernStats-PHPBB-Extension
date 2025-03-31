<?php
/**
 *
 * Modern Statistics Extension
 *
 * @copyright (c) 2025 illusion
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

if (!defined('IN_PHPBB')) {
    exit;
}

if (empty($lang) || !is_array($lang)) {
    $lang = array();
}

$lang = array_merge($lang, array(
    'ACP_MODERNSTATS_TITLE' => 'Modern Statistics',
    'ACP_MODERNSTATS_SETTINGS' => 'Settings',
    'ACP_MODERNSTATS_SETTINGS_SAVED' => 'Modern Statistics settings have been saved successfully.',

    'ACP_MODERNSTATS_SHOW_LATEST_POSTS' => 'Show latest posts',
    'ACP_MODERNSTATS_SHOW_LATEST_POSTS_EXPLAIN' => 'Enable or disable the display of latest posts section.',

    'ACP_MODERNSTATS_LATEST_POSTS_LIMIT' => 'Number of latest posts to display',
    'ACP_MODERNSTATS_LATEST_POSTS_LIMIT_EXPLAIN' => 'Set how many latest posts to display (1-50).',

    'ACP_MODERNSTATS_SHOW_LATEST_USERS' => 'Show latest users',
    'ACP_MODERNSTATS_SHOW_LATEST_USERS_EXPLAIN' => 'Enable or disable the display of latest registered users section.',

    'ACP_MODERNSTATS_LATEST_USERS_LIMIT' => 'Number of latest users to display',
    'ACP_MODERNSTATS_LATEST_USERS_LIMIT_EXPLAIN' => 'Set how many latest users to display (1-50).',

    'ACP_MODERNSTATS_SHOW_STATISTICS' => 'Show statistics',
    'ACP_MODERNSTATS_SHOW_STATISTICS_EXPLAIN' => 'Enable or disable the display of general statistics section.',

    'ACP_MODERNSTATS_DISPLAY_LOCATION' => 'Display location',
    'ACP_MODERNSTATS_LOCATION_AFTER_FORUMLIST' => 'After forum list',
    'ACP_MODERNSTATS_LOCATION_BEFORE_MARKFORUMS' => 'Before mark forums',
    'ACP_MODERNSTATS_LOCATION_AFTER_MARKFORUMS' => 'After mark forums',
));