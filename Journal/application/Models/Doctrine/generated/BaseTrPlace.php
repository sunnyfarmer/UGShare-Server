<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrPlace', 'doctrineConn');

/**
 * BaseTrPlace
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property  $id
 * @property  $name
 * @property  $cty_id_ref
 * @property  $slc_id_ref
 * @property  $longitude
 * @property  $latitude
 * @property  $score
 * @property  $markCount
 * @property  $address
 * @property TrCity $TrCity
 * @property TrSublocality $TrSublocality
 * @property Doctrine_Collection $TrJournalPlace
 * @property Doctrine_Collection $TrPlaceHotMonth
 * @property Doctrine_Collection $TrPlaceHotTag
 * @property Doctrine_Collection $TrPlaceTag
 * @property Doctrine_Collection $TrUserMark
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrPlace extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('tr_place');
        $this->hasColumn('id', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('name', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('cty_id_ref', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('slc_id_ref', '', null, array(
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
        $this->hasColumn('score', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('markCount', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('address', '', null, array(
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
        $this->hasOne('TrCity', array(
             'local' => 'cty_id_ref'));

        $this->hasOne('TrSublocality', array(
             'local' => 'slc_id_ref'));

        $this->hasMany('TrJournalPlace', array(
             'foreign' => 'plc_id_ref'));

        $this->hasMany('TrPlaceHotMonth', array(
             'foreign' => 'plc_id_ref'));

        $this->hasMany('TrPlaceHotTag', array(
             'foreign' => 'plc_id_ref'));

        $this->hasMany('TrPlaceTag', array(
             'foreign' => 'plc_id_ref'));

        $this->hasMany('TrUserMark', array(
             'foreign' => 'plc_id_ref'));
    }
}