<?php
/**
 * @module		com_pbevent
 * @author-name Alex Sandro
 * @copyright	Copyright (C) 2012 ...
 * @license		GNU/GPL
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// require helper file
JLoader::register('PBEventsHelper', dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'pbevents.php');

// import joomla controller library
jimport('joomla.application.component.controller');

$input = JFactory::getApplication()->input;

// Get an instance of the controller prefixed by Ola
$controller = JControllerLegacy::getInstance('PBEvents');

// Perform the Request task
$controller->execute($input->get("task","display","string"));

// Redirect if set by the controller
$controller->redirect();