<?php
/**
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.framework');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root(false).'components/com_pbevents/css/com_pbevents.default.css');

$input =& JFactory::getApplication()->input;
$option = $input->get("option","");
$view = $input->get("view","");

$postActionUrl = JRoute::_('index.php?option=com_pbevents');

?>
<?php if (isset($this->events) && count($this->events) > 0): ?>
<?php foreach ($this->events as $this->event): ?>
	<form action="<?php echo $postActionUrl ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
		<h2><?php echo $this->event->title ?></h2>
		
		<div id="event-info-group">
			<?php $inscription = new Inscription($this->event) ?>
			
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
			<div class="div-input-submit">
				<?php if(!$inscription->isQuestUser()): ?>
					<?php if(!$inscription->checkUser()): ?>
						<?php if (!$inscription->isClosed()): ?>
							<input type="submit" class="submit" name="event-<?php echo $this->event->id ?>" value="<?php echo JText::_("COM_PBEVENTS_SUBMIT_REGISTER") ?>" />
							<input type="hidden"  name="event" value="<?php echo $this->event->id ?>" />
						<?php else: ?>
							<div><p id="alert-max-people"><?php echo JText::_("COM_PBEVENTS_MAX_PEOPLE_ACHIEVED") ?></p></div>
						<?php endif ?>
					<?php else: ?>
						<div><p id="alert-user-registred"><?php echo JText::_("COM_PBEVENTS_REGISTRED_USER_ALERT") ?></p></div>
						<button onclick="location.href='<?php echo JRoute::_("index.php?option=com_users&view=profile")?>'; return false">
							<?php echo JText::_("COM_PBEVENTS_BUTTON_UNSUBSCRIBE") ?>
						</button>
					<?php endif ?>
				<?php else: ?>
					<input type="submit" class="event-submit" name="event-<?php echo $this->event->id ?>" 
						   value="<?php echo JText::_("COM_PBEVENTS_SUBMIT_REGISTER") ?>" />
					<input type="hidden"  name="event" value="<?php echo $this->event->id ?>" />
				<?php endif ?>
			</div>
			<div>
				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token') ?>
			</div>
		</div>
	</form>
<?php endforeach?>
<?php else: ?>
	<div><p id="alert-no-article"><?php echo JText::_("COM_PBEVENTS_NO_EVENTS") ?></p></div>
<?php endif ?>
<?php if (isset($this->pagination)): ?>
	<form action="<?php echo JRoute::_('index.php?option=com_pbevents') ?>" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="option" value="<?php echo $option ?>" />
	    <input type="hidden" name="view" value="<?php echo $view ?>" />
		<div class="pagination">
			<div><?php echo $this->pagination->getResultsCounter()?></div>
			<div><?php echo $this->pagination->getPagesLinks() ?></div>
		</div>
	</form>
<?php endif ?>

