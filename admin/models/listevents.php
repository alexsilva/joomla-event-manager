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
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('publish = ' .(int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(publish = 0 OR publish = 1)');
		}
		// Filter by published state
		$catid = $this->getState('filter.category_id');
		if (is_numeric($catid))
		{
			$query->where('catid = ' .(int) $catid);
		}
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
		} elseif (count($items) == 0) {
			$items = array(); // skip erros in foreach
		}
		return $items; // lista de resultados.
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
	
		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.'.$layout;
		}
		
		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
	
		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);
		
		// List state information.
		parent::populateState($ordering, $direction);
	}
}












