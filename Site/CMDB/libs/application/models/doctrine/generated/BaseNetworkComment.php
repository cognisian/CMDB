<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNetworkComment extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('NetworkComment');
        $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'unsigned' => '1', 'primary' => true, 'autoincrement' => true, 'length' => '4'));
        $this->hasColumn('comment', 'string', null, array('type' => 'string', 'notnull' => true));
        $this->hasColumn('network_prop_id', 'integer', 4, array('type' => 'integer', 'unsigned' => '1', 'length' => '4'));
    }

    public function setUp()
    {
        $this->hasOne('NetworkProperty', array('local' => 'network_prop_id',
                                               'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable(array('created' => array('name' => 'created', 'type' => 'timestamp', 'format' => 'Y-m-d H:i:s')));
        $searchable0 = new Doctrine_Template_Searchable(array('fields' => array(0 => 'comment')));
        $this->actAs($timestampable0);
        $this->actAs($searchable0);
    }
}