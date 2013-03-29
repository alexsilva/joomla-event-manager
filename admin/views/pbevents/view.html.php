<?php
/**
* @package		PurpleBeanie.PBEvents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.view');

class PbeventsViewPbevents extends JViewLegacy
{
    function display($tpl = null)
    {	
    	JToolBarHelper::title(JText::_('COM_PBEVENTS_HEADING'), 'generic.png');
    	
    	$this->event = $this->get("LastEntries");
    	$this->upcoming = $this->get("DateUpComing"); //UPCOMING
    	
    	return parent::display($tpl);
    }
}