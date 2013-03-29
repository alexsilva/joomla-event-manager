<?php
// No direct access
defined('_JEXEC') or die('Restricted access');


class TableAttendees extends JTable
{
    function __construct( &$db ) {
        parent::__construct('#__pbevents_rsvps', 'id', $db);
    }
}