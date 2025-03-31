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
    'ACP_MODERNSTATS_TITLE' => 'Модерна статистика',
    'ACP_MODERNSTATS_SETTINGS' => 'Настройки',
    'ACP_MODERNSTATS_SETTINGS_SAVED' => 'Настройките на Модерна статистика бяха запазени успешно.',

    'ACP_MODERNSTATS_SHOW_LATEST_POSTS' => 'Показване на последни съобщения',
    'ACP_MODERNSTATS_SHOW_LATEST_POSTS_EXPLAIN' => 'Включване или изключване на показването на секцията с последни съобщения.',

    'ACP_MODERNSTATS_LATEST_POSTS_LIMIT' => 'Брой последни съобщения за показване',
    'ACP_MODERNSTATS_LATEST_POSTS_LIMIT_EXPLAIN' => 'Задайте колко последни съобщения да се показват (1-50).',

    'ACP_MODERNSTATS_SHOW_LATEST_USERS' => 'Показване на последни потребители',
    'ACP_MODERNSTATS_SHOW_LATEST_USERS_EXPLAIN' => 'Включване или изключване на показването на секцията с последно регистрирани потребители.',

    'ACP_MODERNSTATS_LATEST_USERS_LIMIT' => 'Брой последни потребители за показване',
    'ACP_MODERNSTATS_LATEST_USERS_LIMIT_EXPLAIN' => 'Задайте колко последни потребители да се показват (1-50).',

    'ACP_MODERNSTATS_SHOW_STATISTICS' => 'Показване на статистика',
    'ACP_MODERNSTATS_SHOW_STATISTICS_EXPLAIN' => 'Включване или изключване на показването на секцията с обща статистика.',

    'ACP_MODERNSTATS_DISPLAY_LOCATION' => 'Местоположение на показване',
    'ACP_MODERNSTATS_LOCATION_AFTER_FORUMLIST' => 'След списъка с форуми',
    'ACP_MODERNSTATS_LOCATION_BEFORE_MARKFORUMS' => 'Преди бутона за маркиране на форуми',
    'ACP_MODERNSTATS_LOCATION_AFTER_MARKFORUMS' => 'След бутона за маркиране на форуми',
));