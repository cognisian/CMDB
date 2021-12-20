<?php
class Suspect_Controller extends Template_Controller {

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

	public function index() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Suspect Reporting';
		$this->template->userName = NULL;
		$this->template->pageID = 'suspect';

		// If the user has logged on then show the view, else show login form
		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Update Navigation info
			$logon = $this->sess->get('logon_id');
			$this->template->userName = $logon;

			// Only admins can generate the report
			if ($this->sess->get('is_admin')) {

				// Show the device name search view
				$this->template->content = new View('suspect_report');

				// Find user properties
				$query = Doctrine_Query::create()
					->select('u.logon, u.fname, u.lname, u.email')
					->from('User u')
					->leftJoin('u.FunctionalArea fa')
					->where('u.logon = ?', array($logon));
				$users = $query->execute(array(), Doctrine::HYDRATE_RECORD);
				$query->free();

				// Only 1 user should have been returned
				if ($users->count() == 1) {

					$user = $users[0];

					$this->template->content->reportEmail = $user->email;
					$this->template->content->reportType = 'Suspect Reports';
				}
			}
			else {
				$this->template->content->errorMsg = 'Only Functional Area ' .
						'administrators are allowed to generate suspect reports.';
			}
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
	 * Displays the list of devices which are suspected of not having the defined
	 * set of required device properties.
	 *
	 * <p>The required set of properties for a device are:
	 * <ul>
	 *     <li>Name</li>
	 *     <li>Asset Tag</li>
	 *     <li>Device Type</li>
	 *     <li>Operational Area</li>
	 *     <li>Operational Status</li>
	 *     <li>Device Functional Area</li>
	 *     <li>Application Functioanl Area</li>
	 *     <li>Location (Site)</li>
	 * </ul>
	 * </p>
	 *
	 * @return void
	 */
	public function device() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Suspect Reporting';
		$this->template->userName = NULL;
		$this->template->pageID = 'suspect_device';

		// If the user has logged on then show the view, else show login form
		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Update Navigation info
			$logon = $this->sess->get('logon_id');
			$this->template->userName = $logon;

			// Show the device name search view
			$this->template->content = new View('suspect_devices');

			// Only admins can generate the report
			if ($this->sess->get('is_admin')) {

				// Find all the devices which have null values for required properties
				$query = Doctrine_Query::create()
					->select('u.logon, u.fname, u.lname, u.is_admin, u.email')
					->from('User u')
					->leftJoin('u.FunctionalArea fa')
					->where('u.logon = ?', array($logon));
				$users = $query->execute(array(), Doctrine::HYDRATE_RECORD);
				$query->free();

				// Only 1 user should have been returned
				if ($users->count() == 1) {

					$user = $users[0];

					$this->template->content->reportEmail = $user->email;
					$this->template->content->reportType = 'Suspect Devices';
				}
			}
			else {
				$this->template->content->reportType = 'Suspect Devices';
				$this->template->content->errorMsg = 'Only Functional Area ' .
						'administrators are allowed to generate suspect reports.';
			}
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
	 * Generates a report which lists the devices which are suspected of not having
	 * the defined set of required device properties.
	 *
	 * <p>The user is able to select one or more properties from a defined set of
	 * properties The required set of properties for a device are:
	 * <ul>
	 *     <li>Name</li>
	 *     <li>Asset Tag</li>
	 *     <li>Device Type</li>
	 *     <li>Operational Area</li>
	 *     <li>Operational Status</li>
	 *     <li>Device Functional Area</li>
	 *     <li>Application Functioanl Area</li>
	 *     <li>Location (Site)</li>
	 * </ul>
	 * </p>
	 *
	 * @return void
	 */
	public function generate_devices() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Suspect Reporting';
		$this->template->userName = NULL;

		// If the user has logged on then show the view, else show login form
		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Update Navigation info
			$logon = $this->sess->get('logon_id');
			$this->template->userName = $logon;

