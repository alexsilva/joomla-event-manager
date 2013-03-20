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
	public static function formatDate($date, $datetime=true) {
		$config =& JFactory::getConfig();
		$_datetime = new DateTime($date, new DateTimeZone($config->get('offset')));
		if ($datetime) {
			$date = date_format($_datetime, JText::_("COM_PBEVENTS_DATETIME_FORMAT"));
		} else {
			$date = date_format($_datetime, JText::_("COM_PBEVENTS_DATE_FORMAT"));
		}
		return $date;
	}
	
	/**
	 * format dates
	 */
	public static function formatDateHours($date, $hstart, $hend, $henabel=false) {
		$_date = PBEventsHelper::formatDate($date, false);
		if ($henabel)
		{
			$result = JText::sprintf("COM_PBEBENTS_EVENT_DATES_HOURS_FORMAT", $_date, $hstart, $hend);
		} 
		else 
		{
			$result = $_date;
		}
		return $result;
		
	}
}


?>
