<?php

class PBEventsModelListAttendees extends JModelList
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
		$query->select('*')->from('#__pbevents_rsvps');
		
		$input =& JFactory::getApplication()->input;
		$event_id = $input->get("id", 0, 'integer');
		
		$query->where('event_id = '.$db->escape($event_id));
		return $query;
	}
	
}