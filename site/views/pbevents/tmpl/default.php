<?php

/**
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.framework');

$doc = JFactory::getDocument();
$config = JFactory::getConfig();

$doc->addStyleSheet(JUri::root(false).'components/com_pbevents/css/com_pbevents.default.css');

$option = JRequest::getCmd('option');
$view = JRequest::getCmd('view');
?>

<?php if (isset($this->events) && count($this->events) > 0): ?>
	
	<?php foreach ($this->events as $event): ?>
		<form action="<?php echo JRoute::_('index.php?option=com_pbevents') ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
			<h2><?php echo $event->title ?></h2>
			<div class="event-info">
				<p><?php echo $event->description ?></p>
			</div>
			
			<?php $inscription = new Inscription($event) ?>
			
			<div class="event-info event-dates">
				<h3><?php echo JText::_("COM_PBEVENTS_DATES") ?></h3>
				
				<?php foreach($event->dates as $date): ?>
					<p><?php echo PBEventsHelper::formatDateHours($date->date, 
								$date->hstart, $date->hend, $date->henable); ?>
						<i><?php echo $date->description ?></i>
					</p>
				<?php endforeach ?>
			</div>
			
			<div class="event-info">
				<p>
					<?php echo sprintf(JText::sprintf("COM_PBEVENTS_GLOBAL_HOUR", $event->hstart, $event->hend)) ?>
				</p>
			</div>
						
			<div class="event-info">
				<p><?php echo JText::_("COM_PBEVENTS_PANELIST") ?><i><?php echo $event->panelist ?></i></p>
			</div>
			
			<div class="event-info">
				<p><?php echo JText::_("COM_PBEVENTS_PRICE") ?><i><?php echo PBEventsHelper::formatMoney($event->price) ?></i></p>
			</div>
			
			<?php if ($event->show_counter == 1): ?>
				<div id="event-people-number">
					<p><?php echo sprintf(JText::_("COM_PBEVENTS_PARTICIPATE_NUMBER"), $inscription->current()) ?></p>
					<p><?php echo sprintf(JText::_("COM_PBEVENTS_PARTICIPATING_NUMBER"), $inscription->count()) ?></p>
				</div>
			<?php endif ?>
			
			<div class="div-input-submit">
				<?php if(!$inscription->isQuestUser()): ?>
					<?php if(!$inscription->checkUser()): ?>
						<?php if (!$inscription->isClosed()): ?>
							<input type="submit" class="submit" name="event-<?php echo $event->id ?>" value="<?php echo JText::_("COM_PBEVENTS_SUBMIT_REGISTER") ?>" />
							<input type="hidden"  name="event" value="<?php echo $event->id ?>" />
						<?php else: ?>
							<div><p id="alert-max-people"><?php echo JText::_("COM_PBEVENTS_MAX_PEOPLE_ACHIEVED") ?></p></div>
						<?php endif ?>
					<?php else: ?>
						<div><p id="alert-user-registred"><?php echo JText::_("COM_PBEVENTS_REGISTRED_USER_ALERT") ?></p></div>
					<?php endif ?>
				<?php else: ?>
					<input type="submit" class="event-submit" name="event-<?php echo $event->id ?>" value="<?php echo JText::_("COM_PBEVENTS_SUBMIT_REGISTER") ?>" />
					<input type="hidden"  name="event" value="<?php echo $event->id ?>" />
				<?php endif ?>
			</div>
			<div>
				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token') ?>
			</div>
		</form>
	<?php endforeach?>
<?php else: ?>
	<div><p id="alert-no-article"><?php echo JText::_("COM_PBEVENTS_NO_EVENTS") ?></p></div>
<?php endif ?>

<form action="<?php echo JRoute::_('index.php?option=com_pbevents') ?>" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="<?php echo $option ?>" />
    <input type="hidden" name="view" value="<?php echo $view ?>" />
	<div class="pagination">
		<div><?php echo $this->pagination->getResultsCounter()?></div>
		<div><?php echo $this->pagination->getPagesLinks() ?></div>
	</div>
</form>












