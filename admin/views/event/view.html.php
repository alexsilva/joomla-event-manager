<?php
/**
* @package		PurpleBeanie.PBEvents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// no direct access
defined('_JEXEC') or die( 'Restricted access' ); 
jimport('joomla.application.component.view');

class PbeventsViewEvent extends JViewLegacy
{
    function display($tpl = null)
    {
		$this->addTitle(); // title: edit/create
		
    	$this->form = $this->get("Form");
    	$this->item = $this->get('Item');
    	
    	$this->addToolBar(); // controls
    	
    	return parent::display($tpl);
    }
    
    /**
     * Setting the toolbar
     */
    public function addTitle() {
    	$input = JFactory::getApplication()->input;
    	
    	$isNew = $input->get("id",null,"integer") != null;
    	$legend = $isNew ? JText::_('COM_PBEVENTS_EDIT_EVENT'): JText::_('COM_PBEVENTS_CREATE_EVENT');
    	
    	JToolBarHelper::title(JText::_('COM_PBEVENTS_EVENTS_MANAGER' ).' '.$legend, 'generic.png');
    }
    
    /**
     * Setting the toolbar
     */
    public function addToolBar()
    {	
    	$input = JFactory::getApplication()->input;
    	$input->set('hidemainmenu',true);
    	
    	$canDo = PBEventsHelper::getActions();
    	
    	// agindo conforme as permissões.
    	if ($canDo->get('core.edit'))
    	{
    		JToolBarHelper::apply('event.apply');
    		JToolBarHelper::save('event.save');
    		
    	}
    	JToolBarHelper::cancel('event.cancel');
    	JToolBarHelper::spacer("10px");
    	
    	JToolBarHelper::custom("create.date.field", "new", "New Date", 
    			 JText::_("COM_PBEVENTS_EDIT_CREATE_NEW_DATE"), false);
    }
}


