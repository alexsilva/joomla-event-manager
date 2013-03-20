<?php 
/**
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// --------------------------------------------------------------------------

class Inscription
{
	public function __construct($event) {
		$this->event = $event;
		$this->user =& JFactory::getUser();
		$this->db =& JFactory::getDbo();
		$this->config = $this->getConfig();
	}
	public function checkToken() {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	}
	//
	public function isQuestUser() {
		return $this->user->guest;
	}
	// avaliza se o usuario ja esta registrado em algum curso.
	public function checkUser() {
		foreach ($this->event->rsvps as $rsvps) {
			$user = JFactory::getUser($rsvps->user_id);
			if ($this->user->email == $user->email)
				return true;
		}
		return false;
	}
	//
	public function addUser() {
		$object = new JObject(array(
			"event_id" => (int)$this->event->id,
			"user_id"  => (int)$this->user->id,
		));
		return $this->db->insertObject("#__pbevents_rsvps", $object, "id");
	}
	// numero de usuário inscritos no evento
	public function count() {
		return count($this->event->rsvps);
	}
	// retorna o numero de participantes atual
	public function current() {
		return ($this->event->max_people - $this->count());
	}
	// avalia as incrições estão fechadas por causa do número de usuários.
	public function isClosed() {
		return ($this->event->max_people > 0 && $this->current() <= 0);
	}
	private function formateDates() {
		$dates = array();
		foreach ($this->event->dates as $date)
			$dates[] = PBEventsHelper::formatDateHours($date->date, $date->hstart, $date->hend, $date->henable);
		return $dates;
	}
	// get the config both from pbevents
	public function getConfig() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')->from('#__pbevents_config');
		$query->where('id = 1');

		return $db->setQuery($query)->loadObject();
	}
	//
	public function execute($status) {
		if ($this->event->email_admin_failure > 0 || $this->event->email_admin_success > 0) {
			$this->sendEmailAdmin($status);
		}
	}
	/**
	 * email the admin of the event with notification
	 * @param mixed the event object
	 * @param string the status that is being sent
	 */
	private function sendEmailAdmin($status)
	{
		$input = JFactory::getApplication()->input;
		$email_body = ($status == 'success') ? $this->config->email_success_body : $this->config->email_failed_body;
		$email_subject = ($status == 'success') ? $this->config->email_success_subject : $this->config->email_failed_subject;

		// dates detail
		$event_details = array('<ul>', sprintf("<li>%s = %s</li>", JText::_('COM_PBEVENTS_EVENT_NAME'), $this->event->title));
		$event_details[] = sprintf("<h3>%s</h3>", JText::_('COM_PBEVENTS_DATES'));

		foreach($this->formateDates() as $date) {
			$event_details[] = sprintf("<li>%s</li>", $date);
		}
		$event_details[] = '</ul>';
		$event_details = implode("\n", $event_details);

		$rsvp_details = array('<ul>');
		$rsvp_details[] = sprintf('<li>%s -  %s</li>', JText::_("COM_PBEVENTS_EMAIL_LABEL"), $this->user->email);
		$rsvp_details[] = sprintf('<li>%s -  %s</li>', JText::_("COM_PBEVENTS_USER_LABEL"), $this->user->username);
		$rsvp_details[] = '</ul>';
		$rsvp_details = implode("\n", $rsvp_details);

		//push the event details and the rsvp details into the email body...
		$email_body = preg_replace('/{\s*%\s*event\s*%\s*}/', $event_details, $email_body);
		$email_body = preg_replace('/{\s*%\s*user\s*%\s*}/', $rsvp_details, $email_body);

		$email_body .= ($status == 'fail') ? '<p>'.$this->db->getErrorMsg().'<p>' : null;
		$email_body .= '<p>'.JText::_('COM_PBEVENTS_REMOTE_ADDR').' '.$_SERVER['REMOTE_ADDR'].'</p>';

		$mailer =& JFactory::getMailer();

		$mailer->addRecipient($this->config->send_notifications_to);
		$mailer->setSubject($email_subject);
		$mailer->isHTML(true);

		$mailer->setBody($email_body);
		$mailer->Send();
	}
}
?>