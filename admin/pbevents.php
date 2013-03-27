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

// Get an instance of the controller prefixed by PBEvents
$controller = JControllerLegacy::getInstance('PBEvents');

// menu lista com categoria
PBEventsHelper::addSubmenu($input->get("task","display","string"));

// Perform the Request task - usando referencia atualizada para task.
$controller->execute($input->get("task","display","string"));

// Redirect to page set
$controller->redirect();