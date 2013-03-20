<?php

/**
* @package	PurpleBeanie.PBevents
* @license	GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// require helper file
JLoader::register('PBEventsHelper', dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'pbevents.php');

$input = JFactory::getApplication()->input;
$task  = $input->get("task","display","string");

JSubMenuHelper::addEntry(JText::_('COM_PBEVENTS_DASHBOARD'),'index.php?option=com_pbevents', ($task=='display'));
JSubMenuHelper::addEntry(JText::_('COM_PBEVENTS_ADMIN_LIST_EVENTS'), 'index.php?option=com_pbevents&task=listevents', ($task == 'listevents'));
JSubMenuHelper::addEntry(JText::_('COM_PBEVENTS_CONFIGURATION'),'index.php?option=com_pbevents&task=editconfiguration', ($task == 'editconfiguration'));

// Get an instance of the controller prefixed by PBEvents
$controller = JControllerLegacy::getInstance('PBEvents');

// Perform the Request task - usando referencia atualizada para task.
$controller->execute($input->get("task","display","string"));

// Redirect to page set
$controller->redirect();