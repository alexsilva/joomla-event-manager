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
	public $_where = "publish = 1 and catid = ";
	
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
	
	private function setRelated( &$item ) {
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
		
		$input = JFactory::getApplication()->input;
		$catid = $input->get("catid", 0, "integer");
		
		$query->where( $this->_where.(int)$catid );
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


