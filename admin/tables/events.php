<?php
// No direct access
defined('_JEXEC') or die('Restricted access');


class TableEvents extends JTable
{
    function __construct( &$db ) {
        parent::__construct('#__pbevents_events', 'id', $db);
    }
}