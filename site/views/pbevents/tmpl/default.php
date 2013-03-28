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

// template de detalhes compõe o corpo das lista de eventos.
$sharedTemplatePath = implode(DIRECTORY_SEPARATOR, array(JPATH_COMPONENT_SITE,"views","details","tmpl","default.php"));
?>
<?php if (isset($this->events) && count($this->events) > 0): ?>
	<?php foreach ($this->events as $this->event): ?>
		<div id="event-info-group">
			<form action="<?php echo JRoute::_('index.php?option=com_pbevents') ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
				
				<?php include $sharedTemplatePath; // incluindo o template de detalhes. usando a mesma base ?>
				
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
						<?php endif ?>
					<?php else: ?>
						<input type="submit" class="event-submit" name="event-<?php echo $this->event->id ?>" value="<?php echo JText::_("COM_PBEVENTS_SUBMIT_REGISTER") ?>" />
						<input type="hidden"  name="event" value="<?php echo $this->event->id ?>" />
					<?php endif ?>
				</div>
				<div>
					<input type="hidden" name="task" value="" />
					<?php echo JHtml::_('form.token') ?>
				</div>
			</form>
		</div>
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












