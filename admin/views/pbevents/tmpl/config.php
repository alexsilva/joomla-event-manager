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

$doc = JFactory::getDocument();

$doc->addScript(JURI::root(false).'administrator/components/com_pbevents/scripts/com.purplebeanie.general.js');
$doc->addStyleSheet(JURI::root(false).'administrator/components/com_pbevents/css/com_pbevents_adminstrator.edit.form.css');
?>

<form action="<?php echo JRoute::_('index.php?option=com_pbevents');?>" method="post" name="adminForm" class="form-validate" id="adminForm">
	<div class="span10 form-horizontal">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_PBEVENTS_CONFIGURATION');?></legend>
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_PBEVENTS_SEND_NOTIFICATIONS_TO');?></label></div>
				<div class="controls"><input type="text" name="send_notifications_to" value="<?php echo (isset($this->config->send_notifications_to)) ? $this->config->send_notifications_to : null;?>" size="255"/></div>
			</div>
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_PBBOOKING_FAILED_SUBJECT');?></label></div>
				<div class="controls"><input type="text" name="email_failed_subject" value="<?php echo (isset($this->config->email_failed_subject)) ? $this->config->email_failed_subject : null;?>" size="80"/></div>
			</div>
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_PBBOOKING_FAILED_BODY');?></label></div>
				<div class="controls"><textarea name="email_failed_body" rows="10" cols="40"><?php echo (isset($this->config->email_failed_body)) ? $this->config->email_failed_body : null;?></textarea></div>
			</div>
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_PBBOOKING_SUCCESS_SUBJECT');?></label></div>
				<div class="controls"><input type="text" name="email_success_subject" value="<?php echo (isset($this->config->email_success_subject)) ? $this->config->email_success_subject : null;?>" size="80"></div>
			</div>
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_PBBOOKING_SUCCESS_BODY');?></label></div>
				<div class="controls"><textarea name="email_success_body" rows="10" cols="40"><?php echo (isset($this->config->email_success_body)) ? $this->config->email_success_body : null;?></textarea></div>
			</div>
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_PBEVENTS_DATE_LOCALE');?></label></div>
				
				<div class="controls">
					<select name="date_picker_locale">
						<?php foreach (array('en-US','cs-CZ','de-DE','es-ES','fr-FR','he-IL','it-IT','nl-NL','pl-PL','pt-BR','ru-RU') as $locale) :?>
							<option value="<?php echo $locale;?>"
							<?php echo (isset($this->config->date_picker_locale) && $this->config->date_picker_locale == $locale) ? 'selected="true"' : null;?> ><?php echo $locale;?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="id" value="<?php echo (isset($this->config->id)) ? $this->config->id : 0;?>"/>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
