<?php

namespace Person\om;

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
use Country\Country;
use Denomination\Denomination;
use Migration\Migration;
use Person\Person;
use Person\PersonPeer;
use Person\PersonQuery;
use ProfessionalCategory\ProfessionalCategory;

/**
 * Base class that represents a query for the 'persons' table.
 *
 *
 *
 * @method PersonQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PersonQuery orderByFirstName($order = Criteria::ASC) Order by the first_name column
 * @method PersonQuery orderByLastName($order = Criteria::ASC) Order by the last_name column
 * @method PersonQuery orderByBirthday($order = Criteria::ASC) Order by the birthday column
 * @method PersonQuery orderByDayOfDeath($order = Criteria::ASC) Order by the day_of_death column
 * @method PersonQuery orderByDenominationId($order = Criteria::ASC) Order by the denomination_id column
 * @method PersonQuery orderByProfessionalCategoryId($order = Criteria::ASC) Order by the professional_category_id column
 * @method PersonQuery orderByProfession($order = Criteria::ASC) Order by the profession column
 * @method PersonQuery orderByCountryOfBirthId($order = Criteria::ASC) Order by the country_of_birth_id column
 * @method PersonQuery orderByPlaceOfBirth($order = Criteria::ASC) Order by the place_of_birth column
 *
 * @method PersonQuery groupById() Group by the id column
 * @method PersonQuery groupByFirstName() Group by the first_name column
 * @method PersonQuery groupByLastName() Group by the last_name column
 * @method PersonQuery groupByBirthday() Group by the birthday column
 * @method PersonQuery groupByDayOfDeath() Group by the day_of_death column
 * @method PersonQuery groupByDenominationId() Group by the denomination_id column
 * @method PersonQuery groupByProfessionalCategoryId() Group by the professional_category_id column
 * @method PersonQuery groupByProfession() Group by the profession column
 * @method PersonQuery groupByCountryOfBirthId() Group by the country_of_birth_id column
 * @method PersonQuery groupByPlaceOfBirth() Group by the place_of_birth column
 *
 * @method PersonQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PersonQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PersonQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PersonQuery leftJoinDenomination($relationAlias = null) Adds a LEFT JOIN clause to the query using the Denomination relation
 * @method PersonQuery rightJoinDenomination($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Denomination relation
 * @method PersonQuery innerJoinDenomination($relationAlias = null) Adds a INNER JOIN clause to the query using the Denomination relation
 *
 * @method PersonQuery leftJoinProfessionalCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProfessionalCategory relation
 * @method PersonQuery rightJoinProfessionalCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProfessionalCategory relation
 * @method PersonQuery innerJoinProfessionalCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the ProfessionalCategory relation
 *
 * @method PersonQuery leftJoinCountry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Country relation
 * @method PersonQuery rightJoinCountry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Country relation
 * @method PersonQuery innerJoinCountry($relationAlias = null) Adds a INNER JOIN clause to the query using the Country relation
 *
 * @method PersonQuery leftJoinMigration($relationAlias = null) Adds a LEFT JOIN clause to the query using the Migration relation
 * @method PersonQuery rightJoinMigration($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Migration relation
 * @method PersonQuery innerJoinMigration($relationAlias = null) Adds a INNER JOIN clause to the query using the Migration relation
 *
 * @method Person findOne(PropelPDO $con = null) Return the first Person matching the query
 * @method Person findOneOrCreate(PropelPDO $con = null) Return the first Person matching the query, or a new Person object populated from the query conditions when no match is found
 *
 * @method Person findOneByFirstName(string $first_name) Return the first Person filtered by the first_name column
 * @method Person findOneByLastName(string $last_name) Return the first Person filtered by the last_name column
 * @method Person findOneByBirthday(string $birthday) Return the first Person filtered by the birthday column
 * @method Person findOneByDayOfDeath(string $day_of_death) Return the first Person filtered by the day_of_death column
 * @method Person findOneByDenominationId(int $denomination_id) Return the first Person filtered by the denomination_id column
 * @method Person findOneByProfessionalCategoryId(int $professional_category_id) Return the first Person filtered by the professional_category_id column
 * @method Person findOneByProfession(string $profession) Return the first Person filtered by the profession column
 * @method Person findOneByCountryOfBirthId(int $country_of_birth_id) Return the first Person filtered by the country_of_birth_id column
 * @method Person findOneByPlaceOfBirth(string $place_of_birth) Return the first Person filtered by the place_of_birth column
 *
 * @method array findById(int $id) Return Person objects filtered by the id column
 * @method array findByFirstName(string $first_name) Return Person objects filtered by the first_name column
 * @method array findByLastName(string $last_name) Return Person objects filtered by the last_name column
 * @method array findByBirthday(string $birthday) Return Person objects filtered by the birthday column
 * @method array findByDayOfDeath(string $day_of_death) Return Person objects filtered by the day_of_death column
 * @method array findByDenominationId(int $denomination_id) Return Person objects filtered by the denomination_id column
 * @method array findByProfessionalCategoryId(int $professional_category_id) Return Person objects filtered by the professional_category_id column
 * @method array findByProfession(string $profession) Return Person objects filtered by the profession column
 * @method array findByCountryOfBirthId(int $country_of_birth_id) Return Person objects filtered by the country_of_birth_id column
 * @method array findByPlaceOfBirth(string $place_of_birth) Return Person objects filtered by the place_of_birth column
 *
 * @package    propel.generator.Person.om
 */
