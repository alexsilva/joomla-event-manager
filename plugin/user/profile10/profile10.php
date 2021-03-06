<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  User.profile
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * An example custom profile plugin.
 *
 * @package     Joomla.Plugin
 * @subpackage  User.profile
 * @since       1.6
 */
class plgUserProfile10 extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		
		JFormHelper::addFieldPath(__DIR__ . '/fields');
	}

	/**
	 * @param   string	$context	The context for the data
	 * @param   integer  $data		The user id
	 * @param   object
	 *
	 * @return  boolean
	 * @since   1.6
	 */
	public function onContentPrepareData($context, $data)
	{
		
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile', 'com_users.user', 'com_users.registration', 'com_admin.profile')))
		{
			return true;
		}

		if (is_object($data))
		{
			$userId = isset($data->id) ? $data->id : 0;

			if (!isset($data->profile) and $userId > 0)
			{
				// Load the profile data from the database.
				$db = JFactory::getDbo();
				$db->setQuery(
					'SELECT profile_key, profile_value FROM #__user_profiles' .
					' WHERE user_id = '.(int) $userId." AND profile_key LIKE 'profile.%'" .
					' ORDER BY ordering'
				);

				try
				{
					$results = $db->loadRowList();
				}
				catch (RuntimeException $e)
				{
					$this->_subject->setError($e->getMessage());
					return false;
				}

				// Merge the profile data.
				$data->profile = array();
				
				foreach ($results as $v)
				{
					$k = str_replace('profile.', '', $v[0]);
					$data->profile[$k] = json_decode($v[1], true);
					if ($data->profile[$k] === null)
					{
						$data->profile[$k] = $v[1];
					}
				}
				
				// dados de inscricoes do usuario.
				$query = $db->getQuery(true);
				$query->select("#__pbevents_rsvps.*,#__pbevents_events.title")->from("#__pbevents_rsvps");
				$query->join("left", "#__pbevents_events on #__pbevents_events.id = #__pbevents_rsvps.event_id");
				$query->where("#__pbevents_rsvps.user_id = ".$userId);
				
				try
				{
					// guardando os dados de inscricao para uso no form
					$data->inscription = $db->setQuery($query)->loadObjectList();
				}
				catch (RuntimeException $e)
				{
					$this->_subject->setError($e->getMessage());
					return false;
				}
				
			}

			if (!JHtml::isRegistered('users.url'))
			{
				JHtml::register('users.url', array(__CLASS__, 'url'));
			}
			if (!JHtml::isRegistered('users.calendar'))
			{
				JHtml::register('users.calendar', array(__CLASS__, 'calendar'));
			}
			if (!JHtml::isRegistered('users.tos'))
			{
				JHtml::register('users.tos', array(__CLASS__, 'tos'));
			}
		}

		return true;
	}

	public static function url($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			$value = htmlspecialchars($value);
			if (substr($value, 0, 4) == "http")
			{
				return '<a href="'.$value.'">'.$value.'</a>';
			}
			else
			{
				return '<a href="http://'.$value.'">'.$value.'</a>';
			}
		}
	}

	public static function calendar($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			return JHtml::_('date', $value, null, null);
		}
	}

	public static function tos($value)
	{
		if ($value)
		{
			return JText::_('JYES');
		}
		else
		{
			return JText::_('JNO');
		}
	}

	/**
	 * @param   JForm	$form	The form to be altered.
	 * @param   array  $data	The associated data for the form.
	 *
	 * @return  boolean
	 * @since   1.6
	 */
	public function onContentPrepareForm($form, $data)
	{
			
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}

		// Check we are manipulating a valid form.
		$name = $form->getName();
		if (!in_array($name, array('com_admin.profile', 'com_users.user', 'com_users.profile', 'com_users.registration')))
		{
			return true;
		}

		// Add the registration fields to the form.
		JForm::addFormPath(__DIR__ . '/profiles');
		
		$form->loadFile('profile', false);
		
		// ------------------------------------------------------------------------------------------------------------------
		// criando o fieldset de eventos
		$fieldset_name = "com_pbevents";
		
		$fieldset = new SimpleXMLElement('<fieldset></fieldset>');
		$fieldset->addAttribute("label", JText::_("PLG_USER_PROFILE_FIELDSET_LEGEND"));
		$fieldset->addAttribute("name", $fieldset_name);
		
		$db = JFactory::getDbo();
		
		if (isset($data->inscription) && count($data->inscription) > 0) {
			foreach($data->inscription as $inscription) {
				
				$key = sprintf("%s-inscription-%d", $fieldset_name, $inscription->id);
				$value = sprintf("%s-rsvps-%d", $fieldset_name, $inscription->id);
				
				$delete = JArrayHelper::getValue($data->profile, $key, null, 'string');
				
				if ($delete == null) {
					$field = $fieldset->addChild("field");
					$field->addAttribute("label", $inscription->title);
					
					$field->addAttribute("id", $key);
					$field->addAttribute("type", "checkboxlink");
					$field->addAttribute("value", $value);
					$field->addAttribute("name", $key);
					$field->addAttribute("description", JText::_("PLG_USER_PROFILE_FIELD_MSG"));
					
					// dialogo de confirmacao da delecao.
					$field->addAttribute("onclick", sprintf("if(this.checked && !confirm('%s')) this.checked=false;", JText::_("PLG_USER_PROFILE_FIELD_MSG_ONCLICK")));
					
					$field->addAttribute("link", JRoute::_("index.php?option=com_pbevents&task=details&id=".$inscription->event_id));
					$field->addAttribute("info", JText::_("PLG_USER_PROFILE_FIELD_INFO"));
					
				} else {
					$query = $db->getQuery(true);
					$query->delete('#__pbevents_rsvps')->where('id='.(int) $inscription->id);
					$db->setQuery($query);
					
					// executando a remoção da inscrição selecionada.
					try { $db->query(); }
					catch (RuntimeException $e) {}
				}
			}
		}
		
		// adiciona o field set
		$form->setField($fieldset, "profile");
		// ------------------------------------------------------------------------------------------------------------------
		
		$fields = array(
			'address1',
			'address2',
			'city',
			'region',
			'country',
			'postal_code',
			'phone',
			'website',
			'favoritebook',
			'aboutme',
			'dob',
			'tos',
		);

		//Change fields description when displayed in front-end
		$app = JFactory::getApplication();
		if ($app->isSite())
		{
			$form->setFieldAttribute('address1', 'description', 'PLG_USER_PROFILE_FILL_FIELD_DESC_SITE', 'profile');
			$form->setFieldAttribute('address2', 'description', 'PLG_USER_PROFILE_FILL_FIELD_DESC_SITE', 'profile');
			$form->setFieldAttribute('city', 'description', 'PLG_USER_PROFILE_FILL_FIELD_DESC_SITE', 'profile');
			$form->setFieldAttribute('region', 'description', 'PLG_USER_PROFILE_FILL_FIELD_DESC_SITE', 'profile');
			$form->setFieldAttribute('country', 'description', 'PLG_USER_PROFILE_FILL_FIELD_DESC_SITE', 'profile');
			$form->setFieldAttribute('postal_code', 'description', 'PLG_USER_PROFILE_FILL_FIELD_DESC_SITE', 'profile');
			$form->setFieldAttribute('phone', 'description', 'PLG_USER_PROFILE_FILL_FIELD_DESC_SITE', 'profile');
			$form->setFieldAttribute('website', 'description', 'PLG_USER_PROFILE_FILL_FIELD_DESC_SITE', 'profile');
			$form->setFieldAttribute('favoritebook', 'description', 'PLG_USER_PROFILE_FILL_FIELD_DESC_SITE', 'profile');
			$form->setFieldAttribute('aboutme', 'description', 'PLG_USER_PROFILE_FILL_FIELD_DESC_SITE', 'profile');
			$form->setFieldAttribute('dob', 'description', 'PLG_USER_PROFILE_FILL_FIELD_DESC_SITE', 'profile');
			$form->setFieldAttribute('tos', 'description', 'PLG_USER_PROFILE_FIELD_TOS_DESC_SITE', 'profile');
		}

		$tosarticle = $this->params->get('register_tos_article');
		$tosenabled = $this->params->get('register-require_tos', 0);

		// We need to be in the registration form, field needs to be enabled and we need an article ID
		if ($name != 'com_users.registration' || !$tosenabled || !$tosarticle)
		{
			// We only want the TOS in the registration form
			$form->removeField('tos', 'profile');
		}
		else
		{
			// Push the TOS article ID into the TOS field.
			$form->setFieldAttribute('tos', 'article', $tosarticle, 'profile');
		}

		foreach ($fields as $field)
		{
			// Case using the users manager in admin
			if ($name == 'com_users.user')
			{
				// Remove the field if it is disabled in registration and profile
				if ($this->params->get('register-require_' . $field, 1) == 0
					&& $this->params->get('profile-require_' . $field, 1) == 0)
				{
					$form->removeField($field, 'profile');
				}
			}
			// Case registration
			elseif ($name == 'com_users.registration')
			{
				// Toggle whether the field is required.
				if ($this->params->get('register-require_' . $field, 1) > 0)
				{
					$form->setFieldAttribute($field, 'required', ($this->params->get('register-require_' . $field) == 2) ? 'required' : '', 'profile');
				}
				else
				{
					$form->removeField($field, 'profile');
				}
			}
			// Case profile in site or admin
			elseif ($name == 'com_users.profile' || $name == 'com_admin.profile')
			{
				// Toggle whether the field is required.
				if ($this->params->get('profile-require_' . $field, 1) > 0)
				{
					$form->setFieldAttribute($field, 'required', ($this->params->get('profile-require_' . $field) == 2) ? 'required' : '', 'profile');
				}
				else
				{
					$form->removeField($field, 'profile');
				}
			}
		}
		return true;
	}

	public function onUserAfterSave($data, $isNew, $result, $error)
	{
			
		$userId	= JArrayHelper::getValue($data, 'id', 0, 'int');

		if ($userId && $result && isset($data['profile']) && (count($data['profile'])))
		{
			try
			{
				//Sanitize the date
				if (!empty($data['profile']['dob']))
				{
					$date = new JDate($data['profile']['dob']);
					$data['profile']['dob'] = $date->format('Y-m-d');
				}

				$db = JFactory::getDbo();
				$db->setQuery(
					'DELETE FROM #__user_profiles WHERE user_id = '.$userId .
					" AND profile_key LIKE 'profile.%'"
				);
				$db->execute();

				$tuples = array();
				$order	= 1;

				foreach ($data['profile'] as $k => $v)
				{
					$tuples[] = '('.$userId.', '.$db->quote('profile.'.$k).', '.$db->quote(json_encode($v)).', '.$order++.')';
				}

				$db->setQuery('INSERT INTO #__user_profiles VALUES '.implode(', ', $tuples));
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}

	/**
	 * Remove all user profile information for the given user ID
	 *
	 * Method is called after user data is deleted from the database
	 *
	 * @param   array  $user		Holds the user data
	 * @param   boolean		$success	True if user was succesfully stored in the database
	 * @param   string  $msg		Message
	 */
	public function onUserAfterDelete($user, $success, $msg)
	{
		
		if (!$success)
		{
			return false;
		}

		$userId	= JArrayHelper::getValue($user, 'id', 0, 'int');

		if ($userId)
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery(
					'DELETE FROM #__user_profiles WHERE user_id = '.$userId .
					" AND profile_key LIKE 'profile.%'"
				);

				$db->execute();
			}
			catch (Exception $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}
}
