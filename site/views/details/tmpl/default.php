<?php
/**
* @license	GNU General Public License version 2 or later; see LICENSE.txt
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root(false).'components/com_pbevents/css/com_pbevents.default.css'); 
?>
<?php if ($this->event): ?>
	<?php $this->events = array($this->event);//alias for include ?>
	<?php include implode(DIRECTORY_SEPARATOR, array(JPATH_COMPONENT_SITE,"views","pbevents","tmpl","default.php")) ?>
<?php else: ?>
	<div id="event-deleted-info">
		<p><?php echo JText::_("COM_PBEVENTS_COURSE_DELETED_INFO") ?></p>
	</div>
<?php endif ?>