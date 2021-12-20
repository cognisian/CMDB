<?php
class Lookup_Controller extends Controller {

	protected $sess;

	/**
	 * When creating the device lookup controller check to make sure we are logged
	 * in.
	 */
	public function __construct() {

		parent::__construct();

		$this->sess = Session::instance();
	}

	/**
	 * Gets the list of device names which start with the characters POSTed.
	 *
	 * <p>This is a function that is called via AJAX and will return the set of
	 * HTML option tags to the caller.</p>s
	 */
	public function names() {

		// Check if user logged in
		if ($this->sess->get('logged_in', FALSE)) {

			// Get device name
			$deviceName = $this->input->post('name');
			if (isset($deviceName) && $deviceName !== '') {

				$query = Doctrine_Query::create()
						->select('d.name')
						->distinct(TRUE)
						->from('Device d')
						->where('d.name LIKE ?', "{$deviceName}%");

				$deviceNames = $query->fetchArray();

				// Regex to highlight portion user typed in
				$regex = '/' . $deviceName . '/';
				$buffer = '';

				// Loop through each device name and highlight the search string
				foreach ($deviceNames as $name) {

					// Split host name into portion user typed and remaining and
					// highlight matched user portion
					$match = preg_replace($regex, "<strong>$0</strong>", $name['name']);

					// Add to list for autocomplete
					$buffer .= '<li>' . $match . '</li>';
				}

				// Send results
				echo "<ul>\n" . $buffer . "\n</ul>";
			}
		}
	}

	/**
	 * Gets the list of services defined on a host
	 *
	 * <p>This is a function that is called via AJAX and will return the set of
	 * HTML option tags to the caller.</p>
	 */
	public function services() {

		// Check if user logged in
		if ($this->sess->get('logged_in', FALSE)) {

			// Output buffer
			$buffer = '';

			// Send the entire list of services
			$services = Doctrine::getTable('Service')->findAll(Doctrine::HYDRATE_ARRAY);

			// Sort services by name and port number
			$serviceNames = array();
			$servicePorts = array();
			$serviceProtos = array();
			foreach($services as $availService) {
				$serviceNames[] = $availService['name'];
				$servicePorts[] = $availService['port'];
				$serviceProtos[] = $availService['protocol'];
			}
			array_multisort(
					$serviceNames, SORT_STRING, SORT_ASC,
					$servicePorts, SORT_NUMERIC, SORT_ASC,
					$serviceProtos, SORT_STRING, SORT_ASC,
					$services);

			foreach ($services as $service) {
				$buffer .= "<option value=\"{$service['id']}\">";
				$buffer .= $service['name'] . ' ' .
					$service['port'] . ' ' .
					'(' . $service['protocol'] . ')';
				$buffer .= '</option>';
			}

			// Send results
			echo $buffer;
		}
	}

	/**
	 * Gets the list of network interfaces defined for a host.
	 *
	 * <p>This is a function that is called via AJAX and will return the set of
	 * HTML option tags to the caller.</p>
	 */
	public function interfaces() {

		// Check if user logged in
		if ($this->sess->get('logged_in', FALSE)) {

			// Get device name
			$deviceName = $this->input->get('name');
			if (isset($deviceName) && $deviceName !== '') {

				$query = Doctrine_Query::create()
						->select('d.name, np.id, np.nic, np.conn_blade, np.conn_port')
						->from('Device d')
						->leftJoin('d.NetworkProperty np')
						->where('d.name = ?', $deviceName);

				$devices = $query->execute(array(), Doctrine::HYDRATE_RECORD);
				$device = $devices[0];

				// Loop through each device name and highlight the search string
				$buffer = '';
				if (isset($device->NetworkProperty) && $device->NetworkProperty->count() > 0) {
					foreach ($device->NetworkProperty as $netProp) {

						// Build interface select option
						if (isset($netProp->id)) {

							$buffer .= "<option value=\"{$netProp->id}\">";
							$buffer .= $netProp->nic;

							// Show interface blade/port, if defined
							if (isset($netProp->conn_blade) && is_numeric($netProp->conn_blade) &&
									isset($netProp->conn_port) && is_numeric($netProp->conn_port)) {

								$buffer .= ' (Bld/Pt: ';
								$buffer .= $netProp->conn_blade;
								$buffer .= '/';
								$buffer .= $netProp->conn_port;
								$buffer .= ')';
							}

							$buffer .= '</option>';
						}
					}
				}
				else {
					$buffer .= '<option value="0">No Interfaces Found</option>';
				}

				// Send results
				echo $buffer;
			}
		}
	}
}
?>
