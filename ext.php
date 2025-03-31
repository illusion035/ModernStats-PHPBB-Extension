<?php

namespace illusion\modernstats;

class ext extends \phpbb\extension\base
{
    public function is_enableable()
    {
        return phpbb_version_compare(PHPBB_VERSION, '3.2.0', '>=');
    }
}