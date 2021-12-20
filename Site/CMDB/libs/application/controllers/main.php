<?php
class Main_Controller extends Template_Controller {

	// Set the name of the template to use
	public $template = 'site_template';

	protected $sess;

	/**
	 * Constructs the Main controller.
	 */
	public function __construct() {

		parent::__construct();

		$this->sess = Session::instance();
	}

	/**
	 * Display the main page.
	 *
	 * When the main page is loaded check if user logged in (cookie).  If logged in
	 * then display the navigation menu else show the log in form.  When the navigation
	 */
	public function index() {

		// Set template view variables
		$this->template->title = 'Welcome to CMDB';
		$this->template->javascript = '';
		$this->template->userName = NULL;
		$this->template->pageID = 'main';

		// If user logged in then show the navigation else show login
		if (!$this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {

				// Set path we are currently at for return when logged in only if not
				// previously set as may have been sent here to login
				if (!$this->sess->get('refer_path', FALSE)) {
					$this->sess->set('refer_path', url::current(TRUE));
				}

				// Set the sub view to MAIN view
				$this->template->content = new View('main');

				// Show login form
				$this->template->content->loginForm = new View('login_form');
			}
			else {
				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
		}
		else {
			// Show user name
			$this->template->userName = $this->sess->get('logon_id');

			if ($this->sess->get('change_password')) {

				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
			else {
				// Set the sub view to MAIN view
				$this->template->content = new View('main');
			}
		}
	}
}
?>