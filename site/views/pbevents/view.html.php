<?php
/**
* @license	GNU General Public License version 2 or later; see LICENSE.txt
*/
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

require_once COM_PBEVENTS_UTILS_PATH.DIRECTORY_SEPARATOR."inscription.php";

// --------------------------------------------------------------------------

class PbEventsViewPBevents extends JViewLegacy
{
    function display($tpl = null)
    {
    	if ($_SERVER["REQUEST_METHOD"] == "POST")
    		$this->on_POST();
    	
    	// forca um limite para a paginacao.
    	$input = JFactory::getApplication()->input;
    	// component configs
    	$params =& JComponentHelper::getParams("com_pbevents");
    	$list_limit = $params->get("slist_limit", 6);
    	
    	$input->set("limit", $input->get("limit",$list_limit,"integer"));
    	
    	// events list
    	$this->events = $this->get("Items");
    	$this->pagination = $this->get("Pagination");
    	
    	return parent::display($tpl);
    }
    
    private function on_POST() {
    	$app =& JFactory::getApplication();
    	if (!$event_id = $app->input->get("event",null,"integer"))
    		return;
    	
    	$model = $this->getModel("pbevents");
    	$event = $model->getById( $event_id );
    	
    	$inscription = new Inscription( $event );
    	
    	// verifica se o post partiu do site
    	$inscription->checkToken();
    	
    	if (!$inscription->isQuestUser())
    	{
    		if (!$inscription->checkUser())
    		{
    			if (!$inscription->isClosed()) {
	    			if ($inscription->addUser())
	    			{
	    				$inscription->execute("success");
	    				$app->enqueueMessage(JText::_("COM_PBEVENTS_ONREGISTRATION_SUCCESS"),'success');
	    			} else {
	    				$inscription->execute("fail");
	    				$app->enqueueMessage(JText::_("COM_PBEVENTS_ONREGISTRATION_ERROR"),'error');
	    			}
    			} else {
    				$app->enqueueMessage(JText::_("COM_PBEVENTS_CLOSED_REGISTRATION"),'info');
    			}
    		} else {
    			$app->enqueueMessage(JText::_("COM_PBEVENTS_REGISTRED_INFO"),'info');
    		}
    	} else {
    		$app->enqueueMessage(JText::_("COM_PBEVENTS_LOGIN_REQUERED"),'error');
    	}
    }
}




