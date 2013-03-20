<?php

/**
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

class PBEventsModelPBEvents extends JModelList
{	
	// nome da tabela raiz de dados
	public $_table = "#__pbevents_events";
	public $_where = "publish = 1";
	
	// lista de nomes das tableas relacionadas
	public $_related = array(
		array(
			"reference" => "dates",
			"table" => "#__pbevents_events_dates",
			"foreingkey" => "event_id"),
		
		array(
			"reference" => "rsvps",
			"table" => "#__pbevents_rsvps",
			"foreingkey" => "event_id")
	);
	
	// retorna os ultimos registros adicionados aos relaciondos
	public function getLastEntries($limit=10) {
		$db = &JFactory::getDbo();
		$items = new JObject();
		
		foreach($this->_related as $object) {
			$query = $db->getQuery(true);
			
			$query->select(sprintf('%s.*,%s.title', $object["table"], $this->_table));
			$query->from($object["table"])->join('left', sprintf('%s on %s.event_id = %s.id', $this->_table, $object["table"], $this->_table));
			
			$query->order(sprintf('%s.id DESC', $object["table"]));
			$query->limit( $limit );
			
			$items->$object["reference"] = array_slice($db->setQuery($query)->loadObjectList(), 0, $limit);
		}
		return $items;
	}
	// retorna registro dentro do intervalo de dias.
	public function getDateUpComing($days = 10) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('#__pbevents_events.*, #__pbevents_events_dates.*')->from('#__pbevents_events_dates');
		$query->join("left", "#__pbevents_events on #__pbevents_events_dates.event_id = #__pbevents_events.id");
		
		$dateTimeZone = new DateTimeZone(JFactory::getConfig()->get('offset'));
		
		$now = date_create("now", $dateTimeZone);
		$_now = $now->format(DATE_ATOM);
		
		$nextdate = mktime(
				$now->format("H"), $now->format("i"), $now->format("s"), // hours.
				$now->format("m"), (int) $now->format("d")+$days, // 10 days.
				$now->format("Y"));
		
		$to = strftime("%Y-%m-%d %H:%M:%S", $nextdate);
		
		$query->where(sprintf('date >= "%s" and date <= "%s" and publish = 1', $_now, $to));
		// $query->limite(10);
		try
		{	
			$items = $db->setQuery($query)->loadObjectList();
		} catch (RuntimeException $e) {
			$items = array(); // log required
		}
		return $items;
	}
	
	public function setRelated( &$item ) {
		$db =& JFactory::getDbo();
		
		foreach ($this->_related as $object) {
			$query = $db->getQuery(true);
			
			$query->select('*')->from($object["table"]);
			$query->where(sprintf('%s = %s', $object["foreingkey"], (int)$item->id));
			
			$item->$object["reference"] = $db->setQuery($query)->loadObjectList();
		}
		return true;
	}
	/*
	 * Faz getItems retornar dados relacionandos ao items
	 * 
	 */
	public function getItems() {
		$events =& parent::getItems();
		$db =& JFactory::getDbo();
		
		if ($events && count($events) > 0) {
			foreach ($events as &$event) {
				$this->setRelated( $event );
			}
		}
		return $events;
	}
	//
	protected function getListQuery() {
		$db =& JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from( $this->_table );
		$query->where( $this->_where );
		
		return $query;
	}
	//
	public function getById($object_id) {
		$db =& JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('*')->from( $this->_table );
		$query->where("id = ".(int)$object_id);
		$item = $db->setQuery($query)->loadObject();
		
		$this->setRelated( $item );
		return $item;
	}
}


