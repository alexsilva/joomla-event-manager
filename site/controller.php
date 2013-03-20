<?php
/**
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

define("COM_PBEVENTS_PATH", dirname(__FILE__));
define("COM_PBEVENTS_UTILS_PATH", COM_PBEVENTS_PATH.DIRECTORY_SEPARATOR."utils");
// --------------------------------------------------------------------------

class PbeventsController extends JControllerLegacy
{
	/**
	 * Method to display the view
	 *
	 * @access    public
	 */
	function display()
	{
		$input = JFactory::getApplication()->input;
		$input->set("layout", "default");
		
		return parent::display();
	}
	function details() {
		$input = JFactory::getApplication()->input;
		$input->set("view", "details");
		
		$this->display();
	}
}

