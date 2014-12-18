<?php

namespace ProfessionalCategory\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'professional_categories' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.ProfessionalCategory.map
 */
class ProfessionalCategoryTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'ProfessionalCategory.map.ProfessionalCategoryTableMap';

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
        $this->setName('professional_categories');
        $this->setPhpName('ProfessionalCategory');
        $this->setClassname('ProfessionalCategory\\ProfessionalCategory');
        $this->setPackage('ProfessionalCategory');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('professional_category', 'ProfessionalCategory', 'VARCHAR', true, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Person', 'Person\\Person', RelationMap::ONE_TO_MANY, array('id' => 'professional_category_id', ), null, null, 'Persons');
    } // buildRelations()

} // ProfessionalCategoryTableMap
