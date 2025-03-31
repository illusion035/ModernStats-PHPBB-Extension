<?php

class ModernStatsListener
{
    static public function getSubscribedEvents()
    {
        return array(
            'core.page_header' => 'load_language_on_setup',
            'core.index_modify_page_title' => 'prepare_stats_data',
            'core.index_modify_markforums_before' => 'display_stats_before_markforums',
            'core.index_modify_markforums_after' => 'display_stats_after_markforums',
            'core.index_modify_forum_rows_before' => 'display_stats_before_forumlist',
            'core.index_modify_forum_rows_after' => 'display_stats_after_forumlist',
        );
    }

    public function load_language_on_setup($event)
    {
        $this->language->add_lang('modernstats', 'illusion/modernstats');
    }

    public function prepare_stats_data($event)
    {
        // ... existing stats preparation code ...
    }

    public function display_stats_before_markforums($event)
    {
        if ($this->config['modernstats_display_location'] === 'before_markforums') {
            $this->display_stats();
        }
    }

    public function display_stats_after_markforums($event)
    {
        if ($this->config['modernstats_display_location'] === 'after_markforums') {
            $this->display_stats();
        }
    }

    public function display_stats_before_forumlist($event)
    {
        if ($this->config['modernstats_display_location'] === 'before_forumlist') {
            $this->display_stats();
        }
    }

    public function display_stats_after_forumlist($event)
    {
        if ($this->config['modernstats_display_location'] === 'after_forumlist') {
            $this->display_stats();
        }
    }

    private function display_stats()
    {
        $this->template->assign_vars(array(
            'S_MODERNSTATS_ENABLED' => true,
            'S_SHOW_STATISTICS' => $this->config['modernstats_show_statistics'],
            'S_SHOW_LATEST_POSTS' => $this->config['modernstats_show_latest_posts'],
            'S_SHOW_LATEST_USERS' => $this->config['modernstats_show_latest_users'],
            'MODERNSTATS_DISPLAY_LOCATION' => $this->config['modernstats_display_location'],
        ));
    }
}