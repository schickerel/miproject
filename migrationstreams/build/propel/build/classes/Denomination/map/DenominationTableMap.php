<?php

namespace Denomination\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'denominations' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.Denomination.map
 */
class DenominationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Denomination.map.DenominationTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('denominations');
        $this->setPhpName('Denomination');
        $this->setClassname('Denomination\\Denomination');
        $this->setPackage('Denomination');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('denomination', 'Denomination', 'VARCHAR', true, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Person', 'Person\\Person', RelationMap::ONE_TO_MANY, array('id' => 'denomination_id', ), null, null, 'Persons');
    } // buildRelations()

} // DenominationTableMap
