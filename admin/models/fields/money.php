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
class JFormFieldMoney extends JFormFieldText
{
	protected $type = 'money';
	
	protected function getInput() {
		// formatando com base no locale
		$this->value = PBEventsHelper::formatMoney($this->value);
		return parent::getInput();
	}
}