<?php

/**
* @package	...
* @license	GNU General Public License version 2 or later; see LICENSE.txt
* @link		
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
?>

<div style="text-align: center;">
	<?php if ($this->event): ?>
		<h3><?php echo $this->event->title ?></h3>	
	<?php else: ?>
		<h3><?php echo JText::_('COM_PBEVENT_INVALID_EVENT') ?></h3>
	<?php endif ?>
</div>

<form action="<?php echo JRoute::_('index.php?option=com_pbevents&view=listattenees') ?>" method="post" name="adminForm" id="adminForm">  

	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<!-- draw header rows-->
				<th>	
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				
				<th>ID</th>
				
				<th><?php echo JText::_("COM_PBEVENTS_VIEW_EMAIL_USER") ?></th>
				
				<th><?php echo JText::_("COM_PBEVENTS_VIEW_USERNAME") ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr><td colspan="5"><?php echo $this->pagination->getListFooter() ?></td></tr>
		</tfoot>
		<tbody>
			<?php if (isset($this->attendees) && count($this->attendees) > 0): ?>
				<?php foreach ($this->attendees as $i => $attendee): ?>
					<tr class="row<?php echo $i % 2; ?>">
						
						<td><?php echo JHtml::_('grid.id', $i, $attendee->id) ?></td>
						<td><?php echo $attendee->id; ?></td>
						
						<?php $user =& JFactory::getUser($attendee->user_id) ?>
						
						<td><?php echo $user->email ?></td>
						<td><?php echo $user->username ?></td>
					</tr>
				<?php endforeach;?>
			<?php else :?>
				<tr>
					<td colspan="5" style="text-align:center;">
						<?php echo JText::_('COM_PBEVENTS_NO_ATTENDEES');?>
					</td>
				</tr>
			<?php endif;?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id" value="<?php echo $this->event_id ?>"/>
	<?php echo JHtml::_('form.token') ?>
</form>