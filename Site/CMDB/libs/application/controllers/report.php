<?php
class Report_Controller extends Template_Controller {

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
	 * Generates the device details report by Selected Site
	 */
	public function device_site() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Report By Sites';
		$this->template->userName = NULL;
		$this->template->pageID = 'report_sites';

		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Insert Navigation information
			$this->template->userName = $this->sess->get('logon_id');

			$this->template->content = new View('report_sites');

			$this->template->content->reportType = 'Site';
			$this->template->content->reportGen = FALSE;
			$this->template->content->reportEmail = '';

			// Generate the select boxes for Sites (ie cities)
			$this->template->content->sites = $this->_get_site_list();
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
	 * Generates the report by selected Operational Area
	 */
	public function device_area() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Report By Site And Operational Area';
		$this->template->userName = NULL;
		$this->template->pageID = 'report_op_area';

		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Insert Navigation information
			$this->template->userName = $this->sess->get('logon_id');

			$this->template->content = new View('report_op_area');

			$this->template->content->reportType = 'Operational Area';
			$this->template->content->reportGen = FALSE;
			$this->template->content->reportEmail = '';

			// Generate the select boxes for Sites (ie cities)
			$this->template->content->sites = $this->_get_site_list();

			$this->template->content->opAreas =
					Doctrine::getTable('OperationalArea')->findAll(Doctrine::HYDRATE_ARRAY);
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
	 * Generates the Switch Device report
	 */
	public function device_switch() {

		// Set template view variables
		$this->template->javascript = html::script(
			array(
				'script/report_switch'
			)
		);
		$this->template->title = 'Report By Switch';
		$this->template->userName = NULL;
		$this->template->pageID = 'report_switch';

		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Insert Navigation information
			$this->template->userName = $this->sess->get('logon_id');

			$this->template->content = new View('report_switch');

			$this->template->content->reportType = 'Switch';
			$this->template->content->reportGen = FALSE;
			$this->template->content->reportEmail = '';
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
	 * Generates the Phone device report.
	 */
	public function device_voice() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Report By Voice';
		$this->template->userName = NULL;
		$this->template->pageID = 'report_voice';

		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Insert Navigation information
			$this->template->userName = $this->sess->get('logon_id');

			$this->template->content = new View('report_voice');

			$voiceTypes = array();
			$analogPhone = Doctrine::getTable('DeviceType')->findOneByType('Phone');
			$voiceTypes[$analogPhone->id] = $analogPhone->type;
			$voipPhone = Doctrine::getTable('DeviceType')->findOneByType('IP Phone');
			$voiceTypes[$voipPhone->id] = $voipPhone->type;
			$this->template->content->voices = $voiceTypes;

			$this->template->content->reportType = 'Voice';
			$this->template->content->reportGen = FALSE;
			$this->template->content->reportEmail = '';
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
	 * Generates the report by selected Business Unit
	 */
	public function device_busunit() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Report By Business Unit';
		$this->template->userName = NULL;
		$this->template->pageID = 'report_busunit';

		if ($this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {

				// Insert Navigation information
				$this->template->userName = $this->sess->get('logon_id');

				// Show the device name search view
				$this->template->content = new View('report_construct');
			}
			else {
				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
		}
		else {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('main');
		}
	}

	/**
	 * Generates the report by selected Operational Area and Business Unit
	 */
	public function device_area_busunit() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Report By Operation Area and Business Unit';
		$this->template->pageID = 'report_oparea_busunit';

		if ($this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {
				// Insert Navigation information
				$this->template->userName = $this->sess->get('logon_id');

				// Show the device name search view
				$this->template->content = new View('report_construct');
			}
			else {
				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
		}
		else {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('main');
		}
	}

	/**
	 * Generates the device details report by Selected Site
	 */
	public function generate_report() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Report By ';
		$this->template->userName = NULL;
		$this->template->pageID = 'report';

		if ($this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {

				// Insert Navigation information
				$this->template->userName = $this->sess->get('logon_id');

				// Get site list
				$sites = $this->_get_site_list();

				// Get the list of suspect devices
				$query = Doctrine_Query::create()
					->select('d.name, d.asset_tag, dt.type, fa.name, aa.name')
					->addSelect('oa.id, oa.type, l.site_id, l.floor, s.id, s.code')
					->addSelect('np.*, pn.*, sp.*, do.*')
					->from('Device d')
					->leftJoin('d.DeviceType dt')
					->leftJoin('d.FunctionalArea fa')
					->leftJoin('d.ApplicationArea aa')
					->leftJoin('d.OpArea oa')
					->leftJoin('d.Location l')
					->leftJoin('l.Site s')
					->leftJoin('d.NetworkProperty np')
					->leftJoin('np.ParentNetwork pn')
					->leftJoin('d.ServiceProperty sp')
					->leftJoin('sp.DependsOn do');

				$site_id = $this->input->post('site');
				if (!empty($site_id) && $site_id != 0) {

					$query->whereIn('s.code', $sites[$site_id]['sites']);

					$report = $this->input->post('report-type');
					$valid = TRUE;
					switch ($report) {

						case 'by_sites' :
							$this->template->title .= 'Sites';

							$redirect = '/report/device_site';
							break;

						case 'by_op_area' :
							$this->template->title .= 'Operational Area';
							$redirect = '/report/device_area';

							$opArea = $this->input->post('op_area');
							if ($opArea == 0) {
								$this->template->content = new View('report_op_area');

								$this->template->content->opAreas =
									Doctrine::getTable('OperationalArea')->findAll(Doctrine::HYDRATE_ARRAY);

								$this->template->content->reportType = 'Site';
								$this->template->content->reportGen = FALSE;

								$this->template->content->sites = $sites;

								$this->template->content->errorMsg = 'An operational area must be selected.';
								$valid = FALSE;
								break;
							}

							$query->andWhere('oa.id = ?', $opArea);
							break;

						default :
							break;
					}

					if ($valid) {

						$devices = $query->execute(array(), Doctrine::HYDRATE_ARRAY);
						$query->free();

						$fileID = $this->_generateDeviceReport($devices);

						$fileCSV = '/tmp/' . $fileID . '.csv';
						$this->auto_render = false;
						$downFileName = "DeviceReport_{$sites[$site_id]['label']}.csv";
						$return = download::force($fileCSV, NULL, $downFileName);

						unlink($fileCSV);

						url::redirect($redirect);
					}
				}
				else {
					$this->template->content = new View('report_sites');

					$this->template->content->reportType = 'Site';
					$this->template->content->reportGen = FALSE;

					$this->template->content->sites = $sites;

					$this->template->content->errorMsg = 'A site must be selected.';
				}
			}
			else {
				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
		}
		else {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('main');
		}
	}

	/**
	 * Generates the switch device details report by name
	 */
	public function generate_switch_report() {

		// Set template view variables
		$this->template->javascript = html::script(
			array(
				'script/report_switch'
			)
		);
		$this->template->title = 'Report By ';
		$this->template->userName = NULL;
		$this->template->pageID = 'report_switch';

		if ($this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {

				// Insert Navigation information
				$this->template->userName = $this->sess->get('logon_id');

				$this->template->title .= 'Switch';
				$redirect = '/report/device_switch';

				$switchName = $this->input->post('name');
				if (isset($switchName)) {

					// switch is different query
					$query = Doctrine_Query::create()
						->select('d.name, d.asset_tag, dt.type, fa.name, aa.name')
						->addSelect('oa.id, oa.type, l.site_id, l.floor, s.id, s.code')
						->addSelect('np.*, cn.*, cd.name, cd.asset_tag')
						->addSelect('cl.site_id, cl.floor, cs.id, cs.code')
						->from('Device d')
						->leftJoin('d.Location l')
						->leftJoin('l.Site s')
						->leftJoin('d.NetworkProperty np')
						->leftJoin('np.ChildNetwork cn')
						->leftJoin('cn.Device cd')
						->leftJoin('cd.OpArea oa')
						->leftJoin('cd.ApplicationArea aa')
						->leftJoin('cd.Location cl')
						->leftJoin('cl.Site cs')
						->where('d.name = ?', $switchName);

					$devices = $query->execute(array(), Doctrine::HYDRATE_ARRAY);
					$query->free();

					$fileID = $this->_generateSwitchReport($devices[0]);

					$fileCSV = '/tmp/' . $fileID . '.csv';
					$this->auto_render = false;
					$downFileName = "SwitchReport.csv";
					$return = download::force($fileCSV, NULL, $downFileName);

					unlink($fileCSV);

					url::redirect($redirect);
				}
				else {
					$this->template->content = new View('report_switch');

					$this->template->content->reportType = 'Switch';
					$this->template->content->reportGen = FALSE;

					$this->template->content->errorMsg = 'A switch must be selected.';
				}
			}
			else {
				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
		}
		else {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('main');
		}
	}

	/**
	 * Generates the voice device details report.
	 */
	public function generate_voice_report() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Report By ';
		$this->template->userName = NULL;
		$this->template->pageID = 'report_voice';

		if ($this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {

				// Insert Navigation information
				$this->template->userName = $this->sess->get('logon_id');

				$this->template->title .= 'Voice';
				$redirect = '/report/device_voice';

				$voiceType = $this->input->post('voice');
				if (isset($voiceType)) {

					// switch is different query
					$query = Doctrine_Query::create()
						->select('d.name, dt.id, dt.type')
						->addSelect('do.fName, do.lName')
						->addSelect('l.site_id, l.floor, s.id, s.code')
						->addSelect('np.*, pn.*, pd.name')
						->from('Device d')
						->leftJoin('d.DeviceType dt')
						->leftJoin('d.Owner do')
						->leftJoin('d.Location l')
						->leftJoin('l.Site s')
						->leftJoin('d.NetworkProperty np')
						->leftJoin('np.ParentNetwork pn')
						->leftJoin('pn.Device pd')
						->where('dt.id = ?', $voiceType);

					$devices = $query->execute(array(), Doctrine::HYDRATE_ARRAY);
					$query->free();

					$fileID = $this->_generateVoiceReport($devices);

					$fileCSV = '/tmp/' . $fileID . '.csv';
					$this->auto_render = false;
					$downFileName = "VoiceReport.csv";
					$return = download::force($fileCSV, NULL, $downFileName);

					unlink($fileCSV);

					url::redirect($redirect);
				}
				else {
					$this->template->content = new View('report_voice');

					$this->template->content->reportType = 'Voice';
					$this->template->content->reportGen = FALSE;

					$this->template->content->errorMsg = 'A Voice Type must be selected.';

					$voiceTypes = array();
					$analogPhone = Doctrine::getTable('DeviceType')->findOneByType('Phone');
					$voiceTypes[$analogPhone->id] = $analogPhone->type;
					$voipPhone = Doctrine::getTable('DeviceType')->findOneByType('IP Phone');
					$voiceTypes[$voipPhone->id] = $voipPhone->type;
					$this->template->content->voices = $voiceTypes;
				}
			}
			else {
				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
		}
		else {
			// Set the current URI so we can return when logged in
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('main');
		}
	}

	/**
	 * Generates the site (grouping sites in same city)
	 *
	 * @return array An array of site information.
	 */
	private function _get_site_list() {
		// Generate the select boxes for Sites (ie cities)
		return array(
			array(
				'label' => 'Please Select ...',
				'sites' => array()
			),
			array(
				'label' => 'Vancouver',
				'sites' => array('vne5')
			),
			array(
				'label' => 'Calgary',
				'sites' => array('clc3', 'clww')
			),
			array(
				'label' => 'Winnipeg',
				'sites' => array('wpfo')
			),
			array(
				'label' => '8PAC',
				'sites' => array('to8p')
			),
			array(
				'label' => '4PAC',
				'sites' => array('to4p')
			),
			array(
				'label' => '1PAC',
				'sites' => array('to1p')
			),
			array(
				'label' => 'RSP',
				'sites' => array('mir2', 'mirs')
			),
			array(
				'label' => 'Tech Ave',
				'sites' => array('mita')
			),
			array(
				'label' => 'Toronto',
				'sites' => array('tofr', 'tofr2', 'toks')
			),
			array(
				'label' => 'Montreal',
				'sites' => array('mtbr', 'mttp')
			),
			array(
				'label' => 'Halifax',
				'sites' => array('hadk', 'hagf')
			),
			array(
				'label' => 'Dallas',
				'sites' => array('tx-dlhr')
			),
			array(
				'label' => 'Memphis',
				'sites' => array('tn-mebp')
			),
			array(
				'label' => 'Nashville',
				'sites' => array('tn-nsrd')
			),
			array(
				'label' => 'Chicago',
				'sites' => array('cglm', 'cgwm')
			),
			array(
				'label' => 'Knoxville',
				'sites' => array('tn-kxmp')
			),
			array(
				'label' => 'Atlanta',
				'sites' => array('atpp')
			),
			array(
				'label' => 'Miami',
				'sites' => array('fl-mina')
			),
			array(
				'label' => 'Orlando',
				'sites' => array('fl-orc1', 'fl-orc2', 'fl-orpr', 'orha')
			),
			array(
				'label' => 'Charlotte',
				'sites' => array('chex', 'chwp')
			),
			array(
				'label' => 'Durham',
				'sites' => array('nc-dhod')
			),
			array(
				'label' => 'Richmond',
				'sites' => array('va-rhpr')
			),
			array(
				'label' => 'Baltimore',
				'sites' => array('md-gbsa')
			),
			array(
				'label' => 'Rutherford',
				'sites' => array('nj-rdrt')
			),
		);
	}

	/**
	 * Generates a PDF suspect report.
	 *
	 * @param Doctrine_Collection $devices The set of suspect devices.
	 * @return string A unique file ID which the name of the file.  The CSV file is $fileID.csv.
	 */
	private function _generateDeviceReport($devices) {

		// Create the CSV document
		$rand = md5(time());
		$fileID = $rand;

		// Add Headers to CSV
		$csvData = 'Host Name, Asset Tag, Device Type, Op Area,';
		$csvData .= 'Hardware Group, Application Area, Floor, Site,';

		$csvData .= 'NIC, IP Address, Local Blade/Port, Switch (NIC/Blade/Port/VLAN),';

		$csvData .= 'Local Service / Port, Remote Service / Port';
		$csvData .= "\n";

		$deviceColCount = 8;
		$netColCount = 4;
		$servColCount = 2;

		$currRow = 0;

		$rows = array();
		foreach ($devices as $device) {

			$startRow = count($rows);
			$rows[] = '';  // Create new row for new device

			// Generate device property data for CSV table
			$rows[$startRow] .= (isset($device['name'])) ?
					$device['name'] : '?';

			$rows[$startRow] .= ',';
			$rows[$startRow] .= (isset($device['asset_tag'])) ?
					$device['asset_tag'] : '?' ;

			$rows[$startRow] .= ',';
			$rows[$startRow] .= (isset($device['DeviceType'])) ?
					$device['DeviceType']['type'] : '?';

			$rows[$startRow] .= ',';
			$rows[$startRow] .= (isset($device['OpArea'])) ?
					$device['OpArea']['type'] : '?';

			$rows[$startRow] .= ',';
			$rows[$startRow] .= (isset($device['FunctionalArea'])) ?
					$device['FunctionalArea']['name'] : '?';

			$rows[$startRow] .= ',';
			$rows[$startRow] .= (isset($device['ApplicationArea'])) ?
					$device['ApplicationArea']['name'] : '?';

			$rows[$startRow] .= ',';
			$rows[$startRow] .= (isset($device['Location']) &&
					isset($device['Location']['floor']) && !empty($device['Location']['floor'])) ?
					strtoupper($device['Location']['floor']) : '?';

			$rows[$startRow] .= ',';
			$rows[$startRow] .= (isset($device['Location']) && isset($device['Location']['Site'])) ?
					strtoupper($device['Location']['Site']['code']) : '?';

			// Reset the current row pointer back to first line of this device row block
			$currRow = $startRow;

			// Add Network properties and dependencies to CSV
			if (isset($device['NetworkProperty']) &&
					count($device['NetworkProperty']) > 0) {

				$firstProp = TRUE;
				foreach ($device['NetworkProperty'] as $netProp) {

					if (!$firstProp) {
						$currRow++;
						$rows[] .= str_repeat(',', $deviceColCount - 1);
					}

					$rows[$currRow] .= ',';
					$rows[$currRow] .= (isset($netProp['nic'])) ?
							$netProp['nic'] : '?';

					$rows[$currRow] .= ',';
					$rows[$currRow] .= (isset($netProp['ip_addr'])) ?
							long2ip($netProp['ip_addr']) : '?';

					$localBlade = (isset($netProp['conn_blade'])) ?
							$netProp['conn_blade'] : '?';
					$localPort = (isset($netProp['conn_port'])) ?
							$netProp['conn_port'] : '?';

					$rows[$currRow] .= ',';
					$rows[$currRow] .= "Blade: {$localBlade} Port: {$localPort}";

					$firstProp = FALSE;

					// Add network dependencies
					if (isset($netProp['ParentNetwork']) &&
							count($netProp['ParentNetwork']) > 0) {

						$firstDep = TRUE;
						foreach ($netProp['ParentNetwork'] as $parentNet) {

							$switch = Doctrine::getTable('Device')->find($parentNet['device_id']);

							$switchNIC = (isset($parentNet['nic'])) ?
									$parentNet['nic'] : '?';
							$switchBlade = (isset($parentNet['conn_blade'])) ?
									$parentNet['conn_blade'] : '?';
							$switchPort = (isset($parentNet['conn_port'])) ?
									$parentNet['conn_port'] : '?';
							$switchVLAN = (isset($parentNet['conn_vlan'])) ?
									$parentNet['conn_vlan'] : '?';

							if (!$firstDep) {
								$currRow++;
								// We use (netColCount - 1) as col count includes
								// net dep. The extra - 1 is because comma is prepended
								// at the location of data being adde to row
								$rows[] .= str_repeat(',', $deviceColCount + ($netColCount - 1) - 1);
							}

							$rows[$currRow] .= ',';
							$rows[$currRow] .= "{$switch['name']} (NIC: {$switchNIC} " .
									"Blade: {$switchBlade} Port: {$switchPort} " .
									"VLAN: {$switchVLAN})";

							$firstDep = FALSE;
						}
					}
					else {
						$rows[$currRow] .= ',?';
					}
				}
			}
			else {
				$rows[$currRow] .= ',?,?,?';
			}

			// Reset the current row pointer back to first line of this device row block
			$currRow = $startRow;

			// Add Service properties
			if (isset($device['ServiceProperty']) &&
					count($device['ServiceProperty']) > 0) {

				$firstProp = TRUE;
				foreach ($device['ServiceProperty'] as $servProp) {

					if (!$firstProp) {
						$currRow++;
						// Check to see if we need to add row
						if (array_key_exists($currRow, $rows)) {
							$rows[$currRow] .= str_repeat(',', $deviceColCount + $netColCount - 1);
						}
						else {
							$rows[] .= str_repeat(',', $deviceColCount + $netColCount - 1);
						}
					}

					$servProp['Service'] = Doctrine::getTable('Service')->find($servProp['service_id'], Doctrine::HYDRATE_ARRAY);

					$servName = (isset($servProp['Service']['name'])) ?
							$servProp['Service']['name'] : '?';
					$servPort = (isset($servProp['Service']['port'])) ?
							$servProp['Service']['port'] : '?';
					$servProto = (isset($servProp['Service']['protocol'])) ?
							$servProp['Service']['protocol'] : '?';

					$rows[$currRow] .= ',';
					$rows[$currRow] .= "{$servName} {$servPort} ({$servProto})";

					$firstProp = FALSE;

					// Add service dependencies
					if (isset($servProp['DependsOn']) &&
							count($servProp['DependsOn']) > 0) {

						$depRow = $currRow;

						$firstDep = TRUE;
						foreach ($servProp['DependsOn'] as $parentServProp) {

							if (!$firstDep) {
								$depRow++;
								if (array_key_exists($depRow, $rows)) {
									$rows[$depRow] .= str_repeat(',', $deviceColCount +
											$netColCount + ($servColCount -1) - 1);
								}
								else {
									$rows[] .= str_repeat(',', $deviceColCount +
											$netColCount + ($servColCount - 1) - 1);
								}
							}

							$parentDevice = Doctrine::getTable('Device')->find($parentServProp['device_id']);

							$parentServProp['Service'] = Doctrine::getTable('Service')->find($parentServProp['service_id'], Doctrine::HYDRATE_ARRAY);

							$parentServName = (isset($parentServProp['Service']['name'])) ?
									$parentServProp['Service']['name'] : '?';
							$parentServPort = (isset($parentServProp['Service']['port'])) ?
									$parentServProp['Service']['port'] : '?';
							$parentServProto = (isset($parentServProp['Service']['protocol'])) ?
									$parentServProp['Service']['protocol'] : '?';

							$rows[$depRow] .= ',';
							$rows[$depRow] .= "{$parentDevice['name']} => {$parentServName} " .
									"{$parentServPort} ({$parentServProto})";

							$firstDep = FALSE;
						}

						$currRow = $depRow;
					}
					else {
						$rows[$currRow] .= ',?';
					}
				}
			}
			else {
				$rows[$currRow] .= ',?,?';
			}
		}

		// Terminate each row with new line
		foreach ($rows as $row) {
			$csvData .= $row . "\n";
		}

		// Save CSV to file
		file_put_contents("/tmp/{$fileID}.csv", $csvData);

		return $fileID;
	}

	/**
	 * Generates a PDF suspect report.
	 *
	 * @param Doctrine_Collection $devices The set of suspect devices.
	 * @return string A unique file ID which the name of the file.  The CSV file is $fileID.csv.
	 */
	private function _generateSwitchReport($device) {

		// Create the CSV document
		$rand = md5(time());
		$fileID = $rand;

		// Add Headers to CSV
		$csvData = 'Site, Floor, ';
		$csvData .= 'Asset Tag, Switch, NIC, Blade/Port, ';
		$csvData .= 'Attached Device, Op Area, App Area, Site, Location';
		$csvData .= "\n";

		$deviceColCount = 4;

		$currRow = 0;

		$rows = array();

		$startRow = count($rows);
		$rows[] = '';  // Create new row for new device

		// Generate device property data for CSV table
		$rows[$startRow] .= (isset($device['Location']) && isset($device['Location']['Site'])) ?
				strtoupper($device['Location']['Site']['code']) : '?';

		$rows[$startRow] .= ',';
		$rows[$startRow] .= (isset($device['Location'])) ?
				$device['Location']['floor'] : '?' ;

		$rows[$startRow] .= ',';
		$rows[$startRow] .= (isset($device['asset_tag'])) ?
				$device['asset_tag'] : '?';

		$rows[$startRow] .= ',';
		$rows[$startRow] .= (isset($device['name'])) ?
				$device['name'] : '?';

		// Reset the current row pointer back to first line of this device row block
		$currRow = $startRow;

		// Add Network properties and dependencies to CSV
		if (isset($device['NetworkProperty']) &&
				count($device['NetworkProperty']) > 0) {

			$firstProp = TRUE;
			foreach ($device['NetworkProperty'] as $netProp) {

				if (!$firstProp) {
					$currRow++;
					$rows[] .= str_repeat(',', $deviceColCount - 1);
				}

				$rows[$currRow] .= ',';
				$rows[$currRow] .= (isset($netProp['nic'])) ?
						"'".$netProp['nic'] : '?';

				$localBlade = (isset($netProp['conn_blade'])) ?
						$netProp['conn_blade'] : '?';
				$localPort = (isset($netProp['conn_port'])) ?
						$netProp['conn_port'] : '?';

				$rows[$currRow] .= ',';
				$rows[$currRow] .= "Blade: {$localBlade} Port: {$localPort}";

				$firstProp = FALSE;

				// Add network dependencies
				if (isset($netProp['ChildNetwork']) &&
						count($netProp['ChildNetwork']) > 0) {

					// Print the list of attached device
					foreach ($netProp['ChildNetwork'] as $childNet) {

						if (isset($childNet['Device'])) {

							$attachDevice = $childNet['Device'];

							$rows[$currRow] .= ',';
							$rows[$currRow] .= (isset($attachDevice['name'])) ?
									$attachDevice['name'] : '?';

							$rows[$currRow] .= ',';
							$rows[$currRow] .= (isset($attachDevice['OpArea'])) ?
									$attachDevice['OpArea']['type'] : '?';

							$rows[$currRow] .= ',';
							$rows[$currRow] .= (isset($attachDevice['ApplicationArea'])) ?
									$attachDevice['ApplicationArea']['name'] : '?';

							$rows[$currRow] .= ',';
							$rows[$currRow] .= (isset($attachDevice['Location']) && isset($attachDevice['Location']['Site'])) ?
									strtoupper($attachDevice['Location']['Site']['code']) : '?';

							$rows[$currRow] .= ',';
							$rows[$currRow] .= (isset($attachDevice['Location']) && !empty($attachDevice['Location']['floor'])) ?
									'Floor ' . $attachDevice['Location']['floor'] : 'Floor: ?';
						}
					}
				}
			}
		}

		// Terminate each row with new line
		foreach ($rows as $row) {
			$csvData .= $row . "\n";
		}

		// Save CSV to file
		file_put_contents("/tmp/{$fileID}.csv", $csvData);

		return $fileID;
	}

/**
	 * Generates a PDF voice device report.
	 *
	 * @param Doctrine_Collection $devices The set of suspect devices.
	 * @return string A unique file ID which the name of the file.  The CSV file is $fileID.csv.
	 */
	private function _generateVoiceReport($devices) {

		// Create the CSV document
		$rand = md5(time());
		$fileID = $rand;

		// Add Headers to CSV
		$csvData = 'Phone, Location, User Names, Switch, Blade/Port';
		$csvData .= "\n";

		$phoneColCount = 2;

		$curRow = 0;
		$rows = array();
		foreach ($devices as $device) {

			$startRow = count($rows);

			$rows[$startRow] = (isset($device['name'])) ?
					$device['name'] : '?';

			$rows[$startRow] .= ',';
			$rows[$startRow] .= (isset($device['Location']) && isset($device['Location']['Site'])) ?
					strtoupper($device['Location']['Site']['code']) : '?';

			$currRow = $startRow;

			$rows[$startRow] .= ',';
			$userName = '';
			if (isset($device['Owner'])) {
				$first = TRUE;
				foreach ($device['Owner'] as $owner) {
					if (!$first) {
						$rows[] .= str_repeat(',', $phoneColCount); // Add empty columns
					}
					else {
						$first = FALSE;
					}
					$rows[$currRow] .= $owner['fname'] . ' ' . $owner['lname'];
					$currRow++;
				}
			}
			else {
				// No Owner
				$rows[$currRow] .= '?';
			}

			// Add Network properties and dependencies to CSV
			if (isset($device['NetworkProperty'][0]['ParentNetwork']) &&
					count($netProp['ParentNetwork']) > 0) {

				$netProp = $device['NetworkProperty'][0];

				// Print the list of attached device
				foreach ($netProp['ParentNetwork'] as $parentNet) {

					if (isset($parentNet['Device'])) {

						$attachDevice = $parentNet['Device'];

						$rows[$startRow] .= ',';
						$rows[$startRow] .= (isset($attachDevice['name'])) ?
								$attachDevice['name'] : '?';

						$localBlade = (isset($netProp['conn_blade'])) ?
								$netProp['conn_blade'] : '?';
						$localPort = (isset($netProp['conn_port'])) ?
								$netProp['conn_port'] : '?';

						$rows[$startRow] .= ',';
						$rows[$startRow] .= "Blade: {$localBlade} Port: {$localPort}";
					}
				}
			}
		}

		foreach ($rows as $row) {
			$csvData .= $row . "\n";
		}

		// Save CSV to file
		file_put_contents("/tmp/{$fileID}.csv", $csvData);

		return $fileID;
	}

	/**
	 * Emails a PDF suspect report to provided address.
	 *
	 * @param string $email The email address to ssend the CSV report.
	 * @param string $fname The first name of th erecipient of emailed report.
	 * @param string $lname The last name of th erecipient of emailed report.
	 * @param string $title The report title.
	 * @param string $msg The report description.
	 * @param string $file The report file location to send.
	 * @param string $nicefile The filename to use as file attachement name.
	 * @return void
	 */
	private function _emailReport($email, $fname, $lname, $title, $desc, $file, $nicefile) {

		// Generate the email message
		$message = $title;
		$message .= "\n\n";
		$message .= $desc;

		// Generate a boundary string
		$semi_rand = md5(time());
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

		// Add the headers for a message and file attachment
		$headers = "To: {$fname} {$lname} <{$email}>\n" .
			"From: Network Segmentation <networkseg@symcor.com>\n" .
			"X-Mailer: PHP 5.2.x";

		$headers .= "\nMIME-Version: 1.0\n" .
			"Content-Type: multipart/mixed;\n" .
			" boundary=\"{$mime_boundary}\"";

		// Add a multipart boundary above the plain message
		$message = "This is a multi-part message in MIME format.\n\n" .
				"--{$mime_boundary}\n" .
				"Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
				"Content-Transfer-Encoding: 7bit\n\n" .
				$message . "\n\n";

		// Base64 encode the file data
		$data = file_get_contents($file);
		$data = chunk_split(base64_encode($data));

		// Add file attachment to the message
		$fileName = basename($file);
		if (isset($nicefile)) {
			$fileName = $nicefile;
		}
		$fileName = basename($file);
		$message .= "--{$mime_boundary}\n" .
				"Content-Type: text/csv;\n" .
				" name=\"{$fileName}\"\n" .
				"Content-Disposition: attachment;\n" .
				" filename=\"{$fileName}\"\n" .
				"Content-Transfer-Encoding: base64\n\n" .
				$data . "\n\n" .
				"--{$mime_boundary}--\n";

		// Send the message
		$ok = @mail($email, $subject, $message, $headers);
	}

	/**
	 * Sends the HTTP headers to show confirmation to download a file.
	 *
	 * @param $file The path to the file to download
	 * @param $file The file name to save as on the client computer.
	 * @return void
	 */
	private function _sendDownload($file, $saveFile) {

		$this->auto_render = false;
		// Don't forget to 'return' the result otherwise nothing happens
		return download::force($file, NULL, $saveFile);

	}
}

?>