			// Only admins can generate the report
			if ($this->sess->get('is_admin')) {

				// Show the device name search view
				$this->template->content = new View('suspect_devices');

				// Find user properties
				$query = Doctrine_Query::create()
					->select('u.logon, u.fname, u.lname, u.email')
					->from('User u')
					->leftJoin('u.FunctionalArea fa')
					->where('u.logon = ?', array($logon));
				$users = $query->execute(array(), Doctrine::HYDRATE_RECORD);
				$query->free();

				// Only 1 user should have been returned
				if ($users->count() == 1) {

					$user = $users[0];

					$this->template->content->reportEmail = $user->email;
					$this->template->content->reportType = 'Suspect Devices';

					// Get the list of suspect devices
					$query = Doctrine_Query::create()
						->select('d.name, d.asset_tag, dt.type, fa.name, aa.name')
						->addSelect('op.name, oa.type, l.site_id, s.id, s.code')
						->from('Device d')
						->leftJoin('d.DeviceType dt')
						->leftJoin('d.FunctionalArea fa')
						->leftJoin('d.ApplicationArea aa')
						->leftJoin('d.OpArea oa')
						->leftJoin('d.OpStatus os')
						->leftJoin('d.Location l')
						->leftJoin('l.Site s');

					$props = $this->input->post('device_props');
					if (count($props) != 0) {

						$first = TRUE;
						foreach ($props as $prop) {
							if ($prop == 'site_id') {
								$prop = 'l.' . $prop;
							}
							else {
								$prop = 'd.' . $prop;
							}

							if ($first) {
								$first = FALSE;
								$query->where($prop . ' IS NULL');
							}
							else {
								$query->orWhere($prop . ' IS NULL');
							}

							if ($prop == 'd.asset_tag') {
								$query->orWhere($prop . ' = 0');
							}
						}

						$devices = $query->execute(array(), Doctrine::HYDRATE_RECORD);
						$query->free();

						$fileID = $this->_generateDeviceReport($devices, $user->email,
								$user->fname, $user->lname, 'Device Suspect Report',
								'A Listing of Current Devices with Missing Device Properties');
						$this->template->content->reportGen = TRUE;

						$filePDF = '/tmp/' . $fileID . '.pdf';
						$this->_emailReport($user->email, $user->fname, $user->lname,
								'Device Suspect Report',
								'A Listing of Current Devices with Missing Device Properties',
								$filePDF, 'DeviceSuspectReport.pdf');

						$fileCSV = '/tmp/' . $fileID . '.csv';

						$this->_sendDownload($fileCSV, 'DeviceSuspectReport.csv');

						unlink($filePDF);
						unlink($fileCSV);

						$devices->free();

						url::redirect('/suspect/device');
					}
					else {
						$this->template->content->errorMsg = 'One or more ' .
								'properties must be selected.';
					}

					$user->free();
				}
			}
			else {
				$this->template->content->errorMsg = 'Only Functional Area ' .
						'administrators are allowed to generate suspect reports.';
			}
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
	 * Displays the list of devices which are suspected of not having a Business Unit
	 * assigned as the primary functional area of the device.
	 *
	 * <p>A device with a suspect functinal area owning Business Unit means that the
	 * primary application being run on the device could not be determined.</p>
	 *
	 * @return void
	 */
	public function network() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Suspect Reporting';
		$this->template->userName = NULL;
		$this->template->pageID = 'suspect_network';

		// If the user has logged on then show the view, else show login form
		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Update Navigation info
			$logon = $this->sess->get('logon_id');
			$this->template->userName = $logon;

