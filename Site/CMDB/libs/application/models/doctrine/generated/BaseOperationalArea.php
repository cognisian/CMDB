<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseOperationalArea extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('OperationalArea');
        $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'unsigned' => '1', 'primary' => true, 'autoincrement' => true, 'length' => '4'));
        $this->hasColumn('code', 'string', 3, array('type' => 'string', 'fixed' => 1, 'notnull' => true, 'length' => '3'));
        $this->hasColumn('type', 'string', 24, array('type' => 'string', 'default' => 'Server', 'notnull' => true, 'length' => '24'));


        $this->index('OpArea', array('fields' => array(0 => 'code', 1 => 'type')));
    }

    public function setUp()
    {
        $this->hasMany('Device', array('local' => 'id',
                                       'foreign' => 'op_area_id'));
    }
}