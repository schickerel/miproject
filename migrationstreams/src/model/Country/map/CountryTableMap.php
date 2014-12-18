<?php

namespace Country\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'countries' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.Country.map
 */
class CountryTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Country.map.CountryTableMap';

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
        $this->setName('countries');
        $this->setPhpName('Country');
        $this->setClassname('Country\\Country');
        $this->setPackage('Country');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('country', 'Country', 'VARCHAR', true, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Person', 'Person\\Person', RelationMap::ONE_TO_MANY, array('id' => 'country_of_birth_id', ), null, null, 'Persons');
        $this->addRelation('Migration', 'Migration\\Migration', RelationMap::ONE_TO_MANY, array('id' => 'country_id', ), null, null, 'Migrations');
    } // buildRelations()

} // CountryTableMap