abstract class BasePersonQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePersonQuery object.
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
            $modelName = 'Person\\Person';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PersonQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PersonQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PersonQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PersonQuery) {
            return $criteria;
        }
        $query = new PersonQuery(null, null, $modelAlias);

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
     * @return   Person|Person[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PersonPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Person A model object, or null if the key is not found
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
     * @return                 Person A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `first_name`, `last_name`, `birthday`, `day_of_death`, `denomination_id`, `professional_category_id`, `profession`, `country_of_birth_id`, `place_of_birth` FROM `persons` WHERE `id` = :p0';
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
            $obj = new Person();
            $obj->hydrate($row);
            PersonPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Person|Person[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Person[]|mixed the list of results, formatted by the current formatter
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
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PersonPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PersonPeer::ID, $keys, Criteria::IN);
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
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PersonPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PersonPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%'); // WHERE first_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstName)) {
                $firstName = str_replace('*', '%', $firstName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PersonPeer::FIRST_NAME, $firstName, $comparison);
    }

    /**
     * Filter the query on the last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%'); // WHERE last_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastName)) {
                $lastName = str_replace('*', '%', $lastName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PersonPeer::LAST_NAME, $lastName, $comparison);
    }

    /**
     * Filter the query on the birthday column
     *
     * Example usage:
     * <code>
     * $query->filterByBirthday('2011-03-14'); // WHERE birthday = '2011-03-14'
     * $query->filterByBirthday('now'); // WHERE birthday = '2011-03-14'
     * $query->filterByBirthday(array('max' => 'yesterday')); // WHERE birthday < '2011-03-13'
     * </code>
     *
     * @param     mixed $birthday The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByBirthday($birthday = null, $comparison = null)
    {
        if (is_array($birthday)) {
            $useMinMax = false;
            if (isset($birthday['min'])) {
                $this->addUsingAlias(PersonPeer::BIRTHDAY, $birthday['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($birthday['max'])) {
                $this->addUsingAlias(PersonPeer::BIRTHDAY, $birthday['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonPeer::BIRTHDAY, $birthday, $comparison);
    }

    /**
     * Filter the query on the day_of_death column
     *
     * Example usage:
     * <code>
     * $query->filterByDayOfDeath('2011-03-14'); // WHERE day_of_death = '2011-03-14'
     * $query->filterByDayOfDeath('now'); // WHERE day_of_death = '2011-03-14'
     * $query->filterByDayOfDeath(array('max' => 'yesterday')); // WHERE day_of_death < '2011-03-13'
     * </code>
     *
     * @param     mixed $dayOfDeath The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByDayOfDeath($dayOfDeath = null, $comparison = null)
    {
        if (is_array($dayOfDeath)) {
            $useMinMax = false;
            if (isset($dayOfDeath['min'])) {
                $this->addUsingAlias(PersonPeer::DAY_OF_DEATH, $dayOfDeath['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dayOfDeath['max'])) {
                $this->addUsingAlias(PersonPeer::DAY_OF_DEATH, $dayOfDeath['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonPeer::DAY_OF_DEATH, $dayOfDeath, $comparison);
    }

    /**
     * Filter the query on the denomination_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDenominationId(1234); // WHERE denomination_id = 1234
     * $query->filterByDenominationId(array(12, 34)); // WHERE denomination_id IN (12, 34)
     * $query->filterByDenominationId(array('min' => 12)); // WHERE denomination_id >= 12
     * $query->filterByDenominationId(array('max' => 12)); // WHERE denomination_id <= 12
     * </code>
     *
     * @see       filterByDenomination()
     *
     * @param     mixed $denominationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByDenominationId($denominationId = null, $comparison = null)
    {
        if (is_array($denominationId)) {
            $useMinMax = false;
            if (isset($denominationId['min'])) {
                $this->addUsingAlias(PersonPeer::DENOMINATION_ID, $denominationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($denominationId['max'])) {
                $this->addUsingAlias(PersonPeer::DENOMINATION_ID, $denominationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonPeer::DENOMINATION_ID, $denominationId, $comparison);
    }

    /**
     * Filter the query on the professional_category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProfessionalCategoryId(1234); // WHERE professional_category_id = 1234
     * $query->filterByProfessionalCategoryId(array(12, 34)); // WHERE professional_category_id IN (12, 34)
     * $query->filterByProfessionalCategoryId(array('min' => 12)); // WHERE professional_category_id >= 12
     * $query->filterByProfessionalCategoryId(array('max' => 12)); // WHERE professional_category_id <= 12
     * </code>
     *
     * @see       filterByProfessionalCategory()
     *
     * @param     mixed $professionalCategoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByProfessionalCategoryId($professionalCategoryId = null, $comparison = null)
    {
        if (is_array($professionalCategoryId)) {
            $useMinMax = false;
            if (isset($professionalCategoryId['min'])) {
                $this->addUsingAlias(PersonPeer::PROFESSIONAL_CATEGORY_ID, $professionalCategoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($professionalCategoryId['max'])) {
                $this->addUsingAlias(PersonPeer::PROFESSIONAL_CATEGORY_ID, $professionalCategoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonPeer::PROFESSIONAL_CATEGORY_ID, $professionalCategoryId, $comparison);
    }

    /**
     * Filter the query on the profession column
     *
     * Example usage:
     * <code>
     * $query->filterByProfession('fooValue');   // WHERE profession = 'fooValue'
     * $query->filterByProfession('%fooValue%'); // WHERE profession LIKE '%fooValue%'
     * </code>
     *
     * @param     string $profession The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByProfession($profession = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($profession)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $profession)) {
                $profession = str_replace('*', '%', $profession);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PersonPeer::PROFESSION, $profession, $comparison);
    }

    /**
     * Filter the query on the country_of_birth_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCountryOfBirthId(1234); // WHERE country_of_birth_id = 1234
     * $query->filterByCountryOfBirthId(array(12, 34)); // WHERE country_of_birth_id IN (12, 34)
     * $query->filterByCountryOfBirthId(array('min' => 12)); // WHERE country_of_birth_id >= 12
     * $query->filterByCountryOfBirthId(array('max' => 12)); // WHERE country_of_birth_id <= 12
     * </code>
     *
     * @see       filterByCountry()
     *
     * @param     mixed $countryOfBirthId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByCountryOfBirthId($countryOfBirthId = null, $comparison = null)
    {
        if (is_array($countryOfBirthId)) {
            $useMinMax = false;
            if (isset($countryOfBirthId['min'])) {
                $this->addUsingAlias(PersonPeer::COUNTRY_OF_BIRTH_ID, $countryOfBirthId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryOfBirthId['max'])) {
                $this->addUsingAlias(PersonPeer::COUNTRY_OF_BIRTH_ID, $countryOfBirthId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonPeer::COUNTRY_OF_BIRTH_ID, $countryOfBirthId, $comparison);
    }

    /**
     * Filter the query on the place_of_birth column
     *
     * Example usage:
     * <code>
     * $query->filterByPlaceOfBirth('fooValue');   // WHERE place_of_birth = 'fooValue'
     * $query->filterByPlaceOfBirth('%fooValue%'); // WHERE place_of_birth LIKE '%fooValue%'
     * </code>
     *
     * @param     string $placeOfBirth The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByPlaceOfBirth($placeOfBirth = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($placeOfBirth)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $placeOfBirth)) {
                $placeOfBirth = str_replace('*', '%', $placeOfBirth);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PersonPeer::PLACE_OF_BIRTH, $placeOfBirth, $comparison);
    }

    /**
     * Filter the query by a related Denomination object
     *
     * @param   Denomination|PropelObjectCollection $denomination The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PersonQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDenomination($denomination, $comparison = null)
    {
        if ($denomination instanceof Denomination) {
            return $this
                ->addUsingAlias(PersonPeer::DENOMINATION_ID, $denomination->getId(), $comparison);
        } elseif ($denomination instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PersonPeer::DENOMINATION_ID, $denomination->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDenomination() only accepts arguments of type Denomination or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Denomination relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function joinDenomination($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Denomination');

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
            $this->addJoinObject($join, 'Denomination');
        }

        return $this;
    }

    /**
     * Use the Denomination relation Denomination object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Denomination\DenominationQuery A secondary query class using the current class as primary query
     */
    public function useDenominationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDenomination($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Denomination', '\Denomination\DenominationQuery');
    }

    /**
     * Filter the query by a related ProfessionalCategory object
     *
     * @param   ProfessionalCategory|PropelObjectCollection $professionalCategory The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PersonQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProfessionalCategory($professionalCategory, $comparison = null)
    {
        if ($professionalCategory instanceof ProfessionalCategory) {
            return $this
                ->addUsingAlias(PersonPeer::PROFESSIONAL_CATEGORY_ID, $professionalCategory->getId(), $comparison);
        } elseif ($professionalCategory instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PersonPeer::PROFESSIONAL_CATEGORY_ID, $professionalCategory->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByProfessionalCategory() only accepts arguments of type ProfessionalCategory or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProfessionalCategory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function joinProfessionalCategory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProfessionalCategory');

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
            $this->addJoinObject($join, 'ProfessionalCategory');
        }

        return $this;
    }

    /**
     * Use the ProfessionalCategory relation ProfessionalCategory object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \ProfessionalCategory\ProfessionalCategoryQuery A secondary query class using the current class as primary query
     */
    public function useProfessionalCategoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProfessionalCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProfessionalCategory', '\ProfessionalCategory\ProfessionalCategoryQuery');
    }

    /**
     * Filter the query by a related Country object
     *
     * @param   Country|PropelObjectCollection $country The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PersonQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCountry($country, $comparison = null)
    {
        if ($country instanceof Country) {
            return $this
                ->addUsingAlias(PersonPeer::COUNTRY_OF_BIRTH_ID, $country->getId(), $comparison);
        } elseif ($country instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PersonPeer::COUNTRY_OF_BIRTH_ID, $country->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCountry() only accepts arguments of type Country or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Country relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function joinCountry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Country');

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
            $this->addJoinObject($join, 'Country');
        }

        return $this;
    }

    /**
     * Use the Country relation Country object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Country\CountryQuery A secondary query class using the current class as primary query
     */
    public function useCountryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCountry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Country', '\Country\CountryQuery');
    }

    /**
     * Filter the query by a related Migration object
     *
     * @param   Migration|PropelObjectCollection $migration  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PersonQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMigration($migration, $comparison = null)
    {
        if ($migration instanceof Migration) {
            return $this
                ->addUsingAlias(PersonPeer::ID, $migration->getPersonId(), $comparison);
        } elseif ($migration instanceof PropelObjectCollection) {
            return $this
                ->useMigrationQuery()
                ->filterByPrimaryKeys($migration->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMigration() only accepts arguments of type Migration or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Migration relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function joinMigration($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Migration');

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
            $this->addJoinObject($join, 'Migration');
        }

        return $this;
    }

    /**
     * Use the Migration relation Migration object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Migration\MigrationQuery A secondary query class using the current class as primary query
     */
    public function useMigrationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMigration($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Migration', '\Migration\MigrationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Person $person Object to remove from the list of results
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function prune($person = null)
    {
        if ($person) {
            $this->addUsingAlias(PersonPeer::ID, $person->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
