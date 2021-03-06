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
		$plugin = JPluginHelper::getPlugin('user', 'profile10');
		 
		// has the plugin been activated?
		if (count($plugin) == 0)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_PEBEVENTS_PROFILE_NOT_ENABLE'), 'error');
		}
		return parent::display($cachable, $urlparams);
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
	 * responds to the publish function
	 */
	public function publish()
	{
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