			// Only admins can generate the report
			if ($this->sess->get('is_admin')) {

				// Show the device name search view
				$this->template->content = new View('suspect_report');

				// Find user properties
				$query = Doctrine_Query::create()
					->select('u.logon, u.fname, u.lname, u.email')
					->from('User u')
					->leftJoin('u.FunctionalArea fa')
					->where('u.logon = ?', array($logon));
				$users = $query->execute(array(), Doctrine::HYDRATE_RECORD);
				$query->free();

				// Only 1 user should have been returned
				if ($users->count() == 1) {

					$user = $users[0];

					$this->template->content->reportEmail = $user->email;
					$this->template->content->reportType = 'Suspect Networks';

					// Get the list of suspect devices
					$query = Doctrine_Query::create()
						->select('d.name, l.site_id, s.id, s.code, fa.name, aa.name')
						->addSelect('dt.type, np.ip_addr, np.nic, np.conn_blade')
						->addSelect('np.conn_port, np.conn_vlan')
						->from('NetworkProperty np')
						->leftJoin('np.ParentNetwork pn')
						->leftJoin('np.Device d')
						->leftJoin('d.DeviceType dt')
						->leftJoin('d.FunctionalArea fa')
						->leftJoin('d.ApplicationArea aa')
						->leftJoin('d.Location l')
						->leftJoin('l.Site s')
						->where('np.ip_addr IS NULL')
						->orWhere('np.ip_addr = 0')
						->orWhere('np.conn_blade IS NULL')
						->orWhere('np.conn_port IS NULL')
						->orWhere('np.conn_vlan IS NULL');

					$devices = $query->execute(array(), Doctrine::HYDRATE_RECORD);
					$query->free();

					$fileID = $this->_generateNetworkReport($devices, $user->email, $user->fname,
							$user->lname,
							'Network Suspect Report',
							'A Listing of Current Devices with Missing Network Properties');

					$this->template->content->reportGen = TRUE;

					$filePDF = '/tmp/' . $fileID . '.pdf';
					$this->_emailReport($user->email, $user->fname, $user->lname,
							'Network Suspect Report',
							'A Listing of Current Devices with Missing Network Properties',
							$filePDF, 'NetworkSuspectReport.pdf');

					$fileCSV = '/tmp/' . $fileID . '.csv';
					$this->_sendDownload($fileCSV, 'NetworkSuspectReport.csv');

					unlink($filePDF);
					unlink($fileCSV);

					$devices->free();

					url::redirect('/suspect');
				}
			}
			else {
				$this->template->content->errorMsg = 'Only Functional Area ' .
						'administrators are allowed to generate suspect reports.';
			}
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
	 * Generates a report which lists the devices which are suspected of not having
	 * the defined set of required services properties.
	 *
	 * <p>The required set of services properties for a device are:
	 * <ul>
	 *     <li>Must be greater than or equal to 1</li>
	 * </ul>
	 * </p>
	 *
	 * @return void
	 */
	public function services() {

		// Set template view variables
		$this->template->javascript = '';
		$this->template->title = 'Suspect Reporting';
		$this->template->userName = NULL;
		$this->template->pageID = 'suspect_services';

		// If the user has logged on then show the view, else show login form
		if ($this->sess->get('logged_in') && !$this->sess->get('change_password')) {

			// Update Navigation info
			$logon = $this->sess->get('logon_id');
			$this->template->userName = $logon;

			// Only admins can generate the report
			if ($this->sess->get('is_admin')) {

				// Show the device name search view
				$this->template->content = new View('suspect_report');

				// Find user properties
				$query = Doctrine_Query::create()
					->select('u.logon, u.fname, u.lname, u.email')
					->from('User u')
					->leftJoin('u.FunctionalArea fa')
					->where('u.logon = ?', array($logon));
				$users = $query->execute(array(), Doctrine::HYDRATE_RECORD);
				$query->free();

				// Only 1 user should have been returned
				if ($users->count() == 1) {

					$user = $users[0];

					$this->template->content->reportEmail = '';
					$this->template->content->reportType = 'Suspect Services';
					$this->template->content->errorMsg = 'The Services Suspect Report is not yet implemented.';

					$user->free();
				}
			}
			else {
				$this->template->content->errorMsg = 'Only Functional Area ' .
						'administrators are allowed to generate suspect reports.';
			}
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
	 * Generates a PDF suspect report.
	 *
	 * @param Doctrine_Collection $devices The set of suspect devices.
	 * @param string $email The email address to ssend the PDF report.
	 * @param string $fname The first name of the user to send to the suspect
	 * report.
	 * @param string $lname The last name of the user to send to the suspect
	 * report.
	 * @param string $title The title of the report.
	 * @param string $subject The subject of the report.
	 * @return string A unique file ID which the name of the file.  The PDF version
	 * is $fileID.pdf and the CSV file is $fileID.csv.
	 */
	function _generateDeviceReport($devices, $email, $fname, $lname, $title, $subject) {

		// Create the CSV document
		$rand = md5(time());

		$fileID = $rand;

		// Create PDF document
		$pdf = new SuspectDeviceReport('LANDSCAPE', 'mm', 'LETTER', $title);

		$pdf->AliasNbPages();  // required for footer page num

		$pdf->SetTitle($title);
		$pdf->SetSubject($subject);
		$pdf->SetCreator('Symcor');

		$pdf->SetFont('Arial', '', 12);

		$pdf->AddCoverPage();

		// Create table
		$pdf->AddPage();
		$colwidths = array(60, 20, 20, 30, 30, 30, 50, 20);
		$headers = array(
			"Host\nName",
			"Asset\nTag",
			"Device\nType",
			"Oper.\nArea",
			"Oper.\nStatus",
			"Device\nArea",
			"Application\nArea",
			"Location\n(Site)"
		);

		// Add Headers to CSV
		$csvData = 'Host Name, Asset Tag, Device Type, Opererational Area,';
		$csvData .= 'Operational Status, Device Area, Application Area, Location (Site)';
		$csvData .= "\n";

		$suspectDev = array();
		$i = 0;
		foreach ($devices as $device) {

			// Generate columns for PDF table
			$suspectDev[$i][0] = (isset($device->name)) ? $device->name : '' ;
			$suspectDev[$i][1] = (isset($device->asset_tag)) ? $device->asset_tag : '' ;
			$suspectDev[$i][2] = (isset($device->DeviceType)) ? $device->DeviceType->type : '' ;
			$suspectDev[$i][3] = (isset($device->OpArea)) ? $device->OpArea->type : '' ;
			$suspectDev[$i][4] = (isset($device->OpStatus)) ? $device->OpStatus->status : '' ;
			$suspectDev[$i][5] = (isset($device->FunctionalArea)) ? $device->FunctionalArea->name : '' ;
			$suspectDev[$i][6] = (isset($device->ApplicationArea)) ? $device->ApplicationArea->name : '' ;
			$suspectDev[$i][7] = (isset($device->Location) && isset($device->Location->Site)) ? strtoupper($device->Location->Site->code) : '' ;

			// Add to CSV file
			$csvData .= $suspectDev[$i][0] . ',' . $suspectDev[$i][1] . ',';
			$csvData .= $suspectDev[$i][2] . ',' . $suspectDev[$i][3] . ',';
			$csvData .= $suspectDev[$i][4] . ',' . $suspectDev[$i][5] . ',';
			$csvData .= $suspectDev[$i][6] . ',' . $suspectDev[$i][7] . "\n";

			$i++;
		}

		$pdf->GenerateTable($colwidths, $headers, $suspectDev);

		// Save PDF to file
		$pdf->Output("/tmp/{$fileID}.pdf", 'F');

		// Save CSV to file
		file_put_contents("/tmp/{$fileID}.csv", $csvData);

		return $fileID;
	}

	/**
	 * Generates a PDF suspect report.
	 *
	 * @param Doctrine_Collection $netProps The set of suspect network devices.
	 * @param string $email The email address to ssend the PDF report.
	 * @param string $fname The first name of the user to send to the suspect
	 * report.
	 * @param string $lname The last name of the user to send to the suspect
	 * report.
	 * @param string $title The title of the report.
	 * @param string $subject The subject of the report.
	 * @return void
	 */
	function _generateNetworkReport($netProps, $email, $fname, $lname, $title, $subject) {

		// Create the CSV document
		$rand = md5(time());
		$fileID = $rand;

		// Create PDF document
		$pdf = new SuspectDeviceReport('LANDSCAPE', 'mm', 'LETTER', $title);

		$pdf->AliasNbPages();  // required for footer page num

		$pdf->SetTitle($title);
		$pdf->SetSubject($subject);
		$pdf->SetCreator('Symcor');

		$pdf->SetFont('Arial', '', 12);

		$pdf->AddCoverPage();

		// Create table
		$pdf->AddPage();
		$colwidths = array(35, 20, 10, 20, 30, 15, 15, 15, 15, 30, 30, 15);
		$headers = array(
			"Host\nName",
			"Device\nType",
			"NIC\n",
			"IP\nAddress",
			"Parent\nSwitch/Router",
			"Switch/Router\nNIC",
			"Blade\n",
			"Port\n",
			"VLAN\n",
			"Device\nFunction Area",
			"App\nFunction Area",
			"Location (Site)"
		);

		// Add Headers to CSV
		$csvData = 'Host Name, Device Type, NIC, IP Address, ';
		$csvData .= 'Parent Switch, Switch/Router NIC, Blade, Port, VLAN,';
		$csvData .= 'Device Area, Application Area, Location (Site)';
		$csvData .= "\n";

		$suspectDev = array();
		$i = 0;
		foreach ($netProps as $netProp) {
			$suspectDev[$i][0] = (isset($netProp->Device->name)) ? $netProp->Device->name : '' ;
			$suspectDev[$i][1] = (isset($netProp->Device->DeviceType)) ? $netProp->Device->DeviceType->type : '' ;
			$suspectDev[$i][2] = (isset($netProp->nic)) ? $netProp->nic : '' ;
			$suspectDev[$i][3] = (isset($netProp->ip_addr)) ? long2ip($netProp->ip_addr) : '' ;
			$suspectDev[$i][4] = (isset($netProp->ParentNetwork) && isset($netProp->ParentNetwork->Device->name)) ?
					$netProp->ParentNetwork->Device->name : '';
			$suspectDev[$i][5] = (isset($netProp->ParentNetwork) && isset($netProp->ParentNetwork->nic)) ?
					$netProp->ParentNetwork->nic : '';
			$suspectDev[$i][6] = (isset($netProp->conn_blade)) ? $netProp->conn_blade : '' ;
			$suspectDev[$i][7] = (isset($netProp->conn_port)) ? $netProp->conn_port : '' ;
			$suspectDev[$i][8] = (isset($netProp->conn_vlan)) ? $netProp->conn_vlan : '' ;
			$suspectDev[$i][9] = (isset($netProp->Device->FunctionalArea)) ? $netProp->Device->FunctionalArea->name : '' ;
			$suspectDev[$i][10] = (isset($netProp->Device->ApplicationArea)) ? $netProp->Device->ApplicationArea->name : '' ;
			$suspectDev[$i][11] = (isset($netProp->Device->Location) && isset($netProp->Device->Location->Site)) ?
					strtoupper($netProp->Device->Location->Site->code) : '';

			// Add to CSV file
			$csvData .= $suspectDev[$i][0] . ',' . $suspectDev[$i][1] . ',';
			$csvData .= $suspectDev[$i][2] . ',' . $suspectDev[$i][3] . ',';
			$csvData .= $suspectDev[$i][4] . ',' . $suspectDev[$i][5] . ',';
			$csvData .= $suspectDev[$i][6] . ',' . $suspectDev[$i][7] . ",";
			$csvData .= $suspectDev[$i][8] . ',' . $suspectDev[$i][9] . ",";
			$csvData .= $suspectDev[$i][10] . ',' . $suspectDev[$i][11] . "\n";

			$i++;
		}

		$pdf->GenerateTable($colwidths, $headers, $suspectDev);

		// Save PDF to file
		$pdf->Output("/tmp/{$fileID}.pdf", 'F');

		// Save CSV to file
		file_put_contents("/tmp/{$fileID}.csv", $csvData);

		return $fileID;
	}

	/**
	 * Generates a PDF suspect report.
	 *
	 * @param Doctrine_Collection $devices The set of suspect devices.
	 * @param string $email The email address to ssend the PDF report.
	 * @param string $fname The first name of the user to send to the suspect
	 * report.
	 * @param string $lname The last name of the user to send to the suspect
	 * report.
	 * @param string $title The title of the report.
	 * @param string $subject The subject of the report.
	 * @return void
	 */
	function _generateServiceReport($devices, $email, $fname, $lname, $title, $subject) {
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
	function _emailReport($email, $fname, $lname, $title, $desc, $file, $nicefile) {

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
	function _sendDownload($file, $saveFile) {

		$this->auto_render = false;
		// Don't forget to 'return' the result otherwise nothing happens
		return download::force($file, NULL, $saveFile);

	}
}
?>
