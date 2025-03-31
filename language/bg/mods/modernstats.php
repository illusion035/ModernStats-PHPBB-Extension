<?php
/**
 *
 * Modern Statistics Extension
 *
 * @copyright (c) 2024 Illusion
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
    'MODERN_STATS' => 'Модерна статистика',
    'MODERN_STATS_EXPLAIN' => 'Преглед на модерна статистика за вашия форум',
    'MODERN_STATS_TOTAL_POSTS' => 'Общо съобщения',
    'MODERN_STATS_TOTAL_TOPICS' => 'Общо теми',
    'MODERN_STATS_TOTAL_USERS' => 'Общо потребители',
    'MODERN_STATS_NEWEST_USER' => 'Най-нов потребител',
    'MODERN_STATS_RECORD_USERS' => 'Най-много потребители онлайн',
    'MODERN_STATS_FORUM_STATS' => 'Статистика на форума',
    'MODERN_STATS_WHO_IS_ONLINE' => 'Кой е онлайн',
    'MODERN_STATS_TOTAL_MEMBERS' => 'Общо членове',
    'MODERN_STATS_OUR_NEWEST_MEMBER' => 'Нашият най-нов член',
    'MODERN_STATS_TOTAL_ATTACHMENTS' => 'Общо прикачени файлове',
    'MODERN_STATS_TOTAL_VIEWS' => 'Общо прегледи',
    'MODERN_STATS_TOP_POSTER' => 'Най-активен потребител',
    'MODERN_STATS_POSTS' => 'Съобщения',
    'MODERN_STATS_POSTS_PER_DAY' => 'Съобщения на ден',
    'MODERN_STATS_ACTIVE_USERS_24H' => 'Активни потребители (24ч)',
    'MODERN_STATS_LATEST_POSTS' => 'Последни съобщения',
    'MODERN_STATS_LATEST_USERS' => 'Последни регистрирани',
));