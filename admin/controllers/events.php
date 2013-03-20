<?php
/**
 * @package  ...
 * @license  GNU General Public License version 2 or later; see LICENSE.txt
 * @link     ....
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controlleradmin');

class PBEventsControllerEvents extends JControllerAdmin
{
	public function __construct($config = array())
	{
		$this->view_list = 'default&task=listevents';
	
		parent::__construct($config);
	}
	
    /**
    * Set default values when no action is specified (ie for cancel)
    */
    public function getModel($name = 'Events', $prefix = 'PBEventsModel', $config = array())
    {
    	return parent::getModel($name, $prefix, $config);
    }
}