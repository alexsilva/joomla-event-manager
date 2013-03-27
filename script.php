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
	}
}

