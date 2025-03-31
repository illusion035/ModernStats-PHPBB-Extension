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

// Include phpBB constants
global $table_prefix;
if (!defined('USERS_TABLE')) {
    define('USERS_TABLE', $table_prefix . 'users');
}
if (!defined('GROUPS_TABLE')) {
    define('GROUPS_TABLE', $table_prefix . 'groups');
}
if (!defined('TOPICS_TABLE')) {
    define('TOPICS_TABLE', $table_prefix . 'topics');
}
if (!defined('POSTS_TABLE')) {
    define('POSTS_TABLE', $table_prefix . 'posts');
}
if (!defined('SESSIONS_TABLE')) {
    define('SESSIONS_TABLE', $table_prefix . 'sessions');
}
if (!defined('USER_IGNORE')) {
    define('USER_IGNORE', 2);
}
if (!defined('ANONYMOUS')) {
    define('ANONYMOUS', 1);
}
if (!defined('FORUMS_TABLE')) {
    define('FORUMS_TABLE', $table_prefix . 'forums');
}
if (!defined('TOPIC_LOCKED')) {
    define('TOPIC_LOCKED', 1);
}
if (!defined('ITEM_APPROVED')) {
    define('ITEM_APPROVED', 1);
}
if (!defined('FORUM_POST')) {
    define('FORUM_POST', 1);
}

/**
 * Generate user avatar HTML
 */
