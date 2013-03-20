<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.modeladmin' );

require_once (dirname(__FILE__) .DIRECTORY_SEPARATOR .'pbevents.php');


// Date processing
class DateInput
{
	public function __construct($id, array &$data)
	{
		$this->_pattern = "/(?P<name>\w+)\-(?P<index>\d+)\-(?P<operation>\w+)/";
		
		// date related fields.
		$this->_fields = array(
			array("name" => "date", "type" => "string", "default" => "0000-00-00", 
				  "format" => array("PBEventsHelper", "formatAtomDate")),
			array("name" => "hstart", "type" => "string", "default" => "00:00:00"),
			array("name" => "hend", "type" => "string", "default" => "00:00:00"),
			array("name" => "henable", "type" => "integer", "default" => 0),
			array("name" => "description", "type" => "string", "default" => "")
		);
		
		$this->mkey = "date";
		
		// filter on post.
		$this->_fieldGroups = array(
			"create" => array(),
			"update" => array(),
			"delete" => array(),
		);
		
		$this->_data = $data;
		$this->event_id = $id;
	}
	//
	private function filterCallback($key) {
		return (is_string($key) && preg_match($this->_pattern, $key, $matches) && $matches["name"] == $this->mkey);
	}
	//
	private function setGroups(array &$fields)
	{
		foreach($fields as $key) {
		
			if (preg_match($this->_pattern, $key, $matches))
			{
				$operation = $matches["operation"];
				$index = $matches["index"];
				$object = new JObject();
				
				foreach($this->_fields as $field) {
					$value = JArrayHelper::getValue($this->_data,
								sprintf("%s-%s-%s", $field["name"], $index, $operation), 
									$field["default"], $field["type"]
								);
					if (isset($field["format"]))
					{
						$value = call_user_func($field["format"], $value);
					}
					$object->set($field["name"], $value);
				}
				if ($operation != "create")
				{
					$object->id = (int)$index;
				}
				$object->event_id = (int) $this->event_id;
				$this->_fieldGroups[$operation][] = $object;
			}
		}
	}
	//
	public function execute() {
		$fields = array_filter(array_keys($this->_data), array($this,"filterCallback"));
		$this->setGroups( $fields );
	}
	//
	public function getObjects($name) {
		return $this->_fieldGroups[ $name ];
	}
}

class PBEventsModelEvent extends JModelAdmin
{
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param       type    The table type to instantiate
	 * @param       string  A prefix for the table class name. Optional.
	 * @param       array   Configuration array for model. Optional.
	 * @return      JTable  A database object
	 * @since       2.5
	 */
	public function getTable($type = 'Events', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param       array   $data           Data for the form.
	 * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
	 * @return      mixed   A JForm object on success, false on failure
	 * @since       2.5
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_pbevents.event', 'event',
				array('control' => 'jform', 'load_data' => $loadData));
		 
		if (empty($form))
		{
			return false;
		}
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return      mixed   The data for the form.
	 * @since       2.5
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_pbevents.edit.event.data', array());
		if (empty($data))
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
	/**
	 * Method to get the pbevents configs.
	 *
	 * @return      mixed   The data for the form.
	 * @since       2.5
	 */
	public function getConfig() {
		$db =& JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select("*")->from("#__pbevents_config");
		$query->where("id = 1"); // unique id;
		
		return $db->setQuery($query)->loadObject();
		
	}
	
	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItem($pk = null) {
		if ($item = parent::getItem( $pk ))
		{
			$model = new PBEventsModelPBEvents();
			$model->setRelated( $item ); // set related data
			
			$item->config = $this->getConfig(); // componente config
		}
		return $item;
	}
	
	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   12.2
	 */
	public function save(array $data)
	{
		// restaurando a situação dos valores em dinheiro.
		$price = JArrayHelper::getValue($data, "price", 0.00);
		$data["price"] = PBEventsHelper::formatFloatMoney($price);
		
		if ($result = parent::save($data)) // on save success
		{
			// primary key id - obtendo a chave primária no caso de edição/adição.
			$key = $this->getTable()->getKeyName();
			$event_id = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName().'.id');
			
			$input = new DateInput($event_id, $_POST);
			$input->execute(); // executa extracao de dados
			
			$db =& JFactory::getDbo();
			
			try
			{
				// insert dates
				foreach ($input->getObjects("create") as $object)
				{
					$db->insertObject("#__pbevents_events_dates", $object, "id");
				}
			} catch (RuntimeException $e) {
				$this->setError($e->getMessage());
			}
			try
			{
				// update dates
				foreach ($input->getObjects("update") as $object)
				{
					$db->updateObject("#__pbevents_events_dates", $object, 'id');
				}
			} catch (RuntimeException $e) {
				$this->setError($e->getMessage());
			}
			try
			{
				// delete dates
				foreach ($input->getObjects("delete") as $object)
				{
					$query = $db->getQuery(true);
					
					$query->delete('#__pbevents_events_dates');
					$query->where("id = " .(int) $object->id);
					
					$db->setQuery($query);
					$db->query();
				}
			} catch (RuntimeException $e) {
				$this->setError($e->getMessage());
			}
		}
		return $result; // save result
	}
}
