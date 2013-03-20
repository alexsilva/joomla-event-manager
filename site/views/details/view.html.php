<?php
/**
* @license	GNU General Public License version 2 or later; see LICENSE.txt
*/
 
// no direct access
 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

require_once COM_PBEVENTS_UTILS_PATH.DIRECTORY_SEPARATOR."inscription.php";
require_once COM_PBEVENTS_PATH.DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."pbevents.php";

// --------------------------------------------------------------------------

class PbEventsViewDetails extends JViewLegacy
{
	public function __construct($config = array()) {
		
		$this->model = new PBEventsModelPBEvents();
		
		parent::__construct($config);
	}
	
	function display($tpl = null) {
    	$input =& JFactory::getApplication()->input;
    	$event_id = $input->get("id",null,"integer");
    	
    	$this->event = $this->model->getById( $event_id );
    	
    	return parent::display($tpl);
    }
}




