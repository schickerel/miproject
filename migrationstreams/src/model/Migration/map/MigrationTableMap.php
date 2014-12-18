<?php

namespace Migration\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'migrations' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.Migration.map
 */
class MigrationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Migration.map.MigrationTableMap';

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
        $this->setName('migrations');
        $this->setPhpName('Migration');
        $this->setClassname('Migration\\Migration');
        $this->setPackage('Migration');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 255, null);
        $this->addForeignKey('country_id', 'CountryId', 'INTEGER', 'countries', 'id', true, null, null);
        $this->addColumn('month', 'Month', 'INTEGER', true, null, null);
        $this->addColumn('year', 'Year', 'INTEGER', true, null, null);
        $this->addForeignKey('person_id', 'PersonId', 'INTEGER', 'persons', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Country', 'Country\\Country', RelationMap::MANY_TO_ONE, array('country_id' => 'id', ), null, null);
        $this->addRelation('Person', 'Person\\Person', RelationMap::MANY_TO_ONE, array('person_id' => 'id', ), null, null);
    } // buildRelations()

} // MigrationTableMap
