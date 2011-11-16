<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrBlacklist', 'doctrineConn');

/**
 * BaseTrBlacklist
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property  $id
 * @property  $usr_id_self_ref
 * @property  $usr_id_other_ref
 * @property TrUser $TrUser
 * @property TrUser $TrUser_2
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrBlacklist extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('tr_blacklist');
        $this->hasColumn('id', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('usr_id_self_ref', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('usr_id_other_ref', '', null, array(
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
             'local' => 'usr_id_self_ref'));

        $this->hasOne('TrUser as TrUser_2', array(
             'local' => 'usr_id_other_ref'));
    }
}