function phpbb_get_user_avatar($row)
{
    if (empty($row['user_avatar'])) {
        return '';
    }

    $avatar_img = '';
    $avatar_width = (int) $row['user_avatar_width'];
    $avatar_height = (int) $row['user_avatar_height'];

    if ($avatar_width <= 0 || $avatar_height <= 0) {
        $avatar_width = 40;
        $avatar_height = 40;
    }

    switch ($row['user_avatar_type']) {
        case 'avatar.driver.upload':
            $avatar_img = generate_board_url() . '/download/file.php?avatar=' . $row['user_avatar'];
            break;
        case 'avatar.driver.remote':
            $avatar_img = $row['user_avatar'];
            break;
        case 'avatar.driver.local':
            $avatar_img = generate_board_url() . '/images/avatars/gallery/' . $row['user_avatar'];
            break;
        case 'avatar.driver.gravatar':
            $avatar_img = 'https://secure.gravatar.com/avatar/' . md5(strtolower(trim($row['user_email']))) . '?s=' . $avatar_width;
            break;
    }

    return '<img src="' . $avatar_img . '" width="' . $avatar_width . '" height="' . $avatar_height . '" alt="' . $row['username'] . '" class="user-avatar" />';
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
        $this->template->assign_vars(array(
            'S_MODERNSTATS_ENABLED' => true,
            'MODERNSTATS_DISPLAY_LOCATION' => $this->config['modernstats_display_location'],
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
            // Get total members
            $sql = 'SELECT COUNT(user_id) as total_members FROM ' . USERS_TABLE . ' WHERE user_type != ' . USER_IGNORE;
            $result = $this->db->sql_query($sql);
            $total_members = $this->db->sql_fetchfield('total_members');
            $this->db->sql_freeresult($result);

            // Get total topics
            $sql = 'SELECT COUNT(topic_id) as total_topics FROM ' . TOPICS_TABLE;
            $result = $this->db->sql_query($sql);
            $total_topics = $this->db->sql_fetchfield('total_topics');
            $this->db->sql_freeresult($result);

            // Get total posts
            $sql = 'SELECT COUNT(post_id) as total_posts FROM ' . POSTS_TABLE;
            $result = $this->db->sql_query($sql);
            $total_posts = $this->db->sql_fetchfield('total_posts');
            $this->db->sql_freeresult($result);

            // Get top poster with group color
            $sql = 'SELECT u.username, u.user_posts, g.group_colour 
                    FROM ' . USERS_TABLE . ' u
                    LEFT JOIN ' . GROUPS_TABLE . ' g ON (u.group_id = g.group_id)
                    WHERE u.user_type != ' . USER_IGNORE . '
                    ORDER BY u.user_posts DESC';
            $result = $this->db->sql_query_limit($sql, 1);
            $top_poster = $this->db->sql_fetchrow($result);
            $this->db->sql_freeresult($result);

            // Get total topic views
            $sql = 'SELECT SUM(topic_views) as total_views FROM ' . TOPICS_TABLE;
            $result = $this->db->sql_query($sql);
            $total_views = $this->db->sql_fetchfield('total_views');
            $this->db->sql_freeresult($result);

            // Get newest user with group color
            $sql = 'SELECT u.username, g.group_colour 
                    FROM ' . USERS_TABLE . ' u
                    LEFT JOIN ' . GROUPS_TABLE . ' g ON (u.group_id = g.group_id)
                    WHERE u.user_type != ' . USER_IGNORE . '
                    ORDER BY u.user_regdate DESC';
            $result = $this->db->sql_query_limit($sql, 1);
            $newest_user = $this->db->sql_fetchrow($result);
            $this->db->sql_freeresult($result);

            // Calculate posts per day
            $sql = 'SELECT MIN(post_time) as first_post FROM ' . POSTS_TABLE;
            $result = $this->db->sql_query($sql);
            $first_post_time = (int) $this->db->sql_fetchfield('first_post');
            $this->db->sql_freeresult($result);

            $days_since_first_post = max(1, (time() - $first_post_time) / 86400);
            $posts_per_day = round($total_posts / $days_since_first_post, 1);

            // Get active users in last 24 hours
            $sql = 'SELECT COUNT(DISTINCT session_user_id) as active_users 
                    FROM ' . SESSIONS_TABLE . '
                    WHERE session_time > ' . (time() - 86400) . '
                    AND session_user_id <> ' . ANONYMOUS;
            $result = $this->db->sql_query($sql);
            $active_users = (int) $this->db->sql_fetchfield('active_users');
            $this->db->sql_freeresult($result);

            $active_percentage = $total_members > 0 ? round(($active_users / $total_members) * 100, 1) : 0;

            // Format usernames with colors
            $top_poster_username = $top_poster['group_colour'] ? '<span style="color: #' . $top_poster['group_colour'] . '">' . $top_poster['username'] . '</span>' : $top_poster['username'];
            $newest_user_username = $newest_user['group_colour'] ? '<span style="color: #' . $newest_user['group_colour'] . '">' . $newest_user['username'] . '</span>' : $newest_user['username'];

            // Assign template variables for statistics
            $this->template->assign_vars(array(
                'S_SHOW_STATISTICS' => true,
                'MODERN_STATS_TOTAL_MEMBERS' => $total_members,
                'MODERN_STATS_TOTAL_TOPICS' => $total_topics,
                'MODERN_STATS_TOTAL_POSTS' => $total_posts,
                'MODERN_STATS_TOP_POSTER' => $top_poster_username,
                'MODERN_STATS_TOP_POSTS' => $top_poster['user_posts'],
                'MODERN_STATS_TOTAL_VIEWS' => $total_views,
                'MODERN_STATS_NEWEST_USER' => $newest_user_username,
                'MODERN_STATS_POSTS_PER_DAY' => $posts_per_day,
                'MODERN_STATS_ACTIVE_PERCENTAGE' => $active_percentage,
            ));
        }

        // Load latest posts if enabled
        if ($this->config['modernstats_show_latest_posts']) {
            $sql = 'SELECT p.*, t.*, f.forum_name, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height
                    FROM ' . POSTS_TABLE . ' p
                    LEFT JOIN ' . TOPICS_TABLE . ' t ON (p.topic_id = t.topic_id)
                    LEFT JOIN ' . FORUMS_TABLE . ' f ON (p.forum_id = f.forum_id)
                    LEFT JOIN ' . USERS_TABLE . ' u ON (p.poster_id = u.user_id)
                    WHERE p.post_visibility = ' . ITEM_APPROVED . '
                    AND f.forum_type = ' . FORUM_POST . '
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
                    'S_TOPIC_LOCKED' => (bool) ($row['topic_status'] == TOPIC_LOCKED),
                    'AVATAR' => phpbb_get_user_avatar($row),
                    'U_LAST_POST' => append_sid("{$this->root_path}viewtopic.{$this->php_ext}", "p=" . $row['post_id'] . "#p" . $row['post_id'])
                ));
            }
            $this->db->sql_freeresult($result);

            $this->template->assign_var('S_SHOW_LATEST_POSTS', true);
        }

        // Load latest users if enabled
        if ($this->config['modernstats_show_latest_users']) {
            $sql = 'SELECT u.*, g.group_colour
                    FROM ' . USERS_TABLE . ' u
                    LEFT JOIN ' . GROUPS_TABLE . ' g ON (u.group_id = g.group_id)
                    WHERE u.user_type != ' . USER_IGNORE . '
                    ORDER BY u.user_regdate DESC';
            $result = $this->db->sql_query_limit($sql, $this->config['modernstats_latest_users_limit']);

            while ($row = $this->db->sql_fetchrow($result)) {
                $this->template->assign_block_vars('latest_users', array(
                    'USERNAME' => $row['username'],
                    'USER_COLOUR' => $row['group_colour'],
                    'JOINED' => $this->user->format_date($row['user_regdate']),
                    'POSTS' => $row['user_posts'],
                    'AVATAR' => phpbb_get_user_avatar($row),
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
                FROM ' . FORUMS_TABLE . '
                WHERE forum_type = ' . FORUM_POST;
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
}