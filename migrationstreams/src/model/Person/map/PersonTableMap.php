<?php

namespace Person\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'persons' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.Person.map
 */
class PersonTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Person.map.PersonTableMap';

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
        $this->setName('persons');
        $this->setPhpName('Person');
        $this->setClassname('Person\\Person');
        $this->setPackage('Person');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('first_name', 'FirstName', 'VARCHAR', true, 255, null);
        $this->addColumn('last_name', 'LastName', 'VARCHAR', true, 255, null);
        $this->addColumn('birthday', 'Birthday', 'DATE', true, null, null);
        $this->addColumn('day_of_death', 'DayOfDeath', 'DATE', false, null, null);
        $this->addForeignKey('denomination_id', 'DenominationId', 'INTEGER', 'denominations', 'id', true, null, null);
        $this->addForeignKey('professional_category_id', 'ProfessionalCategoryId', 'INTEGER', 'professional_categories', 'id', true, null, null);
        $this->addColumn('profession', 'Profession', 'VARCHAR', true, 255, null);
        $this->addForeignKey('country_of_birth_id', 'CountryOfBirthId', 'INTEGER', 'countries', 'id', true, null, null);
        $this->addColumn('place_of_birth', 'PlaceOfBirth', 'VARCHAR', false, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Denomination', 'Denomination\\Denomination', RelationMap::MANY_TO_ONE, array('denomination_id' => 'id', ), null, null);
        $this->addRelation('ProfessionalCategory', 'ProfessionalCategory\\ProfessionalCategory', RelationMap::MANY_TO_ONE, array('professional_category_id' => 'id', ), null, null);
        $this->addRelation('Country', 'Country\\Country', RelationMap::MANY_TO_ONE, array('country_of_birth_id' => 'id', ), null, null);
        $this->addRelation('Migration', 'Migration\\Migration', RelationMap::ONE_TO_MANY, array('id' => 'person_id', ), null, null, 'Migrations');
    } // buildRelations()

} // PersonTableMap
