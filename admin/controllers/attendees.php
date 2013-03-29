<?php
/**
 * @package  ...
 * @license  GNU General Public License version 2 or later; see LICENSE.txt
 * @link     ....
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controlleradmin');

class PBEventsControllerAttendees extends JControllerAdmin
{
	public function __construct($config = array())
	{
		$input = JFactory::getApplication()->input;
		$targetId = $input->get("id", 0, "integer");
		
		$this->view_list = 'listattendees&id='.(int)$targetId;
		
		parent::__construct($config);
	}
	
    /**
    * Set default values when no action is specified (ie for cancel)
    */
    public function getModel($name = 'Attendees', $prefix = 'PBEventsModel', $config = array())
    {
    	return parent::getModel($name, $prefix, $config);
    }
}