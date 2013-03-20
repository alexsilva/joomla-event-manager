<?php
/**
* @package		PurpleBeanie.PBEvents
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.framework');

$doc = JFactory::getDocument();

$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/Locale.'.$this->config->date_picker_locale.'.DatePicker.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/Picker.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/Picker.Attach.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/Picker.Date.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com_pbevents.administrator.create.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com.purplebeanie.general.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com_pbevents.administrator.date.js');

$doc->addStyleSheet(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/datepicker_jqui/datepicker_jqui.css');
$doc->addStyleSheet(JURI::root(false).'administrator/components/com_pbevents/styles/form_fieldset.css');

?>
<script type="text/javascript">
jQuery(document).ready(function() {
	date = new DateFieldControl({
		date: {'label': '<?php echo JText::_('COM_PBEVENTS_DATE');?>'},
		hstart: {'label': '<?php echo JText::_('COM_PBEVENTS_OPTIONAL_HOUR');?>'},
		dtdescription: {'label': '<?php echo JText::_('COM_PEBEVENTS_EVENTS_ABOUT_HOUR');?>'},
		img: {'src': '<?php echo JURI::root(false).'administrator/components/com_pbevents/images/delete.png';?>'},
		conteiner: jQuery(".group-date"),
		sep: "-"
	});
	
	// on event click
	jQuery("#newDateEntry").click( date.insertDateObject );
	
	// configurando a hora global
	var object = jQuery("[name=hstart]");
	date.setupPicker(null, object);
	
	<?php if (isset($this->event)): ?>
		object.attr("value", "<?php echo $this->event->hstart; ?>");
		
	<?php foreach ($this->event->dates as $object): ?>
	<?php 
		$date = explode(" ", $object->date);
		$hour = $date[1]; $date = $date[0];
		
		$pieces = array(
			'date.setup({',
				sprintf('date:%s,', $object->id),
				sprintf('vdate:"%s",', $date),
				sprintf('vhour:"%s",', $hour),
				sprintf('vdesc:"%s",', $object->description),
				sprintf('vcheck:%s', $object->henable),
			'});');
		$pieces[] = 'date.insertDateObject(null);';
		echo implode("", $pieces);
	?>
	<?php endforeach; ?>
	<?php endif; ?>
});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_pbevents&layout=add');?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="row-fluid">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_PBEVENTS_DETAILS');?></legend>
			
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_PBEVENTS_EVENT_NAME');?></label></div>
				<div class="controls"><input type="text" name="title" value="<?php echo (isset($this->event->title)) ? $this->event->title : null;?>" size="80"/></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_PBEVENTS_EVENT_DESCRIPTION');?></label></div>
				<div class="controls"><textarea name="description" rows="10" cols="40"><?php echo (isset($this->event->description)) ? $this->event->description : null;?></textarea></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_PEBEVENTS_PANELIST');?></label></div>
				<div class="controls"><input type="text" name="panelist" value="<?php echo (isset($this->event->panelist)) ? $this->event->panelist : null;?>"/></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label><?php echo sprintf(JText::_('COM_PEBEVENTS_EVENTS_PRICE'), "R$") ;?></label></div>
				<div class="controls"><input type="text" name="price" value="<?php echo (isset($this->event->price)) ? $this->event->price : null;?>"/></div>
			</div>
		</fieldset>
		
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_PBEVENTS_CONFIGURATION');?></legend>
						
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_PBEVENTS_MAX_PEOPLE');?></label></div>
				<div class="controls"><input type="text" name="max_people" value="<?php echo (isset($this->event->max_people)) ? $this->event->max_people : 0;?>"/><i><?php echo JText::_("COM_PBEVENTS_TIP_MAXPEOPLE") ?></i></div>
			</div>
			
			<div class="control-group-checkbox">
				<div class="checkbox-controls"><input type="hidden" name="show_counter" value="0"><input type="checkbox" name="show_counter" value="1" <?php echo (isset($this->event->show_counter) && $this->event->show_counter == 1) ? 'checked' : null;?>></div>
				<div class="checkbox-label"><label><?php echo JText::_('COM_PBEVENTS_SHOW_COUNTER');?></label></div>
			</div>
			
			<div class="control-group-checkbox">
				<div class="checkbox-controls"><input type="checkbox" name="email_admin_failure" value="1" <?php echo (isset($this->event->email_admin_failure) && $this->event->email_admin_failure > 0 ) ? 'checked' : null;?>/></div>
				<div class="checkbox-label"><label><?php echo JText::_('COM_PBEVENTS_NOTIFY_FAILURE');?></label></div>
			</div>
			
			<div class="control-group-checkbox">
				<div class="checkbox-controls"><input type="checkbox" name="email_admin_success" value="1" <?php echo (isset($this->event->email_admin_success) && $this->event->email_admin_success > 0 ) ? 'checked' : null;?>/></div>
				<div class="checkbox-label"><label><?php echo JText::_('COM_PBEVENTS_NOTIFY_SUCCESS');?></label></div>
			</div>			
		</fieldset>
		
		<fieldset class="adminform-date">
			<legend><?php echo JText::_('COM_PEBEVENTS_EVENTS_DATES');?></legend>
			
			<div class="group-date">
				<span id="newDateEntry"><img id="dateAdd" src="<?php echo JURI::root(false);?>administrator/components/com_pbevents/images/add.png"/>
				<?php echo JText::_('COM_PEBEVENTS_NEW_DATA_ENTRY') ?>
				</span>
				<div class="control-group">
					<div class="control-label"><label><?php echo JText::_('COM_PEBEVENTS_EVENTS_START_HOUR');?></label></div>
					<div class="controls"><input type="text" name="hstart" value="" size="80"/></div>
				</div>
				<hr>
			</div>
		</fieldset>
	</div>
	
	<div>
		<input type="hidden" name="id" value="<?php echo (isset($this->event->id)) ? $this->event->id : 0;?>"/>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
