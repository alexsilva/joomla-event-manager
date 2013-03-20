<?php

//No direct access to this file

defined('_JEXEC') or die('Restricted access');

/**
* com_pbevents installer script
*/

class com_pbeventsInstallerScript
{
	function postflight($type, $parent) {
		$installer = new JInstaller(); //now plugin install.
		
		$installer->install(
				implode(DIRECTORY_SEPARATOR, 
					array(dirname(__FILE__),'plugin',"user","profile10")
				));
		
		$parent->getParent()->setRedirectURL('index.php?option=com_pbevents');
	}
	
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent)
	{
		// load the language strings
		$translator = JFactory::getLanguage();
		$translator->load('com_pbevents', JPATH_ADMINISTRATOR);
		
		// $parent is the class calling this method
		$db =& JFactory::getDbo();
		
		$config = new JObject(array(
			'id' => 1,
			'email_failed_subject' => $translator->_("COM_PBEVENTS_EMAIL_FAILED_SUBJECT"),
			'email_failed_body' => $translator->_("COM_PBEVENTS_EMAIL_FAILED_BODY"),
			'email_success_subject' =>  $translator->_("COM_PBEVENTS_EMAIL_SUCCESSFUL_SUBJECT"),
			'email_success_body'=> $translator->_("COM_PBEVENTS_EMAIL_SUCCESSFUL_BODY"),
			'send_notifications_to' => $translator->_("COM_PBEVENTS_EMAIL_TIP_SAMPLE"),
			'date_picker_locale' => $translator->_("COM_PBEVENTS_LOCALE")
		));
		
		$db->insertObject('#__pbevents_config', $config, 'id');
	}
}

