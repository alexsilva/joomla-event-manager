<?php

/**
 * @package		PurpleBeanie.PBEEvents
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.purplebeanie.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

$config = JFactory::getConfig();

echo '<h1>'.JTEXT::_('COM_PBEVENTS_HEADING').'</h1>';

?>
<?php if (!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else: ?>
	<div id="j-main-container">
<?php endif;?>

<div class="container-fluid">
<!-- Begin Content -->
<div class="row-fluid">
	<div class="span6">
		<div class="well well-small">
			<div class="module-title nav-header">
				<?php echo JText::_('COM_PBEVENTS_LATEST_REGISTRATIONS')?>
			</div>
			<div class="row-striped">
				<?php if ($this->event->rsvps && count($this->event->rsvps) > 0) :?>
				<?php foreach ($this->event->rsvps as $rsvps) :?>
				<?php $user =& JFactory::getUser($rsvps->user_id) ?>
				<div class="row-fluid">
					<div class="span9">
						<strong class="row-title"><?php echo $rsvps->title ?> </strong>
					</div>
					<div class="span3">
						<?php echo $user->email ." " .$user->username ?>
					</div>
				</div>
				<?php endforeach ?>
				<?php else: ?>
				<div class="row-fluid">
					<div class="span12">
						<strong class="row-title"><?php echo JText::_('COM_PBBOOKING_DASHBOARD_NOTHING_FOUND')?>
						</strong>
					</div>
				</div>
				<?php endif ?>
			</div>
		</div>
	</div>

	<div class="span6">
		<div class="well well-small">
			<div class="module-title nav-header">
				<?php echo JText::_('COM_PBEVENTS_UPCOMING_EVENTS')?>
			</div>
			<div class="row-striped">
				<?php if ($this->upcoming and count($this->upcoming) > 0): ?>
					<?php foreach ($this->upcoming as $object): ?>
					<div class="row-fluid">
						<div class="span9">
							<a href="<?php echo JRoute::_('index.php?option=com_pbevents&task=edit&id='.(int)$object->event_id)?>">
								<strong class="row-title"><?php echo $object->title ?> </strong>
							</a>
						</div>
						<div class="span3">
							<i class="icon-calendar"></i>
							<?php echo JHtml::_('date', 
									date_create($object->date, new DateTimeZone($config->get('offset')))->format(DATE_ATOM),
											JText::_('COM_PBEVENTS_FULL_DATE_FORMAT')) ?>
						</div>
					</div>
					<?php endforeach ?>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>
</div>
