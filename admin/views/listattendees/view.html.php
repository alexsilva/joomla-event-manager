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
		
		$params =& JComponentHelper::getParams("com_pbevents");
		$limit_list = $params->get("alist_limit", 6);
		
		$input->set("limit", $input->get("limit", $limit_list));
		
		$this->pagination = $this->get("Pagination");
		$this->attendees = $this->get("Items");
		$this->event = $this->get("Event");
		
		// id da seção
		$this->event_id = $input->get("id",0,"integer");
		$this->addToolbar();
		
		parent::display($tpl);
	}
	
	private function addToolbar() {
		JToolBarHelper::title(JText::_('COM_PBEVENTS_EVENTS_MANAGER').' '.JText::_('COM_PBEVENTS_ATTENDEES'),'generic.png');
		
		JToolBarHelper::back(JText::_("COM_PBEVENTS_VIEW_GO_TO_LIST_EVENTS"), JRoute::_("index.php?option=com_pbevents&task=listevents"));
		
		JToolBarHelper::divider();
		JToolBarHelper::cancel("listevents");
		
		$canDo = PBEventsHelper::getActions();
		
		// definindo níves de permissões.
		if ($canDo->get('core.delete'))
		{
			JToolBarHelper::divider();
			JToolBarHelper::deleteList(JText::_("COM_PBEVENTS_VIEW_DELETE_ATTENDEES_WARNING"), 'attendees.delete');
		}
	}
}


