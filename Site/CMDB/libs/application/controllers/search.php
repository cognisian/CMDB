<?php
class Search_Controller extends Template_Controller {

	// Set the name of the template to use
	public $template = 'site_template';

	protected $sess;

	/**
	 * Constructs the Device controller.
	 */
	public function __construct() {

		parent::__construct();

		$this->sess = Session::instance();
	}

	/**
	 * Gets the list of devices meeting the search criteria.
	 */
	public function list_devices() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Device List';
		$this->template->userName = $this->sess->get('logon_id');
		$this->template->pageID = 'device_list';

		// If the user has logged on then show the view, else show login form
		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Set the device search results array
			$results = array();
			$searchParams = array();

			// Get the query
			$deviceIP = $this->input->get('ip_addr', FALSE);
			$fromIP = $this->input->get('from_ip_addr', FALSE);
			$toIP = $this->input->get('to_ip_addr', FALSE);
			$subNetIP = $this->input->get('ip_subnet', FALSE);
			if ($deviceIP) {

				$deviceIP = ip2long($deviceIP);

				// User requesting to find device by IP
				$query = Doctrine_Query::create()
							->select('d.*')
							->from('Device d')
							->leftJoin('d.NetworkProperty np')
							->leftJoin('d.FunctionalArea fa')
							->where('np.ip_addr = ?', $deviceIP);
				$devices = $query->execute(array(), Doctrine::HYDRATE_RECORD);
				$query->free();

				// Populate an array to avoid all unnecessary device properties
				if ($devices->count() > 0) {
					foreach ($devices as $device) {
						$results[] = array(
							'name' => $device->name,
							'funcArea' => $device->FunctionalArea->name
						);
					}
				}

				// Set the search parameters used
				$searchType = 'IP Address';
				$searchParams[] = 'Device IP';
				$searchParams[] = long2ip($deviceIP);
			}
			else if (($fromIP && $toIP) || $subNetIP) {

				// Check if user requesting a IP range from sub net
				if ($subNetIP) {

					$parseCIDR = split('/', $subNetIP);
					if ($parseCIDR && count($parseCIDR) == 2) {

						$fromIP = ip2long($parseCIDR[0]);
						$ipMask = $parseCIDR[1];

						$toIP = $fromIP + pow(2, (32 - $ipMask)) - 1;
					}
				}
				else {
					$fromIP = ip2long($fromIP);
					$toIP = ip2long($toIP);
				}

				// User requesting a range of IP
				$query = Doctrine_Query::create()
							->select('d.*')
							->from('Device d')
							->leftJoin('d.NetworkProperty np')
							->leftJoin('d.FunctionalArea fa')
							->where('np.ip_addr >= ?', $fromIP)
							->andWhere('np.ip_addr <= ?', $toIP);
				$devices = $query->execute(array(), Doctrine::HYDRATE_RECORD);
				$query->free();

				if ($devices->count() > 0) {
					foreach ($devices as $device) {
						$results[] = array(
							'name' => $device->name,
							'funcArea' => $device->FunctionalArea->name
						);
					}
				}

				//Set the search parameters used
				if ($subNetIP) {
					$searchType = 'IP Subnet';
					$searchParams[] = 'CIDR';
					$searchParams[] = $subNetIP;
				}
				else {
					$searchType = 'IP Address Range';
				}
				$tempFrom = long2ip($fromIP);
				$tempTo = long2ip($toIP);
				$searchParams[] = "from IP {$tempFrom} to IP {$tempTo}";
			}
			else {
				$this->index();
				return;
			}

			// Show the device name search view
			$this->template->content = new View('device_list');
			$this->template->content->devices = $results;
			$this->template->content->searchType = $searchType;
			$this->template->content->searchParams = $searchParams;
		}
		else if ($this->sess->get('logged_in') && $this->sess->get('change_password')) {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('/user/change');
		}
		else {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('main');
		}
	}

	/**
	 * Search for a device by name.
	 */
	public function device_name() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Search For A Device By Name';
		$this->template->userName = NULL;
		$this->template->pageID = 'device_name';

		// If the user has logged on then show the view, else show login form
		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Set template view variables
			$this->template->javascript = html::script(
				array(
					'script/autocomplete'
				),
				FALSE
			);
			$this->template->userName = $this->sess->get('logon_id');

			// Show the device name search view
			$this->template->content = new View('search_device_name');
		}
		else if ($this->sess->get('logged_in') && $this->sess->get('change_password')) {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('/user/change');
		}
		else {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('main');
		}
	}

	/**
	 * Search for a device by IP Address/SubNet.
	 */
	public function device_addr() {

	// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Search For A Device By Address';
		$this->template->userName = NULL;
		$this->template->pageID = 'device_addr';

		// If the user has logged on then show the view, else show login form
		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Set template view variables
			$this->template->javascript = html::script(
				array(
					'script/autocomplete'
				),
				FALSE
			);
			$this->template->userName = $this->sess->get('logon_id');

			// Show the device name search view
			$this->template->content = new View('search_device_addr');
		}
		else if ($this->sess->get('logged_in') && $this->sess->get('change_password')) {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('/user/change');
		}
		else {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('main');
		}
	}

	/**
	 * Search for a device by type
	 */
	public function device_type() {

	// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Search For A Device By Type';
		$this->template->userName = NULL;
		$this->template->pageID = 'device_type';

		// If the user has logged on then show the view, else show login form
		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Set template view variables
			$this->template->javascript = html::script(
				array(
					'script/autocomplete'
				),
				FALSE
			);
			$this->template->userName = $this->sess->get('logon_id');

			// Show the device name search view
			$this->template->content = new View('search_device_type');
		}
		else if ($this->sess->get('logged_in') && $this->sess->get('change_password')) {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('/user/change');
		}
		else {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('main');
		}
	}
}
?>