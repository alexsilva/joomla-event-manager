<?php
/**
* @license	GNU General Public License version 2 or later; see LICENSE.txt
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root(false).'components/com_pbevents/css/com_pbevents.default.css');

$inscription = new Inscription($this->event);
?>

<h2><?php echo $this->event->title ?></h2>

<div class="event-info">
	<p><?php echo $this->event->description ?></p>
</div>

<div class="event-info event-dates">
	<h3><?php echo JText::_("COM_PBEVENTS_DATES") ?></h3>
	
	<?php foreach($this->event->dates as $date): ?>
		<p><?php echo PBEventsHelper::formatDateHours($date->date, $date->hstart, $date->hend, $date->henable); ?>
			<i><?php echo $date->description ?></i>
		</p>
	<?php endforeach ?>
</div>

<div class="event-info">
	<p><?php echo sprintf(JText::sprintf("COM_PBEVENTS_GLOBAL_HOUR", $this->event->hstart, $this->event->hend)) ?></p>
</div>

<?php if ($this->event->panelist): ?>
	<div class="event-info">
		<p><?php echo JText::_("COM_PBEVENTS_PANELIST") ?><i><?php echo $this->event->panelist ?></i></p>
	</div>
<?php endif ?>

<?php if ($this->event->price && $this->event->price != 0): ?>
	<div class="event-info">
		<p><?php echo JText::_("COM_PBEVENTS_PRICE") ?><i><?php echo PBEventsHelper::formatMoney($this->event->price) ?></i></p>
	</div>
<?php endif ?>

<?php if ($this->event->show_counter == 1): ?>
	<div id="event-people-number">
		<p><?php echo sprintf(JText::_("COM_PBEVENTS_PARTICIPATE_NUMBER"), $inscription->current()) ?></p>
		<p><?php echo sprintf(JText::_("COM_PBEVENTS_PARTICIPATING_NUMBER"), $inscription->count()) ?></p>
	</div>
<?php endif ?>

