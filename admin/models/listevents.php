<?php

class PBEventsModelListEvents extends JModelList
{	
	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   12.2
	 */
	protected function getListQuery() {
		$db =& JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('*')->from('#__pbevents_events');
		$query->order('id DESC');
		
		return $query;
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItems() {
		if ($items = parent::getItems())
		{
			// adicinando relacionados.
			$db =& JFactory::getDbo();
			
			foreach ($items as &$item)
			{
				$query = $db->getQuery(true);
				
				$query->select('*')->from('#__pbevents_rsvps');
				$query->where('event_id = '.(int)$item->id);
				
				// attendees results.
				$item->attendees = $db->setQuery($query)->loadObjectList();
				
				$query = $db->getQuery(true);
				$query->select('*')->from('#__pbevents_events_dates');
				$query->where('event_id = '.(int)$item->id);
				
				// attendees dates.
				$item->dates = $db->setQuery($query)->loadObjectList();
			}
		}
		return $items; // lista de resultados.
	}
}












