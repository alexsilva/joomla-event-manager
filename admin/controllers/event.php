<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * HelloWorld Controller
 */
class PBEventsControllerEvent extends JControllerForm
{
	public function __construct($config = array())
	{
		// set redirect
		$this->view_list = '&task=listevents';
		
		parent::__construct($config);
	}
}





?>