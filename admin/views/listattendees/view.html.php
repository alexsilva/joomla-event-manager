<?php
/**
 * @package     ...
 * @subpackage  com_pbevents
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of articles.
 *
 * @package     ...
 * @subpackage  com_pbevents
 * @since       ...
 */
class PBEventsViewListAttendees extends JViewLegacy 
{
	public function display($tpl = null) {
		$input =& JFactory::getApplication()->input;
		
		$this->pagination = $this->get("Pagination");
		$this->item = $this->get("Items");
		
		$this->event_id = $input->get("id",0,"integer");
		
		parent::display($tpl);
	}
}