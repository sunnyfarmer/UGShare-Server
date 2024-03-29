<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrJournalPlaceJournal', 'doctrineConn');

/**
 * BaseTrJournalPlaceJournal
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property  $id
 * @property  $jpc_id_ref
 * @property  $journal
 * @property  $time
 * @property TrJournalPlace $TrJournalPlace
 * @property Doctrine_Collection $TrPhoto
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrJournalPlaceJournal extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('tr_journal_place_journal');
        $this->hasColumn('id', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('jpc_id_ref', '', null, array(
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('journal', '', null, array(
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
        $this->hasOne('TrJournalPlace', array(
             'local' => 'jpc_id_ref'));

        $this->hasMany('TrPhoto', array(
             'foreign' => 'jpj_id_ref'));
    }
}