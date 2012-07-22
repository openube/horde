<?php
/**
 * Interface to the Horde_Content tagger
 *
 * Copyright 2009-2012 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author Michael J Rubinsky <mrubinsk@horde.org>
 * @license  http://www.horde.org/licenses/gpl GPL
 * @package Nag
 */
class Nag_Tagger extends Horde_Core_Tagger
{
    protected $_app = 'nag';
    protected $_types = 'task';


    /**
     * Searches for resources that are tagged with all of the requested tags.
     *
     * @param array $tags    Either a tag_id, tag_name or an array.
     * @param array $filter  Array of filter parameters.
     *      - user: (array) - only include objects owned by these users.
     *      - list (array) - restrict to tasks contained in these task lists.
     *
     * @return  A hash of 'calendars' and 'events' that each contain an array
     *          of calendar_ids and event_uids respectively.
     */
    public function search($tags, $filter = array())
    {
        $args = array();

        // These filters are mutually exclusive
        if (array_key_exists('user', $filter)) {
            // semi-hack to see if we are querying for a system-owned share -
            // will need to get the list of all system owned shares and query
            // using a calendar filter instead of a user filter.
            if (empty($filter['user'])) {
                // @TODO: No way to get only the system shares the current
                // user can see?
                $calendars = $GLOBALS['injector']->getInstance('Nag_Shares')
                    ->listSystemShares();
                $args['listId'] = array();
                foreach ($calendars as $name => $share) {
                    if ($share->hasPermission($GLOBALS['registry']->getAuth(), Horde_Perms::READ)) {
                        $args['listId'][] = $name;
                    }
                }
            } else {
                // Items owned by specific user(s)
                $args['userId'] = $filter['user'];
            }
        } elseif (!empty($filter['list'])) {
            // Only events located in specific calendar(s)
            if (!is_array($filter['list'])) {
                $filter['list'] = array($filter['list']);
            }
            $args['listId'] = $filter['list'];
        }

        // Add the tags to the search
        $args['tagId'] = $GLOBALS['injector']->getInstance('Content_Tagger')
            ->getTagIds($tags);

        $results = array();
        $args['typeId'] = 'task';

        return array_values($GLOBALS['injector']->getInstance('Content_Tagger')->getObjects($args));
    }

}