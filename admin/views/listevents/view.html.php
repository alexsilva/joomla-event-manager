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
class PBEventsViewListEvents extends JViewLegacy
{
	public function display($tpl = null) {
		JToolBarHelper::title(JText::_('COM_PBEVENTS_EVENTS_MANAGER').' '.JText::_('COM_PBEVENTS_ADMIN_LIST_EVENTS'), 'generic.png');
		
		$input =& JFactory::getApplication()->input;
		
		// component configs
		$params =& JComponentHelper::getParams("com_pbevents");
		$list_limit = $params->get("alist_limit", 6);
		
		// limit configurável.
		$input->set("limit", $input->get("limit", $list_limit));
		
		$this->events     = $this->get("Items");
		$this->pagination = $this->get("Pagination");
		$this->state	  = $this->get('State');
		
		$this->addToolbar();
		
		$this->sidebar = JHtmlSidebar::render();
		
		parent::display($tpl);
	}
	/*
	 * format all dates
	 */
	public static function makeDateOptions(array &$items)
	{
		$_items = array();
		foreach ($items as $item)
		{
			$_items[] = PBEventsHelper::formatDateHours($item->date,
						array($item->hstart, $item->hend), 
						$item->henable);
		}
		return $_items;
	}
	private function addToolbar() {
		$canDo = PBEventsHelper::getActions();
		
		// agindo conforme as permissões
		if ($canDo->get('core.create'))
		{
			JToolBarHelper::addNew('event.add');
		}
		if ($canDo->get('core.edit'))
		{
			JToolBarHelper::editList("event.edit");
		}
		if ($canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList(JText::_("COM_PBEBENTS_WARNNING_ONDELETE"), "events.delete");
		}
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences("com_pbevents");
		}
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', 
			JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
		);
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_CATEGORY'),
			'filter_category_id',
			JHtml::_('select.options', 
			JHtml::_('category.options', 'com_pbevents'), 'value', 'text', $this->state->get('filter.category_id'))
		);
		
	}
}






?>