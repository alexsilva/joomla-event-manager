<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  User.profile
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Provides input for TOS
 *
 * @package     Joomla.Plugin
 * @subpackage  User.profile
 * @since       2.5.5
 */
class JFormFieldCheckboxLink extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  2.5.5
	 */
	protected $type = 'checkboxlink';
	
	/**
	 * Method to get the field input markup.
	 * The checked element sets the field to selected.
	 *
	 * @return  string   The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		if ($this->value == $this->element['value']) {
			//return "removed";
		}
		
		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		
		$value = $this->element['value'] ? (string) $this->element['value'] : '1';
		
		if (empty($this->value))
		{
			$checked = (isset($this->element['checked'])) ? ' checked="checked"' : '';
		}
		else
		{
			$checked = ' checked="checked"';
		}
		
		// Initialize JavaScript field attributes.
		$onclick = $this->element['onclick'] ? ' onclick="' . (string) $this->element['onclick'] . '"' : '';
		
		$checkbox = '<input type="checkbox" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
				. htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"' . $class . $checked . $disabled . $onclick . ' />';
		
		return implode("", array(
			'<div>',
				$checkbox,
				'<a href="'.$this->element['link'].'" target="_blank" style="margin-left:10px;">'.$this->element['info'].'</a>',
			'</div>',
		));
	}
}


















