<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrUserLocationInfo', 'doctrineConn');

/**
 * BaseTrUserLocationInfo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property  $id
 * @property  $usr_id_ref
 * @property  $isOpen
 * @property  $longitude
 * @property  $latitude
 * @property  $time
 * @property TrUser $TrUser
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrUserLocationInfo extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('tr_user_location_info');
        $this->hasColumn('id', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('usr_id_ref', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('isOpen', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('longitude', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('latitude', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('time', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('TrUser', array(
             'local' => 'usr_id_ref'));
    }
}