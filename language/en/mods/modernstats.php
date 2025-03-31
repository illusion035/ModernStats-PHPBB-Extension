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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
    'MODERN_STATS' => 'Modern Statistics',
    'MODERN_STATS_EXPLAIN' => 'View modern statistics for your forum',
    'MODERN_STATS_TOTAL_POSTS' => 'Total Posts',
    'MODERN_STATS_TOTAL_TOPICS' => 'Total Topics',
    'MODERN_STATS_TOTAL_USERS' => 'Total Users',
    'MODERN_STATS_NEWEST_USER' => 'Newest User',
    'MODERN_STATS_RECORD_USERS' => 'Most Users Ever Online',
    'MODERN_STATS_FORUM_STATS' => 'Forum Statistics',
    'MODERN_STATS_WHO_IS_ONLINE' => 'Who is online',
    'MODERN_STATS_TOTAL_MEMBERS' => 'Total Members',
    'MODERN_STATS_OUR_NEWEST_MEMBER' => 'Our newest member',
    'MODERN_STATS_TOTAL_ATTACHMENTS' => 'Total Attachments',
    'MODERN_STATS_TOTAL_VIEWS' => 'Total Views',
    'MODERN_STATS_TOP_POSTER' => 'Most Active User',
    'MODERN_STATS_POSTS' => 'Posts',
    'MODERN_STATS_POSTS_PER_DAY' => 'Posts per Day',
    'MODERN_STATS_ACTIVE_USERS_24H' => 'Active Users (24h)',
    'MODERN_STATS_LATEST_POSTS' => 'Latest Posts',
    'MODERN_STATS_LATEST_USERS' => 'Latest Members',
));