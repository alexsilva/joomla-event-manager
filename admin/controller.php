<?php
/**
 * @package      PurpleBeanie.PBBooking
 * @license      GNU General Public License version 2 or later; see LICENSE.txt
 * @link     http://www.purplebeanie.com
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controlleradmin');
jimport('joomla.application.input');
jimport('joomla.html.pagination');

class PbeventsController extends JControllerLegacy
{	
	/**
	 * Method to display the view
	 *
	 * @access    public
	 */
	function display($cachable = false, $urlparams = array())
	{
		JToolBarHelper::title(JText::_('COM_PBEVENTS_HEADING'), 'generic.png');
		
		$app =& JFactory::getApplication();
		$plugin = JPluginHelper::getPlugin('user', 'profile10');
		
		// has the plugin been activated?
		if (count($plugin) == 0) {
			$app->enqueueMessage(JText::_('COM_PEBEVENTS_PROFILE_NOT_ENABLE'), 'error');
		}
		
		$input = $app->input;
		$_view = "pbevents";
		
		if ($input->get("view", $_view) == $_view)
		{
			$view = $this->getView($_view, "html");
			
			if ($model = $this->getModel("pbevents"))
			{
				$view->setModel($model, true);
			}
			$view->event = $model->getLastEntries();
			$view->upcoming = $model->getDateUpComing(); //UPCOMING
			
			$view->display();
		} 
		else
		{	
			return parent::display($cachable, $urlparams);
		}
	}

	/**
	 * Method to list all the events in the database
	 * @todo implement proper filtering and pagination on listed events
	 *
	 */
	public function listevents() {
		// configurando a view da tarefa.
		JFactory::getApplication()->input->set("view", "listevents");
		return parent::display();
	}
	
	/**
	 * gets all attendees for an event and displays along with all custom fields
	 */
	public function viewattendees()
	{
		JToolBarHelper::title(JText::_('COM_PBEVENTS_EVENTS_MANAGER').' '.JText::_('COM_PBEVENTS_ATTENDEES'),'generic.png');
		$canDo = PBEventsHelper::getActions();
		
		// definindo níves de permissões.
		if ($canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('','deleteattendee');
		}
		
		$input = JFactory::getApplication()->input;
		$event_id = $input->get('id',null,'integer');
		$limit= $input->get('limit',20,'integer');
		$limitstart= $input->get('limitstart',0,'integer');

		//load the view and stuff in params
		$view = $this->getView('pbevents','html');
		$view->setLayout('viewattendees');

		if ($event_id) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')->from('#__pbevents_events')->where('id = '.$db->escape($event_id));
			$view->event = $db->setQuery($query)->loadObject();
			if ($view->event) {
				$query = $db->getQuery(true);
				$query->select('*')->from('#__pbevents_rsvps')->where('event_id = '.$db->escape($view->event->id));
				$view->attendees = $db->setQuery($query)->loadObjectList();
				$total_records = count($view->attendees);
				if ($limit>0)
					$view->attendees = array_slice($view->attendees,$limitstart,$limit);

				//display the view
				$view->pagination = new JPagination($total_records,$limitstart,$limit);
				$view->display();
			} else {
				$this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=listevents',JText::_('COM_PBEVENT_INVALID_EVENT'));
			}
		} else {
			$this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=listevents',JText::_('COM_PBEVENT_NO_ID'));
		}
	}

	/**
	 * deletes attendees / attendee from an event and redirects to the list page
	 */
	public function deleteattendee()
	{
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid', 0, 'array');
		$event_id = $input->get('id', 0, 'integer');
		
		if ($cid) {
			//process the delete attendes
			$db = &JFactory::getDbo();
			foreach ($cid as $id) {
				$query = $db->getQuery(true);
				$query->delete('#__pbevents_rsvps')->where('id = '.$db->escape($id));
				$db->setQuery($query);
				$db->query();
			}
		}
		$this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=viewattendees&id='.(int)$event_id);
	}
	
	/**
	 * responds to the publish function
	 */
	public function publish()
	{
		echo print_r($_REQUEST);
		$db = &JFactory::getDbo();
		$input = &JFactory::getApplication()->input;
		$cids = $input->get('cid',null,'array');
		
		foreach ($cids as $cid)
		{
			$db->updateObject('#__pbevents_events', new JObject(array('id'=>(int)$cid,'publish'=>1)), 'id');
		}
		$this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=listevents');
	}
	
	/**
	 * responds to the unpublish function
	 */
	public function unpublish()
	{
		$db = &JFactory::getDbo();
		$input = &JFactory::getApplication()->input;
		$cids = $input->get('cid',null,'array');
		
		foreach ($cids as $cid)
		{
			$db->updateObject('#__pbevents_events', new JObject(array('id'=>(int)$cid,'publish'=>0)),'id');
		}
		$this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents&task=listevents');
	}
	
	/**
	 * responds to the cancel function
	 * @access public
	 * @since 0.2
	 */
	public function cancel()
	{
		$this->setRedirect(JURI::root(false).'administrator/index.php?option=com_pbevents');
	}
}
