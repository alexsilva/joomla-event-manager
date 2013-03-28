<?php

/**
* @package		PurpleBeanie.PBEEvents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('dropdown.init');
?>

<form action="<?php echo JRoute::_('index.php?option=com_pbevents&task=listevents');?>" method="post" name="adminForm" id="adminForm">  
	<?php if (!empty( $this->sidebar)): ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else: ?>
		<div id="j-main-container">
	<?php endif;?>
	
	<div class="clearfix"> </div>
	
	<table class="table table-striped" id="articleList">
		<thead>
			<tr>
				<th>
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th>ID</th>
				
				<th><?php echo JText::_('COM_PBEVENTS_EVENT_NAME') ?></th>
				
				<th><?php echo JText::_('COM_PBEVENTS_EVENT_DESCRIPTION') ?></th>
				
				<th><?php echo JText::_('COM_PEBEVENTS_PANELIST') ?></th>
				
				<th><?php echo JText::_('COM_PEBEVENTS_EVENTS_DATES') ?></th>
				
				<th><?php echo JText::_('COM_PEBEVENTS_EVENTS_START_HOUR') ?></th>
				
				<th><?php echo JText::_('COM_PEBEVENTS_EVENTS_END_HOUR') ?></th>
				
				<th><?php echo JText::_('COM_PEBEVENTS_EVENTS_PRICE') ?></th>
				
				<th><?php echo JText::_('COM_PBEVENTS_ATTENDEES') ?></th>
				
				<th><?php echo JText::_('COM_PBEVENTS_EVENT_ARCHIVED')?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10"><?php echo $this->pagination->getListFooter() ?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->events as $i => $event) :?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center" width="1%">
					<?php echo JHtml::_('grid.id', $i, $event->id); ?>
				</td>
				
				<td class="center" width="1%">
					<?php echo $event->id;?>
				</td>
				
				<td class="center">
					<a href="<?php echo JURI::root(false);?>administrator/index.php?option=com_pbevents&task=event.edit&id=<?php echo $event->id;?>">
						<?php echo $event->title;?>
					</a>
				</td>
				
				<td style="max-width: 250px;">
					<?php echo $event->description;?>
				</td>
				
				<td class="center">
					<?php echo $event->panelist;?>
				</td>
				
				<td class="center">
					<select name="events_dates_choices" class="inputbox">
						<?php echo JHtml::_('select.options', $this->makeDateOptions($event->dates), 'value', 'text', $this->state->get('filter.published'), true);?>
					</select>
				</td>
				
				<td class="center">
					<?php echo $event->hstart;?>
				</td>
				
				<td class="center">
					<?php echo $event->hend;?>
				</td>
				
				<td class="center">
					<?php echo PBEventsHelper::formatMoney($event->price);?>
				</td>
				
				<td class="center">
					<a href="<?php echo JURI::root(false);?>administrator/index.php?option=com_pbevents&task=viewattendees&id=<?php echo $event->id;?>"><?php echo count($event->attendees);?></a>
				</td>
				
				<td class="center"><?php echo JHtml::_('jgrid.published', $event->publish, $i); ?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="listevents" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>