<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrRegisterEmailInfo', 'doctrineConn');

/**
 * BaseTrRegisterEmailInfo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property  $id
 * @property  $email
 * @property  $username
 * @property  $password
 * @property  $time
 * @property  $status
 * @property  $verifycode
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrRegisterEmailInfo extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('tr_register_email_info');
        $this->hasColumn('id', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('email', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('username', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('password', '', null, array(
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
        $this->hasColumn('status', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('verifycode', '', null, array(
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
        
    }
}