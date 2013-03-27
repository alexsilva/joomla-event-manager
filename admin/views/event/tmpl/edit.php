<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.framework');

$doc = JFactory::getDocument();
// configuração do componente.
$config = JComponentHelper::getParams("com_pbevents");

$picker_locale = $config->get("date_picker_locale", "en-US");

$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/'.'Locale.'.$picker_locale.'.DatePicker.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/Picker.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/Picker.Attach.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/Picker.Date.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com_pbevents.administrator.onstart.js');
$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com.purplebeanie.general.js');
$doc->addStyleSheet(JURI::root(false).'administrator/components/com_pbevents/scripts/datepicker/datepicker_jqui/datepicker_jqui.css');

$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com_pbevents.administrator.date.js');
$doc->addStyleSheet(JURI::root(false).'administrator/components/com_pbevents/css/com_pbevents_adminstrator.edit.form.css');
?>
<script type="text/javascript">
// chamando o validador de joomla
Joomla.submitbutton = function(task) {
	if (task == 'event.cancel' || document.formvalidator.isValid(document.id('adminForm')))
	{
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
}
function remove_clone_hidden(_this) {
	jQuery(_this).parent().find(":hidden").remove(); // clean hidden
}

function clone_as_hidden(_this) {
	var object = jQuery(_this);
	var parent = object.parent();
	var clone = object.clone();
	clone.attr("type","hidden");
	clone.attr("value","0");
	clone.removeAttr("onclick");
	clone.removeAttr("id");
	clone.appendTo(parent);
}

jQuery(document).ready(function() {
	// forcando o uso do locale selecionando
	Locale.use('<?php echo $picker_locale;?>');
	
	date = new DateField({
		date: {'label': '<?php echo JText::_('COM_PBEVENTS_DATE');?>'},
		hstart: {'label': '<?php echo JText::_('COM_PEBEVENTS_EVENTS_START_HOUR');?>'},
		hend: {'label': '<?php echo JText::_('COM_PEBEVENTS_EVENTS_END_HOUR');?>'},
		description: {'label': '<?php echo JText::_('COM_PEBEVENTS_EVENTS_ABOUT_HOUR');?>'},
		henable: {"label": '<?php echo JText::_('COM_PEBEVENTS_EVENTS_HOUR_ENABLE');?>'},
		image: {
			'src': '<?php echo JURI::root(false).'administrator/components/com_pbevents/images/delete.png';?>',
			'label': '<?php echo JText::_('COM_PEBEVENTS_EVENTS_DATE_DELETE');?>'
		},
		dateformat: '<?php echo JText::_('COM_PBEVENTS_DATE_PICKER_FORMAT');?>',
		hourformat: '<?php echo JText::_('COM_PBEVENTS_HOUR_PICKER_FORMAT');?>',
		conteiner: jQuery(".group-date"), sep: "-"
	});
	
	// on event click
	jQuery(".btn-add-control").click( date.createObject );
	
	// adicionando as horas iniciais/finais
	var hstart = jQuery("#jform_hstart");
	hstart.attr("value", "<?php echo $this->item->hstart ?>");
	date.setupPicker(null, hstart);
	
	// configurando a hora global
	var hend = jQuery("#jform_hend");
	hend.attr("value", "<?php echo $this->item->hend ?>");
	date.setupPicker(null, hend);
	
<?php if (isset($this->item->dates)) {
	foreach ($this->item->dates as $object) {
		$pieces = array(
			'date.setup({',
				sprintf('id:%s,', $object->id),
				sprintf('date:"%s",', PBEventsHelper::formatDate($object->date,false)),
				sprintf('hstart:"%s",', $object->hstart),
				sprintf('hend:"%s",', $object->hend),
				sprintf('desc:"%s",', str_replace(array("\r","\n"),array("\\r","\\n"), $object->description)),
				sprintf('check:%s', $object->henable),
			'}); ',
			'date.createObject(null); '
		);	
		echo implode("", $pieces)."\r\n";
	}
};?>
});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_pbevents&layout=edit&id='.(int) $this->item->id); ?>" 
		method="post" class="form-validate" name="adminForm" id="adminForm">
		
	<div class="span10 form-horizontal">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_PBEVENTS_DETAILS');?></legend>
			
			<?php foreach($this->form->getFieldset("event") as $field): ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $field->label; ?>
					</div>
					<div class="controls">
						<?php echo $field->input; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</fieldset>
		
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_PBEVENTS_LEGEND_CONFIGURATION');?></legend>
			
			<?php foreach($this->form->getFieldset("event-config") as $field): ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $field->label; ?>
					</div>
					<div class="controls">
						<?php echo $field->input; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</fieldset>
		
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_PBEVENTS_EVENT_LEGEND_HOURS');?></legend>
			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel("hstart"); ?></div>
				<div class="controls"><?php echo $this->form->getInput("hstart"); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel("hend"); ?></div>
				<div class="controls"><?php echo $this->form->getInput("hend"); ?></div>
			</div>
		</fieldset>
		
		<fieldset class="adminform date-field">
			<legend><?php echo JText::_('COM_PEBEVENTS_EVENTS_DATES');?></legend>
			
			<div class="control-group">
				<div class="control-label btn-add-control">
					<img src="<?php echo JURI::root(false); ?>administrator/components/com_pbevents/images/add.png"/>
					<span><?php echo JText::_('COM_PEBEVENTS_NEW_DATA_ENTRY'); ?></span>
				</div>
			</div>
			<hr>
			<div class="group-date"></div>
		</fieldset>
		
		<div>
			<input type="hidden" name="task" value="event.save" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>



