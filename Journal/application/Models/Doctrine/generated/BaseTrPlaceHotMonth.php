<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrPlaceHotMonth', 'doctrineConn');

/**
 * BaseTrPlaceHotMonth
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property  $id
 * @property  $plc_id_ref
 * @property  $month
 * @property TrPlace $TrPlace
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrPlaceHotMonth extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('tr_place_hot_month');
        $this->hasColumn('id', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('plc_id_ref', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('month', '', null, array(
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
        $this->hasOne('TrPlace', array(
             'local' => 'plc_id_ref'));
    }
}