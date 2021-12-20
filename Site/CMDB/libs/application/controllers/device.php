<?php
class Device_Controller extends Template_Controller {

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
	 * Display the initial device editing form.  This will allow the user to select
	 * a device to edit by locating it by name.
	 */
	public function index() {

		// Set template view variables
		$this->template->javascript = html::script(
			array(
				'script/autocomplete'
			),
			FALSE
		);
		$this->template->title = 'Edit A Device';
		$this->template->userName = NULL;
		$this->template->pageID = 'device_name';

		// If the user has logged on then show the view, else show login form
		if ($this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {

				// Update Navigation info
				$this->template->userName = $this->sess->get('logon_id');

				// Show the device name search view
				$this->template->content = new View('search_device_name');
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
	 * Shows all the device properties and enables/disables the appropriate
	 * properties based on user's business unit.
	 */
	public function edit() {

		// Set template information
		$this->template->javascript = html::script(
			array(
				'script/device_deps',
				'script/service_deps',
				'script/network_deps'
			)
		);
		$this->template->title = 'Edit Device';
		$this->template->userName = NULL;
		$this->template->pageID = 'edit-device';

		if ($this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {

				// Insert Navigation information
				$this->template->userName = $this->sess->get('logon_id');

				// Edit the specified device
				$deviceName = $this->input->get('name', FALSE);
				if ($deviceName) {

					// Get the device details
					$query = Doctrine_Query::create()
						->from('Device d')
						->leftJoin('d.DeviceType dt')
						->leftJoin('d.OpArea oa')
						->leftJoin('d.Owner own')
						->leftJoin('d.ParentDevice pd')
						->leftJoin('d.FunctionalArea fa')
						->leftJoin('d.ApplicationArea aa')
						->leftJoin('d.Comments dc')
						->leftJoin('d.SystemProperty sp')
						->leftJoin('sp.FunctionalArea sfa')
						->leftJoin('sp.Comments sc')
						->leftJoin('d.NetworkProperty np')
						->leftJoin('np.FunctionalArea nfa')
						->leftJoin('np.Comments nc')
						->leftJoin('np.ParentNetwork pn')
						->leftJoin('d.ServiceProperty srvp')
						->leftJoin('srvp.Service srv')
						->leftJoin('srvp.DependsOn sdep')
						->leftJoin('d.Location l')
						->leftJoin('l.Site s')
						->where('d.name = ?', $deviceName);
					$devices = $query->execute(array(), Doctrine::HYDRATE_RECORD);
					$query->free();

					// Check if we have found a device, if not redirect back to find device
					if ($devices->count() == 1) {

						$device = $devices[0];

						// Show the device detail edit views
						$this->template->content = new View('device_detail');

						$this->template->content->addDevice = FALSE;

						$this->template->content->deviceName = $devices[0]->name;
						$this->template->content->device = $devices[0];

						$userfuncAreas = unserialize($this->sess->get('functional_areas'));

						$this->_insertDeviceProperties($device, $userfuncAreas);
						$this->_insertSystemProperties($device, $userfuncAreas);
						$this->_insertServiceProperties($device, $userfuncAreas);
						$this->_insertNetworkProperties($device, $userfuncAreas);

						$this->_insertDeviceDependencies($device, $userfuncAreas);
						$this->_insertNetworkDependencies($device, $userfuncAreas);
						$this->_insertServiceDependencies($device, $userfuncAreas);
					}
					else {
						// Unable to find device or too many devices with same name
						url::redirect('/device');
					}
				}
				else {
					url::redirect('device');
				}
			}
			else {
				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
		}
		else {
			$this->template->content = '';

			$this->sess->set('refer_path', 'device');
			url::redirect('main');
		}
	}

	/**
	 * Add a new device.
	 *
	 * The new device details to update are passed via the POST parameters
	 */
	public function add() {

		// Set template information
		$this->template->javascript = '';
		$this->template->title = 'Add a Device';
		$this->template->userName = NULL;
		$this->template->pageID = 'add-device';

		if ($this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {

				// Insert Navigation information
				$this->template->userName = $this->sess->get('logon_id');

				$this->template->content = new View('device_add');

				$deviceTypes = Doctrine::getTable('DeviceType')->findAll(Doctrine::HYDRATE_RECORD);
				$this->template->content->deviceTypes = $deviceTypes;

				$i = 0;
				$funcAreas = array();
				$areas = Doctrine::getTable('FunctionalArea')->findAll(Doctrine::HYDRATE_RECORD);
				foreach ($areas as $area) {
					if ($area->name == 'Unix' || $area->name == 'Windows' ||
							$area->name == 'Mainframe' || $area->name == 'Desktop' ||
							$area->name == 'Network') {

						$funcAreas[$i]['id'] = $area->id;
						$funcAreas[$i]['name'] = $area->name;

						$i++;
					}
				}
				$this->template->content->hardwareAreas = $funcAreas;

				$this->template->content->funcAreas = $areas;

				$sites = Doctrine::getTable('Site')->findAll(Doctrine::HYDRATE_RECORD);
				$this->template->content->sites = $sites;

				$this->template->content->opStatuses =
						Doctrine::getTable('OperationalStatus')->findAll(Doctrine::HYDRATE_RECORD);

				$this->template->content->opAreas =
						Doctrine::getTable('OperationalArea')->findAll(Doctrine::HYDRATE_RECORD);
			}
			else {
				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
		}
		else {
			$this->sess->set('refer_path', url::current(TRUE));
			url::redirect('main');
		}
	}

	/**
	 * Update a device.
	 *
	 * The device details to update are passed via the POST parameters.
	 *
	 * @param string $propType A string indicating that the POST parameters contain
	 * a specific device property type.
	 */
	public function update($propType) {

		if ($this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {

				// Edit the specified device
				$deviceName = $this->input->post('deviceName', FALSE);
				if ($deviceName) {

					// Get the device details
					$query = Doctrine_Query::create()
								->from('Device d')
								->leftJoin('d.DeviceType dt')
								->leftJoin('d.ParentDevice pd')
								->leftJoin('d.FunctionalArea fa')
								->leftJoin('d.ApplicationArea aa')
								->leftJoin('d.Comments dc')
								->leftJoin('d.SystemProperty sp')
								->leftJoin('sp.Comments sc')
								->leftJoin('d.ServiceProperty srvp')
								->leftJoin('srvp.DependsOn dep')
								->leftJoin('d.NetworkProperty np')
								->leftJoin('np.ParentNetwork pn')
								->leftJoin('np.Comments nc')
								->leftJoin('d.Location l')
								->leftJoin('l.Site s')
								->where('d.name = ?', $deviceName);
					$devices = $query->execute(array(), Doctrine::HYDRATE_RECORD);
					$query->free();
					$query = null;

					// Get device
					$device = $devices[0];

					// Extract variables depending on the property being editied
					switch ($propType) {

						case 'device-props' :

							$props = array(
								// Device relationship data
								'device_type' => $this->input->post('device_type', NULL),
								'site_code' => $this->input->post('site_code', NULL),
								'function_area' => $this->input->post('function_area', NULL),
								'application_area' => $this->input->post('application_area', NULL),
								'op_area' => $this->input->post('op_area', NULL),
								'op_status' => $this->input->post('op_status', NULL),
								// Device property data
								'name' => $this->input->post('device_new_name', NULL),
								'asset_tag' => $this->input->post('asset_tag', NULL),
								'manufacturer' => $this->input->post('manufacturer', NULL),
								'model' => $this->input->post('model', NULL)
							);

							// Update comments
							$newComment = trim($this->input->post('device_comment', NULL));
							if (isset($newComment) && !empty($newComment)) {

								$comment = new DeviceComment();

								$comment->device_id = $device->id;
								$comment->comment = $newComment;

								$comment->save();
							}

							// Check to ensure that device rename is valid
							if (empty($props['name']) || $device->name == $props['name']) {
								unset($props['name']);
							}

							// Check to make sure that we are not renaming to existing device
							if (isset($props['name'])) {
								$existDevice = Doctrine::getTable('Device')->findOneByName($props['name']);
								if ($existDevice) {
									unset($props['name']);
								}
							}

							// Process all relationship data and remove the form input
							// and add reference to actual device property and ORM record
							if (!empty($props['site_code'])) {

								if (!isset($device->Location)) {
									$location = new Location();
									$device->Location = $location;
								}
								$location = $device->Location;

								$site = Doctrine::getTable('Site')->findOneByCode($props['site_code']);
								$location->Site = $site;

								$floor = $this->input->post('site_floor', NULL);
								if (isset($floor)) {
									$location->floor = $floor;
								}
								$room = $this->input->post('site_room', NULL);
								if (isset($room)) {
									$location->room = $room;
								}
								$row = $this->input->post('site_row', NULL);
								if (isset($row)) {
									$location->row = $row;
								}
								$cabinet = $this->input->post('site_cabinet', NULL);
								if (isset($cabinet)) {
									$location->cabinet = $cabinet;
								}

								$props['Location'] = $location;
							}
							unset($props['site_code']);

							$deviceType = Doctrine::getTable('DeviceType')->find($props['device_type']);
							unset($props['device_type']);
							if ($deviceType) {
								$props['DeviceType'] = $deviceType;
							}

							$opArea = Doctrine::getTable('OperationalArea')->find($props['op_area']);
							unset($props['op_area']);
							if ($opArea) {
								$props['OpArea'] = $opArea;
							}

							$opStatus = Doctrine::getTable('OperationalStatus')->find($props['op_status']);
							unset($props['op_status']);
							if ($opStatus) {
								$props['OpStatus'] = $opStatus;
							}

							$funcArea = Doctrine::getTable('FunctionalArea')->find($props['function_area']);
							unset($props['function_area']);
							if ($funcArea) {
								$props['FunctionalArea'] = $funcArea;
							}

							$appFuncArea = Doctrine::getTable('FunctionalArea')->find($props['application_area']);
							unset($props['application_area']);
							if ($appFuncArea) {
								$props['ApplicationArea'] = $appFuncArea;
							}

							// Update the device properties
							foreach ($props as $propName => $propValue) {
								$device->{$propName} = $propValue;
							}

							// Save new values
							$device->save();
							$deviceName = $device->name;

							break;

						case 'system-props' :

							$props = array(
								// Device property data
								'type' => $this->input->post('type', NULL),
								'os' => $this->input->post('os', NULL),
								'os_version' => $this->input->post('os_version', NULL),
								'os_patch_version' => $this->input->post('os_patch_version', NULL),
								'num_cpu' => $this->input->post('num_cpu', NULL),
								'cpu_type' => $this->input->post('cpu_type', NULL),
								'ram' => $this->input->post('ram', NULL),
								'internal_disks' => $this->input->post('internal_disks', NULL),
								'internal_storage' => $this->input->post('internal_storage', NULL)
							);

							// Upate the device system property
							if ($device->SystemProperty->count() == 0) {
								$sysProp = new SystemProperty();
								$sysProp->Device = $device;
								$sysProp->FunctionalArea = $device->FunctionalArea;

								$sysProp->save();

								$device->SystemProperty[] = $sysProp;
							}
							foreach ($props as $propName => $propValue) {
								$device->SystemProperty[0]->{$propName} = $propValue;
							}

							// Update comments
							$newComment = trim($this->input->post('system_comment', NULL));
							if (isset($newComment) && !empty($newComment)) {

								$comment = new SystemComment();

								$comment->system_prop_id = $device->SystemProperty[0]->id;
								$comment->comment = $newComment;

								$comment->save();
							}

							// Save new values
							$device->save();
							break;

						case 'network-props' :

							// Find the Network device being updated
							$netID = $this->input->post('interfaceID', NULL);

							$props = array(
								// Device property data
								'ip_addr' => ip2long($this->input->post('ip_addr', NULL)),
								'nic' => $this->input->post('nic', NULL),
								'mac' => $this->input->post('mac', NULL),
								'conn_blade' => $this->input->post('conn_blade', NULL),
								'conn_port' => $this->input->post('conn_port', NULL),
								'conn_vlan' => $this->input->post('conn_vlan', NULL),
								'conn_speed' => $this->input->post('conn_speed', NULL),
								'conn_medium' => $this->input->post('conn_medium', NULL),
								'duplex' => $this->input->post('duplex', NULL)
							);

							// If updating existing network property
							if ($netID) {

								// Find the network property being changed
								$prop = NULL;
								for ($i = 0; $i < $device->NetworkProperty->count(); $i++) {
									if ($device->NetworkProperty[$i]->id == $netID) {
										$prop = $i;
										break;
									}
								}
							}
							else {
								// Else we are adding a new
								$prop = $device->NetworkProperty->count();

								$netProp = new NetworkProperty();

								$netProp->Device = $device;
								$netProp->FunctionalArea =
										Doctrine::getTable('FunctionalArea')->findOneByName('IT Operations');

								$device->NetworkProperty[$prop] = $netProp;
							}

							// Update properties
							foreach ($props as $propName => $propValue) {
								$device->NetworkProperty[$prop]->{$propName} = $propValue;
							}

							// Save new values
							$device->save();

							// Update comments
							$newComment = trim($this->input->post('intf_comment', NULL));
							if (isset($newComment) && !empty($newComment)) {

								$comment = new NetworkComment();

								$comment->network_prop_id = $device->NetworkProperty[$prop]->id;
								$comment->comment = $newComment;

								$comment->save();
							}

							break;

						case 'service-props' :

							$props = $this->input->post('local-services', NULL);

							// Update the local services
							foreach ($props as $propValue) {

								$addService = TRUE;
								if ($device->ServiceProperty->count() > 0) {
									foreach($device->ServiceProperty as $servProp) {
										if ($servProp->Service->id == $propValue) {
											$addService = FALSE;
											break;
										}
									}
								}
								if ($addService) {

									$servProp = new ServiceProperty();

									$service = Doctrine::getTable('Service')->find($propValue);
									$servProp->Device = $device;
									$servProp->Service = $service;
									$servProp->FunctionalArea =  $device->ApplicationArea;

									$servProp->save();
									$servProp->free();
								}
							}
							break;

						case 'device-deps' :

							$remoteDeviceName = $this->input->post('name', NULL);

							// Do not add if same device
							if ($remoteDeviceName == $device->name) {
								break;
							}

							// Get the remote device
							$query = Doctrine_Query::create()
										->from('Device d')
										->where('d.name = ?', $remoteDeviceName);
							$remoteDevices = $query->execute(array(), Doctrine::HYDRATE_RECORD);
							$query->free();
							$query = null;

							if (isset($remoteDevices) && $remoteDevices->count() > 0) {
								$remoteDevice = $remoteDevices[0];

								$addDep = TRUE;
								if (isset($device->ParentDevice) && $device->ParentDevice->count() > 0) {
									foreach ($device->ParentDevice as $parentDev) {
										if ($parentDev->id == $remoteDevice->id) {
											$addDep = FALSE;
											break;
										}
									}
								}
								if ($addDep) {
									$devDep = new DeviceDependency();

									$devDep->from_device_id = $device->id;
									$devDep->to_device_id = $remoteDevice->id;

									$devDep->save();
									$devDep->free();
								}
							}

							break;

						case 'network-deps' :

							$localInterface = $this->input->post('list-local-interfaces', NULL);
							$remoteDeviceName = $this->input->post('name', NULL);
							$remoteInterfaces = $this->input->post('remote-interfaces-deps', NULL);

							// Get the remote NetworkProperty
							$query = Doctrine_Query::create()
										->from('Device d')
										->leftJoin('d.ServiceProperty np')
										->leftJoin('np.DependsOn dep')
										->where('d.name = ?', $remoteDeviceName);
							$remoteDevices = $query->execute(array(), Doctrine::HYDRATE_RECORD);
							$query->free();
							$query = null;

							// Get local NetworkProperty
							$localNetProp = NULL;
							if (isset($device->NetworkProperty) && $device->NetworkProperty->count() > 0) {
								foreach ($device->NetworkProperty as $netProp) {
									if ($netProp->id == $localInterface) {
										$localNetProp = $netProp;
										break;
									}
								}
							}

							// Get remote device
							$remoteDevice = $remoteDevices[0];
							if (isset($remoteDevice)) {

								// Update the remote network dependencies, if not exists
								foreach ($remoteInterfaces as $depInterface) {

									// Get the remote network property
									$remoteNetProp = NULL;
									foreach ($remoteDevice->NetworkProperty as $netProp) {
										if ($netProp->id == $depInterface) {
											$remoteNetProp = $netProp;
											break;
										}
									}

									// Add dependency
									$addDep = TRUE;
									foreach($localNetProp->ParentNetwork as $depNetProp) {
										if ($depNetProp->id == $depInterface) {
											$addDep = FALSE;
											break;
										}
									}
									if ($addDep) {

										$netDep = new NetworkDependency();

										$netDep->from_net_prop_id = $localNetProp->id;
										$netDep->to_net_prop_id = $remoteNetProp->id;

										$netDep->save();
									}
								}
							}

							break;

						case 'service-deps' :

							$localService = $this->input->post('list-local-def-services', NULL);
							$remoteDeviceName = $this->input->post('name', NULL);
							$remoteServices = $this->input->post('remote-services-deps', NULL);

							// Get the remote ServiceProperty
							$query = Doctrine_Query::create()
										->from('Device d')
										->leftJoin('d.ServiceProperty srvp')
										->leftJoin('srvp.DependsOn dep')
										->where('d.name = ?', $remoteDeviceName);
							$remoteDevices = $query->execute(array(), Doctrine::HYDRATE_RECORD);
							$query->free();
							$query = null;

							// Get local ServiceProperty
							$localServProp = NULL;
							if (isset($device->ServiceProperty) && $device->ServiceProperty->count() > 0) {
								foreach ($device->ServiceProperty as $servProp) {
									if ($servProp->Service->id == $localService) {
										$localServProp = $servProp;
										break;
									}
								}
							}

							// Get remote device
							$remoteDevice = $remoteDevices[0];
							if (isset($remoteDevice) && count($remoteServices) > 0) {

								// Update the remote service dependencies, if not exists
								foreach ($remoteServices as $depService) {

									// Get the remote service property
									$remoteServProp = NULL;
									foreach ($remoteDevice->ServiceProperty as $servProp) {
										if ($servProp->Service->id == $depService) {
											$remoteServProp = $servProp;
											break;
										}
									}

									// Add the remote local services, if it did not exist
									if (!isset($remoteServProp)) {

										$remoteServProp = new ServiceProperty();

										$service = Doctrine::getTable('Service')->find($depService);
										$remoteServProp->Device = $remoteDevice;
										$remoteServProp->Service = $service;
										$remoteServProp->FunctionalArea =  $device->ApplicationArea;

										$remoteServProp->save();
									}

									// Add dependency
									$addDep = TRUE;
									foreach($localServProp->DependsOn as $depServProp) {
										// Make sure that the local service dependnecy
										// to remote service on device not prev defined
										if ($depServProp->Device->name == $remoteDeviceName &&
												$depServProp->Service->id == $remoteServProp->Service->id) {

											$addDep = FALSE;
											break;
										}
									}
									if ($addDep) {

										// Add dependency from local to remote
										$servDep = new ServiceDependency();

										$servDep->from_service_prop_id = $localServProp->id;
										$servDep->to_service_prop_id = $remoteServProp->id;

										$servDep->save();
										$servDep->free();

										// Add dependency from remote to local
										$servDep = new ServiceDependency();

										$servDep->to_service_prop_id = $localServProp->id;
										$servDep->from_service_prop_id = $remoteServProp->id;

										$servDep->save();
										$servDep->free();
									}
								}
							}

							break;
					}
				}

				// Reshow device with new properties
				url::redirect("/device/edit?name={$deviceName}");
			}
			else {
				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
		}
		else {
			$this->sess->set('refer_path', 'device');
			url::redirect('main');
		}
	}

	/**
	 * Inserts a new device.
	 *
	 * The device details to insert are passed via the POST parameters.
	 */
	public function insert() {

		// Insert template view data
		if ($this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {

				// Edit the specified device
				$deviceName = $this->input->post('name', FALSE);
				if ($deviceName) {

					// Get the device details
					$device = Doctrine::getTable('Device')->findOneByName($deviceName);
					if (!$device || !isset($device)) {

						// Extract entered values
						$deviceTypeID = $this->input->post('type', FALSE);
						$siteCode = $this->input->post('site', FALSE);
						$funcAreaID = $this->input->post('function_area', FALSE);
						$appFuncAreaID = $this->input->post('application_area', FALSE);
						$opAreaID = $this->input->post('op_area', FALSE);
						$opStatusID = $this->input->post('op_status', FALSE);
						$ipAddr = $this->input->post('ip_addr', FALSE);

						// Get corresponding objects
						$deviceType = Doctrine::getTable('DeviceType')->find($deviceTypeID);
						if (!$deviceType) {
							$this->add();
							$this->template->content->error = "A device type must be specified.";
							return;
						}

						$site = Doctrine::getTable('Site')->findOneByCode($siteCode);
						if (!$site) {
							$this->add();
							$this->template->content->error = "A Site must be specified.";
							return;
						}

						$funcArea = Doctrine::getTable('FunctionalArea')->find($funcAreaID);
						if (!$funcArea) {
							$funcArea = NULL;
						}

						$appFuncArea = Doctrine::getTable('FunctionalArea')->find($appFuncAreaID);
						if (!$appFuncArea) {
							$appFuncArea = NULL;
						}

						$opArea = Doctrine::getTable('OperationalArea')->find($opAreaID);
						if (!$opArea) {
							$opArea = NULL;
						}

						$opStatus = Doctrine::getTable('OperationalStatus')->find($opStatusID);
						if (!$opStatus) {
							$opStatus = NULL;
						}

						// Create the DEVICE
						try {
							$device = new Device();

							$device->name = $deviceName;
							$device->DeviceType = $deviceType;
							$location = new Location();
							$location->Site = $site;
							$device->Location = $location;
							$device->ApplicationArea = $appFuncArea;
							$device->FunctionalArea = $funcArea;

							$device->OpArea = $opArea;
							$device->OpStatus = $opStatus;

							$netProp = new NetworkProperty();
							$ipNum = ip2long($ipAddr);
							if ($ipNum == 0) {
								$ips = gethostbyname($ipAddr);
								$ipNum = ip2long($ips);
							}
							$netProp->ip_addr = $ipNum;
							$netProp->FunctionalArea = Doctrine::getTable('FunctionalArea')->findOneByName('IT Operations');

							$device->NetworkProperty[] = $netProp;

							$device->save();

							// Go back to adding error or otherwise
							url::redirect('/device/add');
						}
						catch (Exception $ex) {
							$this->add();
							$this->template->content->error = $ex->getMessage();
						}
					}
					else {
						$this->add();
						$this->template->content->error = "Device exists.  Must specify a unique host name";
						return;
					}
				}
				else {
					$this->add();
					$this->template->content->error = "Must specify a host name";
					return;
				}
			}
			else {
				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
		}
		else {
			$this->sess->set('refer_path', 'device');
			url::redirect('main');
		}
	}

	/**
	 * Deletes a device property.
	 *
	 * @param string propType
	 */
	function delete($propType) {

		if ($this->sess->get('logged_in')) {
			if (!$this->sess->get('change_password')) {

				// Edit the specified device
				$deviceName = $this->input->post('deviceName', FALSE);
				if ($deviceName) {

					// Get the device details
					$query = Doctrine_Query::create()
								->from('Device d')
								->where('d.name = ?', $deviceName);
					$devices = $query->execute(array(), Doctrine::HYDRATE_RECORD);
					$query->free();
					$query = null;

					// Get device
					$device = $devices[0];

					// Extract variables depending on the property being editied
					switch ($propType) {

						case 'device-props' :
						case 'system-props' :
						case 'network-props' :
						case 'service-props' :
						case 'device-deps' :
						case 'service-deps' :
							break;

						case 'network-deps' :

							$delNetDeps = $this->input->post('del-net-deps', NULL);

							foreach ($delNetDeps as $netDep) {

								// Value of input checkbox is NetworkProperty ID numbers
								$netDepProps = split('_', $netDep);

								// Delete the NetworkDependency
								$query = Doctrine_Query::create()
											->delete('NetworkDependency d')
											->where('d.from_net_prop_id = ? AND d.to_net_prop_id = ?', $netDepProps);
								$query->execute();
								$query->free();
								$query = null;
							}

							break;
					}

					// Reshow device with new properties
					url::redirect("/device/edit?name={$deviceName}");
				}
			}
			else {
				// Set the current URI so we can return when logged in
				$this->sess->set('refer_path', url::current(TRUE));
				url::redirect('/user/change');
			}
		}
		else {
			$this->sess->set('refer_path', 'device');
			url::redirect('main');
		}
	}

	/**
	 * Inserts the device properties into the view.
	 *
	 * @param Doctrine_Record $device The device.
	 * @param Doctrine_Collection $userFuncArea The collection of user Functional
	 * Areas.
	 */
	function _insertDeviceProperties($device, $userfuncAreas) {

		// Add the DEVICE PROPERTY template
		$this->template->content->deviceProps = new View('device_props');

		$this->template->content->deviceProps->userFuncAreas = $userfuncAreas;
		$this->template->content->deviceProps->device = $device;

		// Get the list of Device Types and add to view
		$deviceTypes = Doctrine::getTable('DeviceType')->findAll(Doctrine::HYDRATE_RECORD);
		$this->template->content->deviceProps->deviceTypes = $deviceTypes;

		// Get the list of Sites and add to view
		$sites = Doctrine::getTable('Site')->findAll(Doctrine::HYDRATE_RECORD);
		$this->template->content->deviceProps->sites = $sites;

		// Get the list of FunctionalAreas and add to view
		$i = 0;
		$funcAreas = array();
		$areas = Doctrine::getTable('FunctionalArea')->findAll(Doctrine::HYDRATE_RECORD);
		foreach ($areas as $area) {
			if ($area->name == 'Unix' || $area->name == 'Windows' ||
					$area->name == 'Mainframe' || $area->name == 'Desktop' ||
					$area->name == 'Network') {

				$funcAreas[$i]['id'] = $area->id;
				$funcAreas[$i]['name'] = $area->name;

				$i++;
			}
		}
		$this->template->content->deviceProps->funcAreas = $funcAreas;
		$this->template->content->deviceProps->appFuncAreas = $areas;

		$this->template->content->deviceProps->opStatuses =
			Doctrine::getTable('OperationalStatus')->findAll(Doctrine::HYDRATE_RECORD);

		$this->template->content->deviceProps->opAreas =
			Doctrine::getTable('OperationalArea')->findAll(Doctrine::HYDRATE_RECORD);

		$comments = '';
		foreach($device->Comments as $comment) {
			$comments .= '[' . $comment->created . ']  ';
			$comments .= $comment->comment . "\n";
		}
		$this->template->content->deviceProps->comments = $comments;
	}

	/**
	 * Inserts the device system properties into the view.
	 *
	 * @param Doctrine_Record $device The device.
	 * @param Doctrine_Collection $userFuncArea The collection of user Functional
	 * Areas.
	 */
	function _insertSystemProperties($device, $userfuncAreas) {

		// Add the DEVICE SYSTEM PROPERTY template
		$this->template->content->deviceSysProps = new View('device_sys_props');

		$this->template->content->deviceSysProps->device = $device;
		$this->template->content->deviceSysProps->userFuncAreas = $userfuncAreas;

		$this->template->content->deviceSysProps->sysTypes = array(
			0 => array (
				'code' => 'PHYSICAL',
				'name' => 'Physical'
			),
			1 => array (
				'code' => 'VIRTUAL',
				'name' => 'Virtual'
			)
		);

		$comments = '';
		foreach($device->SystemProperty[0]->Comments as $comment) {
			$comments .= '[' . $comment->created . ']  ';
			$comments .= $comment->comment . "\n";
		}
		$this->template->content->deviceSysProps->comments = $comments;
	}

	/**
	 * Inserts the local service properties into the view.
	 *
	 * @param Doctrine_Record $device The device.
	 * @param Doctrine_Collection $userFuncArea The collection of user Functional
	 * Areas.
	 */
	function _insertServiceProperties($device, $userfuncAreas) {

		// Add the SERVICE DEPENDENCY PROPERTY template
		$this->template->content->deviceServProps = new View('device_serv_props');

		// Remove the services from master list of all services already defined
		// for device
		$availServices = array();
		$services = Doctrine::getTable('Service')->findAll(Doctrine::HYDRATE_RECORD);
		foreach ($services as $service) {

			$deviceService = FALSE;
			// Check if service is defined in host, if it is then do not add to
			// list of available services
			foreach ($device->ServiceProperty as $devServ) {
				if ($devServ->Service->id == $service->id) {
					$deviceService = TRUE;
					break;
				}
			}

			// If here then service not defined in device
			if (!$deviceService) {
				$availServices[] = array(
					'id' => $service->id,
					'name' => $service->name,
					'protocol' => $service->protocol,
					'port' => $service->port,
				);
			}
		}

		// Sort availServices by name and port number
		$serviceNames = array();
		$servicePorts = array();
		$serviceProtos = array();
		foreach($availServices as $availService) {
			$serviceNames[] = $availService['name'];
			$servicePorts[] = $availService['port'];
			$serviceProtos[] = $availService['protocol'];
		}
		array_multisort(
				$serviceNames, SORT_STRING, SORT_ASC,
				$servicePorts, SORT_NUMERIC, SORT_ASC,
				$serviceProtos, SORT_STRING, SORT_ASC,
				$availServices);
		$this->template->content->deviceServProps->services = $availServices;

		$this->template->content->deviceServProps->device = $device;
		$this->template->content->deviceServProps->userFuncAreas = $userfuncAreas;
	}

	/**
	 * Inserts the device network properties into the view.
	 *
	 * @param Doctrine_Record $device The device.
	 * @param Doctrine_Collection $userFuncArea The collection of user Functional
	 * Areas.
	 */
	function _insertNetworkProperties($device, $userfuncAreas) {

		// Add the DEVICE NETWORK PROPERTY template
		$this->template->content->deviceNetProps = new View('device_net_props');

		$this->template->content->deviceNetProps->device = $device;
		$this->template->content->deviceNetProps->userFuncAreas = $userfuncAreas;

		$this->template->content->deviceNetProps->duplexTypes = array(
			0 => array (
				'code' => 'FD',
				'name' => 'Full Duplex'
			),
			1 => array (
				'code' => 'HD',
				'name' => 'Half Duplex'
			)
		);
	}

	/**
	 * Inserts the device dependencies into the view.
	 *
	 * @param bool $addFlag A flag indicating whether we are inserting into the
	 * device update form or the device add form.
	 * @param Doctrine_Record $device The device.
	 * @param Doctrine_Collection $userFuncArea The collection of user Functional
	 * Areas.
	 */
	function _insertDeviceDependencies($device, $userfuncAreas) {

		// Add the DEVICE DEPENDENCY PROPERTY template
		$this->template->content->deviceDeps = new View('device_deps');

		$this->template->content->deviceDeps->device = $device;
		$this->template->content->deviceDeps->userFuncAreas = $userfuncAreas;
	}

	/**
	 * Inserts the device network dependencies into the view.
	 *
	 * @param Doctrine_Record $device The device.
	 * @param Doctrine_Collection $userFuncArea The collection of user Fucntional
	 * Areas.
	 */
	function _insertNetworkDependencies($device, $userfuncAreas) {

		// Add the NETWORK DEPENDENCY PROPERTY template
		$this->template->content->networkDeps = new View('device_net_deps');

		// Show list of avaialable local services
		$interfaces = $device->NetworkProperty;
		$depDisplay = array();
		if ($interfaces && $interfaces->count() > 0) {

			foreach ($interfaces as $interface) {

				// Only add if there are dependencies
				if (isset($interface->ParentNetwork) && $interface->ParentNetwork->count() > 0) {
					$depDisplay[] = array(
						'id' => $interface->id,
						'nic' => $interface->nic,
						'mac' => $interface->mac,
						'blade' => isset($interface->conn_blade) ? $interface->conn_blade : 'n/a',
						'port' => isset($interface->conn_port) ? $interface->conn_port : 'n/a',
						'vlan' => isset($interface->conn_vlan) ? $interface->conn_vlan : 'n/a',
						'deps' => array()
					);

					foreach ($interface->ParentNetwork as $depIntf) {
						$depDisplay[count($depDisplay) - 1]['deps'][] = array(
							'deviceName' => $depIntf->Device->name,
							'id' => $depIntf->id,
							'nic' => $depIntf->nic,
							'mac' => $depIntf->mac,
							'blade' => isset($depIntf->conn_blade) ? $depIntf->conn_blade : 'n/a',
							'port' => isset($depIntf->conn_port) ? $depIntf->conn_port : 'n/a',
							'vlan' => isset($depIntf->conn_vlan) ? $depIntf->conn_vlan : 'n/a'
						);
					}
				}
			}
		}
		$this->template->content->networkDeps->depIntfProps = $depDisplay;
		$this->template->content->networkDeps->definedInterfaces = $device->NetworkProperty;

		$this->template->content->networkDeps->device = $device;
		$this->template->content->networkDeps->userFuncAreas = $userfuncAreas;
	}

	/**
	 * Inserts the service dependencies into the view.
	 *
	 * @param Doctrine_Record $device The device.
	 * @param Doctrine_Collection $userFuncArea The collection of user Functional
	 * Areas.
	 */
	function _insertServiceDependencies($device, $userfuncAreas) {

		// Add the SERVICE DEPENDENCY PROPERTY template
		$this->template->content->serviceDeps = new View('device_serv_deps');

		$services = $device->ServiceProperty;
		$depDisplay = array();
		if ($services && $services->count() > 0) {

			// Show list of local services with remote dependency
			foreach ($services as $service) {

				// Add to local the dependency
				if (isset($service->DependsOn) && $service->DependsOn->count() > 0) {
					$depDisplay[] = array(
						'id' => $service->Service->id,
						'name' => $service->Service->name,
						'port' => $service->Service->port,
						'protocol' => $service->Service->protocol,
						'deps' => array()
					);

					// Add local -> remote dependency
					foreach ($service->DependsOn as $depServ) {
						$depDisplay[count($depDisplay) - 1]['deps'][] = array(
							'deviceName' => $depServ->Device->name,
							'id' => $depServ->Service->id,
							'name' => $depServ->Service->name,
							'port' => $depServ->Service->port,
							'protocol' => $depServ->Service->protocol
						);
					}
				}
			}
		}

		$this->template->content->serviceDeps->depServProps = $depDisplay;
		$this->template->content->serviceDeps->definedServices = $device->ServiceProperty;

		$this->template->content->serviceDeps->device = $device;
		$this->template->content->serviceDeps->userFuncAreas = $userfuncAreas;
	}

	/**
	 * Converts a MAC address into dashed notation.
	 *
	 * <p>Checks whether the incoming MAC address is 12 characters and does not
	 * contain any dashes.  If so, it will then add a dash in between every 2
	 * characters.
	 * </p>
	 *
	 * @param $mac The MAC addres to convert to dashed format.
	 * @return string The converted MAC address.  If the incoming MAC address
	 * is not converted the original is returned.
	 */
	function _convertMAC($mac) {

		$dashedMAC = $mac;

		// Check if it is valid MAC and it is not in dashed notation
		if (strlen($mac) == 12 && substr_count($mac, "-") == 0) {

			// Replace every 2 chars with same 2 chars followed by - (dash)
			$dashedMAC = preg_replace('/([\dA-Fa-f]{2})/', "$1-", $dashedMAC);
			// Convert hex to upper case and remove trailing -
			$dashedMAC = strtoupper(substr($dashedMAC, 0, -1));
		}

		return $dashedMAC;
	}
}
?>
