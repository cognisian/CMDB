<?php
/**
 * Alters the CMDB to include fields/tables for Voice project
 *
 * @author schalmers
 */
class UpdateToVoice extends Doctrine_Migration {

	/**
	 * Adds new column to the Location table to migrate to new version 1.
	 * Adds new DeviceOwner table to migrate to new version 1.
	 * Adds new column to the User to enable signon.
	 */
	public function up() {

		// Update Location
		$options = array(
			'length' => 16
		);
		$this->addColumn('Location', 'jack', 'string', $options);

		// Create DeviceOwner
		$fields = array(
			'device_id' => array(
				'type' => 'integer',
				'length' => 4,
				'primary' => true,
				'unsigned' => true,
				'notnull' => true,
				'autoincrement' => false
			),
			'owner_id' => array(
				'type' => 'integer',
				'length' => 4,
				'primary' => true,
				'unsigned' => true,
				'notnull' => true,
				'autoincrement' => false
			)
		);
		$this->createTable('DeviceOwner', $fields);

		// Update User
		$options = array(
			'length' => 1,
			'unsigned' => 1,
			'notnull' => true,
			'default' => 0
		);
		$this->addColumn('User', 'logon_enabled', 'integer', $options);
	}

	/**
	 * Reverts the Location table from version 1 to version 0.
	 * Removes the DeviceOwner table from version 1 to version 0.
	 * Reverts the User table from version 1 to version 0.
	 */
	public function down() {

		// Remove jalck field from Location
		$this->removeColumn('Location', 'jack');

		// remove DeviceOwner table
		$this->dropTable('DeviceOwner');

		// Remove jalck field from Location
		$this->removeColumn('User', 'logon_enabled');
	}

	/**
	 * Adds new rows to the DeviceType table.
	 */
	public function preUp() {
		$deviceType = new DeviceType();
		$deviceType->type = 'Phone';
		$deviceType->save();

		$deviceType = new DeviceType();
		$deviceType->type = 'IP Phone';
		$deviceType->save();
	}

	/**
	 * Adds new rows to the DeviceType table.
	 */
	public function postUp() {

		// Ensure that all Current users are enabled to signon
		$query = Doctrine_Query::create()
					->update('User')
					->set('logon_enabled', 1);
		$query->execute();
	}

	/**
	 * Removes the new DeviceTypes.
	 */
	public function preDown() {
		// Delete all users that are not enabled to signon
		$query = Doctrine_Query::create()
					->delete('User u')
					->where('u.logon_enabled = 0');
		$query->execute();
	}

	/**
	 * Removes the new DeviceTypes.
	 */
	public function postDown() {
		$devicePhone = Doctrine::getTable('DeviceType')->findOneByType('Phone');
		$devicePhone->delete();

		$devicePhone = Doctrine::getTable('DeviceType')->findOneByType('IP Phone');
		$devicePhone->delete();
	}
}
?>
