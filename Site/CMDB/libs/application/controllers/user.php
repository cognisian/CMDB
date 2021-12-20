<?php
class User_Controller extends Template_Controller {

	// Set the name of the template to use
	public $template = 'site_template';

	protected $sess;

	/**
	 * Construct the User controller.
	 */
	public function __construct() {
		parent::__construct();
		$this->sess = Session::instance();
	}

	/**
	 * Display the login form.
	 *
	 * @return void
	 */
	public function index() {

		// Set template properties
		$this->template->javascript = '';
		$this->template->title = 'Log On To CMDB';
		$this->template->userName = NULL;
		$this->template->pageID = 'main';

		// Show log in form
		$this->template->content = new View('main');

		// Insert Content
		$this->template->content->loginForm = new View('login_form');
	}

	/**
	 * Login in a user and redirect back to the requested site or the front
	 * page if none requested.
	 *
	 * @return void
	 */
	public function login() {

		// Set template properties
		$this->template->javascript = '';
		$this->template->title = 'Login to CMDB';
		$this->template->userName = NULL;
		$this->template->pageID = 'main';

		if (!$this->sess->get('logged_in')) {

			// Get logon details
			$logon = $this->input->post('username');
			$password = sha1($this->input->post('password'));

			$this->sess->set('logged_in', FALSE);
			$this->sess->set('change_password', TRUE);

			// Get user details from DB
			$query = Doctrine_Query::create()
				->select('u.logon, u.password, u.is_admin, u.change_password, fa.name')
				->addSelect('u.logon_enabled')
				->from('User u')
				->leftJoin('u.FunctionalArea fa')
				->where('u.logon = ?', array($logon));
			$users = $query->execute(array(), Doctrine::HYDRATE_RECORD);

			// Only 1 user should have been returned
			if ($users->count() == 1) {

				// Get user ORM
				$user = $users[0];

				// Logon only if enabled and username and password are valid
				if ($user->logon_enabled && $user->logon === $logon &&
					$user->password === $password) {

					// Set credentials
					$this->sess->set('logged_in', TRUE);
					$this->sess->set('logon_id', $user->logon);
					$this->sess->set('is_admin', (bool)$user->is_admin);
					$this->sess->set('functional_areas', serialize($user->FunctionalArea));
					$this->sess->set('change_password', FALSE);  // Default until proved innocent

					// Check if the user should change their password
					$redirectURI = '';
					if (((bool)$user->change_password)) {
						// Set change password in sess and redirect
						$this->sess->set('change_password', (bool)$user->change_password);
						$redirectURI = '/user/change';
					}
					else {
						// Redirect to previous site.  If no previous site redirect to main
						$redirectURI = $this->sess->get('refer_path');
						if (!(isset($redirectURI) || $redirectURI === '')) {
							$redirectURI = 'main';
						}
					}

					// Redirect to requested page
					url::redirect($redirectURI);
				}
				else {
					// Redisplay logon
					$this->sess->delete('logged_in');
					$this->sess->delete('change_password');
					$this->index();
				}
			}
			else {
				$this->index();
			}
		}
		else {
			if ($this->sess->get('change_password')) {
				$this->change();
			}
			else {
				$this->index();
			}
		}
	}

	/**
	 * Login in a user and redirect back to the requested site or the front
	 * page if none requested.
	 *
	 * @return void
	 */
	public function logout() {

		// Destroy session
		$this->sess->destroy();

		// Redirect to the main page which will allow relogin
		url::redirect('/');
	}

	/**
	 * Changes a user's password.
	 *
	 * <p>A user is redirected to this action when the user is required to reset
	 * their password, due to the field being set in their User record.</p>
	 *
	 * @return void
	 */
	public function change() {

		// Set template properties
		$this->template->javascript = '';
		$this->template->title = 'Change User Password';
		$this->template->userName = NULL;
		$this->template->pageID = 'change_password';

		// Check that the user has logged on using previous password
		if ($this->sess->get('logged_in') && $this->sess->get('change_password')) {

			// Get current logon details
			$logon = $this->sess->get('logon_id');

			// Show user name
			$this->template->userName = $this->sess->get('logon_id');

			// If submitted the change password form then change the user password
			$checkSubmit = $this->input->post('password_change_submit');
			if (isset($checkSubmit)) {

				$newPassword = $this->input->post('new_password');
				$confPassword = $this->input->post('confirm_password');

				if ($newPassword === $confPassword && $this->_validatePassword($newPassword)) {

					// Get user details from DB
					$query = Doctrine_Query::create()
						->select('u.logon, u.password, u.is_admin, u.change_password')
						->from('User u')
						->where('u.logon = ?', array($logon));
					$users = $query->execute(array(), Doctrine::HYDRATE_RECORD);
					$query->free();

					// Only 1 user should have been returned
					if ($users->count() == 1) {

						// Update user password
						$user = $users[0];

						$user->password = sha1($newPassword);
						$user->change_password = 0;

						$user->save();

						$this->sess->set('change_password', FALSE);

						// Redirect to whereever they were coming from
						$redirectURI = $this->sess->get('refer_path');
						if (!(isset($redirectURI) || $redirectURI === '')) {
							$redirectURI = 'main';
						}

						// Go to whatever was originally requested
						url::redirect($redirectURI);
					}
					else {
						// Show log in form
						$this->template->content = new View('change_password');
						$this->template->content->logon = $logon;
						$this->template->content->error = "Unable to locate user ID {$logon}";
					}
				}
				else {
					// Show log in form
					$this->template->content = new View('change_password');
					$this->template->content->logon = $logon;
					$this->template->content->error = "Either the new and " .
							"confirmation passwords did not match or the new " .
							"password was not sufficently complex.";
				}
			}
			// Else display the change password form
			else {
				// Show log in form
				$this->template->content = new View('change_password');
				$this->template->content->logon = $logon;
			}
		}
		else {
			// User has not logged in so redirect to login screen
			url::redirect('/login');
		}
	}

	/**
	 * Validates a users password to ensure it meets criteria.
	 *
	 * <p>A valid password MUST contain at least 8 chars and no more than 16 chars
	 *  It must contain both upper and lower case characters.  It must contain at
	 *  least 1 numeric character.  It must contain at least one punctuation
	 *  character.</p>
	 *
	 * @param string $password The password to validate.
	 * @return bool TRUE if the password meets criteria, FALSE otherwise
	 */
	function _validatePassword($password) {

		$result = FALSE;

		// This is a regex which uses conditional searches -> '(?=. testcond)' basically it says:
		// A string that has min 8 and max 16 chars AND
		// there is a digit AND
		// there is a non-word char AND
		// there is an upper-case letter AND
		// here is an lower-case letter
		$regex='/(?=^.{8,16}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';
		if (preg_match($regex, $password)) {
			$result = TRUE;
		}

		return $result;
	}
}
?>
