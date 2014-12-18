<?php

namespace ProfessionalCategory\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Person\Person;
use ProfessionalCategory\ProfessionalCategory;
use ProfessionalCategory\ProfessionalCategoryPeer;
use ProfessionalCategory\ProfessionalCategoryQuery;

/**
 * Base class that represents a query for the 'professional_categories' table.
 *
 *
 *
 * @method ProfessionalCategoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ProfessionalCategoryQuery orderByProfessionalCategory($order = Criteria::ASC) Order by the professional_category column
 *
 * @method ProfessionalCategoryQuery groupById() Group by the id column
 * @method ProfessionalCategoryQuery groupByProfessionalCategory() Group by the professional_category column
 *
 * @method ProfessionalCategoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ProfessionalCategoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ProfessionalCategoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ProfessionalCategoryQuery leftJoinPerson($relationAlias = null) Adds a LEFT JOIN clause to the query using the Person relation
 * @method ProfessionalCategoryQuery rightJoinPerson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Person relation
 * @method ProfessionalCategoryQuery innerJoinPerson($relationAlias = null) Adds a INNER JOIN clause to the query using the Person relation
 *
 * @method ProfessionalCategory findOne(PropelPDO $con = null) Return the first ProfessionalCategory matching the query
 * @method ProfessionalCategory findOneOrCreate(PropelPDO $con = null) Return the first ProfessionalCategory matching the query, or a new ProfessionalCategory object populated from the query conditions when no match is found
 *
 * @method ProfessionalCategory findOneByProfessionalCategory(string $professional_category) Return the first ProfessionalCategory filtered by the professional_category column
 *
 * @method array findById(int $id) Return ProfessionalCategory objects filtered by the id column
 * @method array findByProfessionalCategory(string $professional_category) Return ProfessionalCategory objects filtered by the professional_category column
 *
 * @package    propel.generator.ProfessionalCategory.om
 */
abstract class BaseProfessionalCategoryQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseProfessionalCategoryQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'migrationstreams';
        }
        if (null === $modelName) {
            $modelName = 'ProfessionalCategory\\ProfessionalCategory';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ProfessionalCategoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ProfessionalCategoryQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ProfessionalCategoryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ProfessionalCategoryQuery) {
            return $criteria;
        }
        $query = new ProfessionalCategoryQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   ProfessionalCategory|ProfessionalCategory[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ProfessionalCategoryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ProfessionalCategoryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 ProfessionalCategory A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 ProfessionalCategory A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `professional_category` FROM `professional_categories` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new ProfessionalCategory();
            $obj->hydrate($row);
            ProfessionalCategoryPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return ProfessionalCategory|ProfessionalCategory[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|ProfessionalCategory[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ProfessionalCategoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ProfessionalCategoryPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ProfessionalCategoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ProfessionalCategoryPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProfessionalCategoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ProfessionalCategoryPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ProfessionalCategoryPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfessionalCategoryPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the professional_category column
     *
     * Example usage:
     * <code>
     * $query->filterByProfessionalCategory('fooValue');   // WHERE professional_category = 'fooValue'
     * $query->filterByProfessionalCategory('%fooValue%'); // WHERE professional_category LIKE '%fooValue%'
     * </code>
     *
     * @param     string $professionalCategory The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProfessionalCategoryQuery The current query, for fluid interface
     */
    public function filterByProfessionalCategory($professionalCategory = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($professionalCategory)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $professionalCategory)) {
                $professionalCategory = str_replace('*', '%', $professionalCategory);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ProfessionalCategoryPeer::PROFESSIONAL_CATEGORY, $professionalCategory, $comparison);
    }

    /**
     * Filter the query by a related Person object
     *
     * @param   Person|PropelObjectCollection $person  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProfessionalCategoryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPerson($person, $comparison = null)
    {
        if ($person instanceof Person) {
            return $this
                ->addUsingAlias(ProfessionalCategoryPeer::ID, $person->getProfessionalCategoryId(), $comparison);
        } elseif ($person instanceof PropelObjectCollection) {
            return $this
                ->usePersonQuery()
                ->filterByPrimaryKeys($person->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPerson() only accepts arguments of type Person or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Person relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ProfessionalCategoryQuery The current query, for fluid interface
     */
    public function joinPerson($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Person');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Person');
        }

        return $this;
    }

    /**
     * Use the Person relation Person object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Person\PersonQuery A secondary query class using the current class as primary query
     */
    public function usePersonQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPerson($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Person', '\Person\PersonQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ProfessionalCategory $professionalCategory Object to remove from the list of results
     *
     * @return ProfessionalCategoryQuery The current query, for fluid interface
     */
    public function prune($professionalCategory = null)
    {
        if ($professionalCategory) {
            $this->addUsingAlias(ProfessionalCategoryPeer::ID, $professionalCategory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
