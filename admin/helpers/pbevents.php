<?php
/**
 * @module		com_pbevents
 * @script		pbevents.php
 * @author-name Alex Sandro
 * @copyright	Copyright (C) 2012
 * @license		GNU/GPL, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */

// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * PBEvents component helper.
 */
abstract class PBEventsHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu)
	{	
		JHtmlSidebar::addEntry(
			JText::_('COM_PBEVENTS_DASHBOARD'), 
			'index.php?option=com_pbevents',
			$submenu=='display'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_PBEVENTS_ADMIN_LIST_EVENTS'), 
			'index.php?option=com_pbevents&view=listevents', 
			$submenu == 'listevents'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_PBEVENTS_SUBMENU_CATEGORIES'), 
			'index.php?option=com_categories&view=categories&extension=com_pbevents', 
			$submenu == 'categories'
		);
	}
	
	/**
	 * format money value.
	 */
	public static function formatMoney($value) {
		$value = $value != null ? $value: 0.00;
		
		$decimals = JText::_("COM_PBEBENTS_MONEY_DECIMALS");
		$thousands = JText::_("COM_PBEBENTS_MONEY_THOUSANDS");
		
		if (strlen($decimals) == 1 && strlen($thousands) == 1)
		{
			$value = number_format($value, 2, $decimals, $thousands);
		}
		return $value;
	}
	
	/**
	 * format money float.
	 */
	public static function formatFloatMoney($value) {
		$value = $value != null ? $value: "0.00";
		
		$thousands = JText::_("COM_PBEBENTS_MONEY_THOUSANDS");
		$decimals = JText::_("COM_PBEBENTS_MONEY_DECIMALS");
		
		$value = str_replace($thousands, "", $value);
		$value = str_replace($decimals, ".", $value);
		return $value;
	}
	
	/**
	 * format dates
	 */
	public static function formatDate($date, $isDateTime=true) {
		$datetime = new DateTime($date, new DateTimeZone(JFactory::getConfig()->get('offset')));
		if ($isDateTime) {
			$date = $datetime->format(JText::_("COM_PBEVENTS_DATETIME_FORMAT"));
		} else {
			$date = $datetime->format(JText::_("COM_PBEVENTS_DATE_FORMAT"));
		}
		return $date;
	}
	/**
	 * format dates
	 */
	public static function formatDateHours($date, array $hours, $henable=false) {
		$_hours = $henable ? implode(" | ", $hours): "";
		$_date = PBEventsHelper::formatDate($date, false);
		return sprintf("%s %s", $_date, $_hours);
	}
	
	/**
	 * date in database format: Y-m-d
	 */
	public static function formatAtomDate($value) {
		$date = date_create($value, new DateTimeZone(JFactory::getConfig()->get('offset')));
		return $date->format("Y-m-d");
	}
	
	/**
	 * hour only
	 */
	public static function getHourOnly( $date ) {
		$datetime = new DateTime($date, new DateTimeZone(JFactory::getConfig()->get('offset')));
		return $datetime->format(JText::_("COM_PBEVENTS_HOUR_FORMAT"));
	}
	
	/**
	 * date only
	 */
	public static function getDateOnly( $date ) {
		$datetime = new DateTime($date, new DateTimeZone(JFactory::getConfig()->get('offset')));
		return $datetime->format(JText::_("COM_PBEVENTS_DATE_FORMAT"));
	}
	
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		
		$user	= JFactory::getUser();
		$result	= new JObject;
 		
		if (empty($messageId)) {
			$assetName = 'com_pbevents';
		}
		else {
			$assetName = 'com_pbevents.message.'.(int) $messageId;
		}
 		
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);
 		
		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}
 		
		return $result;
	}
}
