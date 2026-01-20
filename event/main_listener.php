<?php

/**
 *
 * Modern Statistics Extension
 *
 * @copyright (c) 2025 illusion
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace illusion\modernstats\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

if (!defined('IN_PHPBB')) {
    exit;
}

class main_listener implements EventSubscriberInterface
{
    /** @var \phpbb\language\language */
    protected $language;

    /** @var \phpbb\template\template */
    protected $template;

    /** @var \phpbb\db\driver\driver_interface */
    protected $db;

    /** @var \phpbb\user */
    protected $user;

    /** @var string */
    protected $root_path;

    /** @var string */
    protected $php_ext;

    /** @var \phpbb\auth\auth */
    protected $auth;

    /** @var \phpbb\config\config */
    protected $config;

    /**
     * Constructor
     *
     * @param \phpbb\language\language $language Language object
     * @param \phpbb\template\template $template Template object
     * @param \phpbb\db\driver\driver_interface $db Database object
     * @param \phpbb\user $user User object
     * @param string $root_path phpBB root path
     * @param string $php_ext PHP file extension
     * @param \phpbb\auth\auth $auth Auth object
     * @param \phpbb\config\config $config Config object
     */
    public function __construct(
        \phpbb\language\language $language,
        \phpbb\template\template $template,
        \phpbb\db\driver\driver_interface $db,
        \phpbb\user $user,
        $root_path,
        $php_ext,
        \phpbb\auth\auth $auth,
        \phpbb\config\config $config
    ) {
        $this->language = $language;
        $this->template = $template;
        $this->db = $db;
        $this->user = $user;
        $this->root_path = $root_path;
        $this->php_ext = $php_ext;
        $this->auth = $auth;
        $this->config = $config;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'core.page_header' => 'load_language',
            'core.index_modify_page_title' => 'load_stats',
            'core.index_modify_markforums_before' => array('load_stats', 1),
            'core.index_modify_markforums_after' => array('load_stats', 1),
        );
    }

    public function load_language()
    {
        $this->language->add_lang('mods/modernstats', 'illusion/modernstats');

        // Get theme setting, default to 'light'
        $theme = isset($this->config['modernstats_theme']) ? $this->config['modernstats_theme'] : 'light';
        $theme_class = 'theme-' . $theme;

        // Only enable the wrapper if at least one feature is enabled
        $is_enabled = $this->config['modernstats_show_statistics']
            || $this->config['modernstats_show_latest_posts']
            || $this->config['modernstats_show_latest_users'];

        $this->template->assign_vars(array(
            'S_MODERNSTATS_ENABLED' => $is_enabled,
            'MODERNSTATS_DISPLAY_LOCATION' => $this->config['modernstats_display_location'],
            'MODERNSTATS_THEME_CLASS' => $theme_class,
        ));
    }

    public function load_stats()
    {
        // Only proceed if at least one section is enabled
        if (
            !$this->config['modernstats_show_statistics'] &&
            !$this->config['modernstats_show_latest_posts'] &&
            !$this->config['modernstats_show_latest_users']
        ) {
            return;
        }

        // Load general statistics if enabled
        if ($this->config['modernstats_show_statistics']) {
            // Use phpBB's built-in config values instead of custom queries (performance optimization)
            $total_members = $this->config['num_users'];
            $total_topics = $this->config['num_topics'];
            $total_posts = $this->config['num_posts'];

            // Get top poster with group color (excluding selected groups) - custom query needed
            $excluded_groups = isset($this->config['modernstats_excluded_groups']) ? $this->config['modernstats_excluded_groups'] : '';
            $excluded_groups_ary = array_filter(explode(',', $excluded_groups));

            $sql = 'SELECT u.username, u.user_posts, g.group_colour
					FROM ' . \USERS_TABLE . ' u
					LEFT JOIN ' . \GROUPS_TABLE . ' g ON (u.group_id = g.group_id)
					WHERE u.user_type != ' . \USER_IGNORE;

            if (!empty($excluded_groups_ary)) {
                $sql .= ' AND ' . $this->db->sql_in_set('u.group_id', $excluded_groups_ary, true);
            }

            $sql .= ' ORDER BY u.user_posts DESC';
            $result = $this->db->sql_query_limit($sql, 1);
            $top_poster = $this->db->sql_fetchrow($result);
            $this->db->sql_freeresult($result);

            // Get total topic views - custom query needed
            $sql = 'SELECT SUM(topic_views) as total_views FROM ' . \TOPICS_TABLE;
            $result = $this->db->sql_query($sql);
            $total_views = $this->db->sql_fetchfield('total_views');
            $this->db->sql_freeresult($result);

            // Use phpBB's newest user from config
            $newest_user_id = $this->config['newest_user_id'];
            $newest_username = $this->config['newest_username'];
            $newest_user_colour = $this->config['newest_user_colour'];

            // Calculate posts per day using config record_time as reference
            $board_startdate = $this->config['board_startdate'];
            $days_since_start = max(1, (time() - $board_startdate) / 86400);
            $posts_per_day = round($total_posts / $days_since_start, 1);

            // Get active users in last 24 hours - custom query needed
            $sql = 'SELECT COUNT(DISTINCT session_user_id) as active_users
					FROM ' . \SESSIONS_TABLE . '
					WHERE session_time > ' . (time() - 86400) . '
					AND session_user_id <> ' . \ANONYMOUS;
            $result = $this->db->sql_query($sql);
            $active_users = (int) $this->db->sql_fetchfield('active_users');
            $this->db->sql_freeresult($result);

            $active_percentage = $total_members > 0 ? round(($active_users / $total_members) * 100, 1) : 0;

            // Format usernames with colors (with null checks)
            $top_poster_username = '-';
            $top_poster_posts = 0;
            if ($top_poster) {
                $top_poster_username = $top_poster['group_colour'] ? '<span style="color: #' . $top_poster['group_colour'] . '">' . $top_poster['username'] . '</span>' : $top_poster['username'];
                $top_poster_posts = $top_poster['user_posts'];
            }

            // Format newest user with color
            $newest_user_formatted = $newest_user_colour ? '<span style="color: #' . $newest_user_colour . '">' . $newest_username . '</span>' : $newest_username;

            // Assign template variables for statistics
            $this->template->assign_vars(array(
                'S_SHOW_STATISTICS' => true,
                'MODERN_STATS_TOTAL_MEMBERS' => $total_members,
                'MODERN_STATS_TOTAL_TOPICS' => $total_topics,
                'MODERN_STATS_TOTAL_POSTS' => $total_posts,
                'MODERN_STATS_TOP_POSTER' => $top_poster_username,
                'MODERN_STATS_TOP_POSTS' => $top_poster_posts,
                'MODERN_STATS_TOTAL_VIEWS' => $total_views,
                'MODERN_STATS_NEWEST_USER' => $newest_user_formatted,
                'MODERN_STATS_POSTS_PER_DAY' => $posts_per_day,
                'MODERN_STATS_ACTIVE_PERCENTAGE' => $active_percentage,
            ));
        }

        // Load latest posts if enabled
        if ($this->config['modernstats_show_latest_posts']) {
            $sql = 'SELECT p.*, t.*, f.forum_name, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, u.user_email
					FROM ' . \POSTS_TABLE . ' p
					LEFT JOIN ' . \TOPICS_TABLE . ' t ON (p.topic_id = t.topic_id)
					LEFT JOIN ' . \FORUMS_TABLE . ' f ON (p.forum_id = f.forum_id)
					LEFT JOIN ' . \USERS_TABLE . ' u ON (p.poster_id = u.user_id)
					WHERE p.post_visibility = ' . \ITEM_APPROVED . '
					AND f.forum_type = ' . \FORUM_POST . '
					AND ' . $this->db->sql_in_set('f.forum_id', $this->get_readable_forums()) . '
					ORDER BY p.post_time DESC';
            $result = $this->db->sql_query_limit($sql, $this->config['modernstats_latest_posts_limit']);

            while ($row = $this->db->sql_fetchrow($result)) {
                $this->template->assign_block_vars('latest_posts', array(
                    'TOPIC_TITLE' => $row['topic_title'],
                    'USERNAME' => $row['username'],
                    'USER_COLOUR' => $row['user_colour'],
                    'FORUM_NAME' => $row['forum_name'],
                    'POST_TIME' => $this->user->format_date($row['post_time']),
                    'S_TOPIC_LOCKED' => (bool) ($row['topic_status'] == \ITEM_LOCKED),
                    'AVATAR' => $this->get_user_avatar($row),
                    'U_LAST_POST' => append_sid("{$this->root_path}viewtopic.{$this->php_ext}", "p=" . $row['post_id'] . "#p" . $row['post_id'])
                ));
            }
            $this->db->sql_freeresult($result);

            $this->template->assign_var('S_SHOW_LATEST_POSTS', true);
        }

        // Load latest users if enabled
        if ($this->config['modernstats_show_latest_users']) {
            $sql = 'SELECT u.*, g.group_colour
					FROM ' . \USERS_TABLE . ' u
					LEFT JOIN ' . \GROUPS_TABLE . ' g ON (u.group_id = g.group_id)
					WHERE u.user_type != ' . \USER_IGNORE . '
					ORDER BY u.user_regdate DESC';
            $result = $this->db->sql_query_limit($sql, $this->config['modernstats_latest_users_limit']);

            while ($row = $this->db->sql_fetchrow($result)) {
                $this->template->assign_block_vars('latest_users', array(
                    'USERNAME' => $row['username'],
                    'USER_COLOUR' => $row['group_colour'],
                    'JOINED' => $this->user->format_date($row['user_regdate']),
                    'POSTS' => $row['user_posts'],
                    'AVATAR' => $this->get_user_avatar($row),
                    'U_PROFILE' => append_sid("{$this->root_path}memberlist.{$this->php_ext}", 'mode=viewprofile&u=' . $row['user_id'])
                ));
            }
            $this->db->sql_freeresult($result);

            $this->template->assign_var('S_SHOW_LATEST_USERS', true);
        }
    }

    /**
     * Get array of readable forum IDs for the current user
     *
     * @return array Array of forum IDs
     */
    private function get_readable_forums()
    {
        $forum_ary = array();
        $sql = 'SELECT forum_id
				FROM ' . \FORUMS_TABLE . '
				WHERE forum_type = ' . \FORUM_POST;
        $result = $this->db->sql_query($sql);

        while ($row = $this->db->sql_fetchrow($result)) {
            if ($this->auth->acl_get('f_read', $row['forum_id'])) {
                $forum_ary[] = (int) $row['forum_id'];
            }
        }
        $this->db->sql_freeresult($result);

        if (empty($forum_ary)) {
            $forum_ary[] = 0;
        }

        return $forum_ary;
    }

    /**
     * Generate user avatar HTML using phpBB's internal function
     *
     * @param array $row User data row
     * @return string Avatar HTML
     */
    private function get_user_avatar($row)
    {
        if (!function_exists('phpbb_get_avatar')) {
            include($this->root_path . 'includes/functions_display.' . $this->php_ext);
        }

        // Map user_* keys to the expected format for phpbb_get_avatar
        $avatar_row = array(
            'avatar' => isset($row['user_avatar']) ? $row['user_avatar'] : '',
            'avatar_type' => isset($row['user_avatar_type']) ? $row['user_avatar_type'] : '',
            'avatar_width' => isset($row['user_avatar_width']) ? $row['user_avatar_width'] : '',
            'avatar_height' => isset($row['user_avatar_height']) ? $row['user_avatar_height'] : '',
        );

        $avatar = phpbb_get_avatar($avatar_row, 'USER_AVATAR', false);

        if (!empty($avatar)) {
            // Add custom class to the avatar
            return str_replace('<img', '<img class="user-avatar"', $avatar);
        }

        return '';
    }
}
