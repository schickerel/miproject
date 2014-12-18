<?php

namespace Person\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Country\Country;
use Country\CountryQuery;
use Denomination\Denomination;
use Denomination\DenominationQuery;
use Migration\Migration;
use Migration\MigrationQuery;
use Person\Person;
use Person\PersonPeer;
use Person\PersonQuery;
use ProfessionalCategory\ProfessionalCategory;
use ProfessionalCategory\ProfessionalCategoryQuery;

/**
 * Base class that represents a row from the 'persons' table.
 *
 *
 *
 * @package    propel.generator.Person.om
 */
abstract class BasePerson extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Person\\PersonPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PersonPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the first_name field.
     * @var        string
     */
    protected $first_name;

    /**
     * The value for the last_name field.
     * @var        string
     */
    protected $last_name;

    /**
     * The value for the birthday field.
     * @var        string
     */
    protected $birthday;

    /**
     * The value for the day_of_death field.
     * @var        string
     */
    protected $day_of_death;

    /**
     * The value for the denomination_id field.
     * @var        int
     */
    protected $denomination_id;

    /**
     * The value for the professional_category_id field.
     * @var        int
     */
    protected $professional_category_id;

    /**
     * The value for the profession field.
     * @var        string
     */
    protected $profession;

    /**
     * The value for the country_of_birth_id field.
     * @var        int
     */
    protected $country_of_birth_id;

    /**
     * The value for the place_of_birth field.
     * @var        string
     */
    protected $place_of_birth;

    /**
     * @var        Denomination
     */
    protected $aDenomination;

    /**
     * @var        ProfessionalCategory
     */
    protected $aProfessionalCategory;

    /**
     * @var        Country
     */
    protected $aCountry;

    /**
     * @var        PropelObjectCollection|Migration[] Collection to store aggregation of Migration objects.
     */
    protected $collMigrations;
    protected $collMigrationsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $migrationsScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [first_name] column value.
     *
     * @return string
     */
    public function getFirstName()
    {

        return $this->first_name;
    }

    /**
     * Get the [last_name] column value.
     *
     * @return string
     */
    public function getLastName()
    {

        return $this->last_name;
    }

    /**
     * Get the [optionally formatted] temporal [birthday] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBirthday($format = '%x')
    {
        if ($this->birthday === null) {
            return null;
        }

        if ($this->birthday === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->birthday);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->birthday, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [day_of_death] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDayOfDeath($format = '%x')
    {
        if ($this->day_of_death === null) {
            return null;
        }

        if ($this->day_of_death === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->day_of_death);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->day_of_death, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [denomination_id] column value.
     *
     * @return int
     */
    public function getDenominationId()
    {

        return $this->denomination_id;
    }

    /**
     * Get the [professional_category_id] column value.
     *
     * @return int
     */
    public function getProfessionalCategoryId()
    {

        return $this->professional_category_id;
    }

    /**
     * Get the [profession] column value.
     *
     * @return string
     */
    public function getProfession()
    {

        return $this->profession;
    }

    /**
     * Get the [country_of_birth_id] column value.
     *
     * @return int
     */
    public function getCountryOfBirthId()
    {

        return $this->country_of_birth_id;
    }

    /**
     * Get the [place_of_birth] column value.
     *
     * @return string
     */
    public function getPlaceOfBirth()
    {

        return $this->place_of_birth;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PersonPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [first_name] column.
     *
     * @param  string $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[] = PersonPeer::FIRST_NAME;
        }


        return $this;
    } // setFirstName()

    /**
     * Set the value of [last_name] column.
     *
     * @param  string $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[] = PersonPeer::LAST_NAME;
        }


        return $this;
    } // setLastName()

    /**
     * Sets the value of [birthday] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Person The current object (for fluent API support)
     */
    public function setBirthday($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->birthday !== null || $dt !== null) {
            $currentDateAsString = ($this->birthday !== null && $tmpDt = new DateTime($this->birthday)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->birthday = $newDateAsString;
                $this->modifiedColumns[] = PersonPeer::BIRTHDAY;
            }
        } // if either are not null


        return $this;
    } // setBirthday()

    /**
     * Sets the value of [day_of_death] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Person The current object (for fluent API support)
     */
    public function setDayOfDeath($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->day_of_death !== null || $dt !== null) {
            $currentDateAsString = ($this->day_of_death !== null && $tmpDt = new DateTime($this->day_of_death)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->day_of_death = $newDateAsString;
                $this->modifiedColumns[] = PersonPeer::DAY_OF_DEATH;
            }
        } // if either are not null


        return $this;
    } // setDayOfDeath()

    /**
     * Set the value of [denomination_id] column.
     *
     * @param  int $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setDenominationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->denomination_id !== $v) {
            $this->denomination_id = $v;
            $this->modifiedColumns[] = PersonPeer::DENOMINATION_ID;
        }

        if ($this->aDenomination !== null && $this->aDenomination->getId() !== $v) {
            $this->aDenomination = null;
        }


        return $this;
    } // setDenominationId()

    /**
     * Set the value of [professional_category_id] column.
     *
     * @param  int $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setProfessionalCategoryId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->professional_category_id !== $v) {
            $this->professional_category_id = $v;
            $this->modifiedColumns[] = PersonPeer::PROFESSIONAL_CATEGORY_ID;
        }

        if ($this->aProfessionalCategory !== null && $this->aProfessionalCategory->getId() !== $v) {
            $this->aProfessionalCategory = null;
        }


        return $this;
    } // setProfessionalCategoryId()

    /**
     * Set the value of [profession] column.
     *
     * @param  string $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setProfession($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->profession !== $v) {
            $this->profession = $v;
            $this->modifiedColumns[] = PersonPeer::PROFESSION;
        }


        return $this;
    } // setProfession()

    /**
     * Set the value of [country_of_birth_id] column.
     *
     * @param  int $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setCountryOfBirthId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->country_of_birth_id !== $v) {
            $this->country_of_birth_id = $v;
            $this->modifiedColumns[] = PersonPeer::COUNTRY_OF_BIRTH_ID;
        }

        if ($this->aCountry !== null && $this->aCountry->getId() !== $v) {
            $this->aCountry = null;
        }


        return $this;
    } // setCountryOfBirthId()

    /**
     * Set the value of [place_of_birth] column.
     *
     * @param  string $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setPlaceOfBirth($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->place_of_birth !== $v) {
            $this->place_of_birth = $v;
            $this->modifiedColumns[] = PersonPeer::PLACE_OF_BIRTH;
        }


        return $this;
    } // setPlaceOfBirth()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->first_name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->last_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->birthday = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->day_of_death = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->denomination_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->professional_category_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->profession = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->country_of_birth_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->place_of_birth = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 10; // 10 = PersonPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Person object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aDenomination !== null && $this->denomination_id !== $this->aDenomination->getId()) {
            $this->aDenomination = null;
        }
        if ($this->aProfessionalCategory !== null && $this->professional_category_id !== $this->aProfessionalCategory->getId()) {
            $this->aProfessionalCategory = null;
        }
        if ($this->aCountry !== null && $this->country_of_birth_id !== $this->aCountry->getId()) {
            $this->aCountry = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PersonPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aDenomination = null;
            $this->aProfessionalCategory = null;
            $this->aCountry = null;
            $this->collMigrations = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PersonQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                PersonPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aDenomination !== null) {
                if ($this->aDenomination->isModified() || $this->aDenomination->isNew()) {
                    $affectedRows += $this->aDenomination->save($con);
                }
                $this->setDenomination($this->aDenomination);
            }

            if ($this->aProfessionalCategory !== null) {
                if ($this->aProfessionalCategory->isModified() || $this->aProfessionalCategory->isNew()) {
                    $affectedRows += $this->aProfessionalCategory->save($con);
                }
                $this->setProfessionalCategory($this->aProfessionalCategory);
            }

            if ($this->aCountry !== null) {
                if ($this->aCountry->isModified() || $this->aCountry->isNew()) {
                    $affectedRows += $this->aCountry->save($con);
                }
                $this->setCountry($this->aCountry);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->migrationsScheduledForDeletion !== null) {
                if (!$this->migrationsScheduledForDeletion->isEmpty()) {
                    MigrationQuery::create()
                        ->filterByPrimaryKeys($this->migrationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->migrationsScheduledForDeletion = null;
                }
            }

            if ($this->collMigrations !== null) {
                foreach ($this->collMigrations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = PersonPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PersonPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PersonPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PersonPeer::FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`first_name`';
        }
        if ($this->isColumnModified(PersonPeer::LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`last_name`';
        }
        if ($this->isColumnModified(PersonPeer::BIRTHDAY)) {
            $modifiedColumns[':p' . $index++]  = '`birthday`';
        }
        if ($this->isColumnModified(PersonPeer::DAY_OF_DEATH)) {
            $modifiedColumns[':p' . $index++]  = '`day_of_death`';
        }
        if ($this->isColumnModified(PersonPeer::DENOMINATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`denomination_id`';
        }
        if ($this->isColumnModified(PersonPeer::PROFESSIONAL_CATEGORY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`professional_category_id`';
        }
        if ($this->isColumnModified(PersonPeer::PROFESSION)) {
            $modifiedColumns[':p' . $index++]  = '`profession`';
        }
        if ($this->isColumnModified(PersonPeer::COUNTRY_OF_BIRTH_ID)) {
            $modifiedColumns[':p' . $index++]  = '`country_of_birth_id`';
        }
        if ($this->isColumnModified(PersonPeer::PLACE_OF_BIRTH)) {
            $modifiedColumns[':p' . $index++]  = '`place_of_birth`';
        }

        $sql = sprintf(
            'INSERT INTO `persons` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`first_name`':
                        $stmt->bindValue($identifier, $this->first_name, PDO::PARAM_STR);
                        break;
                    case '`last_name`':
                        $stmt->bindValue($identifier, $this->last_name, PDO::PARAM_STR);
                        break;
                    case '`birthday`':
                        $stmt->bindValue($identifier, $this->birthday, PDO::PARAM_STR);
                        break;
                    case '`day_of_death`':
                        $stmt->bindValue($identifier, $this->day_of_death, PDO::PARAM_STR);
                        break;
                    case '`denomination_id`':
                        $stmt->bindValue($identifier, $this->denomination_id, PDO::PARAM_INT);
                        break;
                    case '`professional_category_id`':
                        $stmt->bindValue($identifier, $this->professional_category_id, PDO::PARAM_INT);
                        break;
                    case '`profession`':
                        $stmt->bindValue($identifier, $this->profession, PDO::PARAM_STR);
                        break;
                    case '`country_of_birth_id`':
                        $stmt->bindValue($identifier, $this->country_of_birth_id, PDO::PARAM_INT);
                        break;
                    case '`place_of_birth`':
                        $stmt->bindValue($identifier, $this->place_of_birth, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aDenomination !== null) {
                if (!$this->aDenomination->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDenomination->getValidationFailures());
                }
            }

            if ($this->aProfessionalCategory !== null) {
                if (!$this->aProfessionalCategory->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aProfessionalCategory->getValidationFailures());
                }
            }

            if ($this->aCountry !== null) {
                if (!$this->aCountry->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCountry->getValidationFailures());
                }
            }


            if (($retval = PersonPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collMigrations !== null) {
                    foreach ($this->collMigrations as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = PersonPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getFirstName();
                break;
            case 2:
                return $this->getLastName();
                break;
            case 3:
                return $this->getBirthday();
                break;
            case 4:
                return $this->getDayOfDeath();
                break;
            case 5:
                return $this->getDenominationId();
                break;
            case 6:
                return $this->getProfessionalCategoryId();
                break;
            case 7:
                return $this->getProfession();
                break;
            case 8:
                return $this->getCountryOfBirthId();
                break;
            case 9:
                return $this->getPlaceOfBirth();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Person'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Person'][$this->getPrimaryKey()] = true;
        $keys = PersonPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getFirstName(),
            $keys[2] => $this->getLastName(),
            $keys[3] => $this->getBirthday(),
            $keys[4] => $this->getDayOfDeath(),
            $keys[5] => $this->getDenominationId(),
            $keys[6] => $this->getProfessionalCategoryId(),
            $keys[7] => $this->getProfession(),
            $keys[8] => $this->getCountryOfBirthId(),
            $keys[9] => $this->getPlaceOfBirth(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aDenomination) {
                $result['Denomination'] = $this->aDenomination->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aProfessionalCategory) {
                $result['ProfessionalCategory'] = $this->aProfessionalCategory->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCountry) {
                $result['Country'] = $this->aCountry->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collMigrations) {
                $result['Migrations'] = $this->collMigrations->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = PersonPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setFirstName($value);
                break;
            case 2:
                $this->setLastName($value);
                break;
            case 3:
                $this->setBirthday($value);
                break;
            case 4:
                $this->setDayOfDeath($value);
                break;
            case 5:
                $this->setDenominationId($value);
                break;
            case 6:
                $this->setProfessionalCategoryId($value);
                break;
            case 7:
                $this->setProfession($value);
                break;
            case 8:
                $this->setCountryOfBirthId($value);
                break;
            case 9:
                $this->setPlaceOfBirth($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = PersonPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setFirstName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setLastName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setBirthday($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDayOfDeath($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setDenominationId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setProfessionalCategoryId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setProfession($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setCountryOfBirthId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setPlaceOfBirth($arr[$keys[9]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PersonPeer::DATABASE_NAME);

        if ($this->isColumnModified(PersonPeer::ID)) $criteria->add(PersonPeer::ID, $this->id);
        if ($this->isColumnModified(PersonPeer::FIRST_NAME)) $criteria->add(PersonPeer::FIRST_NAME, $this->first_name);
        if ($this->isColumnModified(PersonPeer::LAST_NAME)) $criteria->add(PersonPeer::LAST_NAME, $this->last_name);
        if ($this->isColumnModified(PersonPeer::BIRTHDAY)) $criteria->add(PersonPeer::BIRTHDAY, $this->birthday);
        if ($this->isColumnModified(PersonPeer::DAY_OF_DEATH)) $criteria->add(PersonPeer::DAY_OF_DEATH, $this->day_of_death);
        if ($this->isColumnModified(PersonPeer::DENOMINATION_ID)) $criteria->add(PersonPeer::DENOMINATION_ID, $this->denomination_id);
        if ($this->isColumnModified(PersonPeer::PROFESSIONAL_CATEGORY_ID)) $criteria->add(PersonPeer::PROFESSIONAL_CATEGORY_ID, $this->professional_category_id);
        if ($this->isColumnModified(PersonPeer::PROFESSION)) $criteria->add(PersonPeer::PROFESSION, $this->profession);
        if ($this->isColumnModified(PersonPeer::COUNTRY_OF_BIRTH_ID)) $criteria->add(PersonPeer::COUNTRY_OF_BIRTH_ID, $this->country_of_birth_id);
        if ($this->isColumnModified(PersonPeer::PLACE_OF_BIRTH)) $criteria->add(PersonPeer::PLACE_OF_BIRTH, $this->place_of_birth);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(PersonPeer::DATABASE_NAME);
        $criteria->add(PersonPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Person (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setBirthday($this->getBirthday());
        $copyObj->setDayOfDeath($this->getDayOfDeath());
        $copyObj->setDenominationId($this->getDenominationId());
        $copyObj->setProfessionalCategoryId($this->getProfessionalCategoryId());
        $copyObj->setProfession($this->getProfession());
        $copyObj->setCountryOfBirthId($this->getCountryOfBirthId());
        $copyObj->setPlaceOfBirth($this->getPlaceOfBirth());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getMigrations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMigration($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Person Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return PersonPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PersonPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Denomination object.
     *
     * @param                  Denomination $v
     * @return Person The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDenomination(Denomination $v = null)
    {
        if ($v === null) {
            $this->setDenominationId(NULL);
        } else {
            $this->setDenominationId($v->getId());
        }

        $this->aDenomination = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Denomination object, it will not be re-added.
        if ($v !== null) {
            $v->addPerson($this);
        }


        return $this;
    }


    /**
     * Get the associated Denomination object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Denomination The associated Denomination object.
     * @throws PropelException
     */
    public function getDenomination(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDenomination === null && ($this->denomination_id !== null) && $doQuery) {
            $this->aDenomination = DenominationQuery::create()->findPk($this->denomination_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDenomination->addPersons($this);
             */
        }

        return $this->aDenomination;
    }

    /**
     * Declares an association between this object and a ProfessionalCategory object.
     *
     * @param                  ProfessionalCategory $v
     * @return Person The current object (for fluent API support)
     * @throws PropelException
     */
    public function setProfessionalCategory(ProfessionalCategory $v = null)
    {
        if ($v === null) {
            $this->setProfessionalCategoryId(NULL);
        } else {
            $this->setProfessionalCategoryId($v->getId());
        }

        $this->aProfessionalCategory = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ProfessionalCategory object, it will not be re-added.
        if ($v !== null) {
            $v->addPerson($this);
        }


        return $this;
    }


    /**
     * Get the associated ProfessionalCategory object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return ProfessionalCategory The associated ProfessionalCategory object.
     * @throws PropelException
     */
    public function getProfessionalCategory(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aProfessionalCategory === null && ($this->professional_category_id !== null) && $doQuery) {
            $this->aProfessionalCategory = ProfessionalCategoryQuery::create()->findPk($this->professional_category_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aProfessionalCategory->addPersons($this);
             */
        }

        return $this->aProfessionalCategory;
    }

    /**
     * Declares an association between this object and a Country object.
     *
     * @param                  Country $v
     * @return Person The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCountry(Country $v = null)
    {
        if ($v === null) {
            $this->setCountryOfBirthId(NULL);
        } else {
            $this->setCountryOfBirthId($v->getId());
        }

        $this->aCountry = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Country object, it will not be re-added.
        if ($v !== null) {
            $v->addPerson($this);
        }


        return $this;
    }


    /**
     * Get the associated Country object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Country The associated Country object.
     * @throws PropelException
     */
    public function getCountry(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCountry === null && ($this->country_of_birth_id !== null) && $doQuery) {
            $this->aCountry = CountryQuery::create()->findPk($this->country_of_birth_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCountry->addPersons($this);
             */
        }

        return $this->aCountry;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Migration' == $relationName) {
            $this->initMigrations();
        }
    }

    /**
     * Clears out the collMigrations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Person The current object (for fluent API support)
     * @see        addMigrations()
     */
    public function clearMigrations()
    {
        $this->collMigrations = null; // important to set this to null since that means it is uninitialized
        $this->collMigrationsPartial = null;

        return $this;
    }

    /**
     * reset is the collMigrations collection loaded partially
     *
     * @return void
     */
    public function resetPartialMigrations($v = true)
    {
        $this->collMigrationsPartial = $v;
    }

    /**
     * Initializes the collMigrations collection.
     *
     * By default this just sets the collMigrations collection to an empty array (like clearcollMigrations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMigrations($overrideExisting = true)
    {
        if (null !== $this->collMigrations && !$overrideExisting) {
            return;
        }
        $this->collMigrations = new PropelObjectCollection();
        $this->collMigrations->setModel('Migration');
    }

    /**
     * Gets an array of Migration objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Person is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Migration[] List of Migration objects
     * @throws PropelException
     */
    public function getMigrations($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMigrationsPartial && !$this->isNew();
        if (null === $this->collMigrations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMigrations) {
                // return empty collection
                $this->initMigrations();
            } else {
                $collMigrations = MigrationQuery::create(null, $criteria)
                    ->filterByPerson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMigrationsPartial && count($collMigrations)) {
                      $this->initMigrations(false);

                      foreach ($collMigrations as $obj) {
                        if (false == $this->collMigrations->contains($obj)) {
                          $this->collMigrations->append($obj);
                        }
                      }

                      $this->collMigrationsPartial = true;
                    }

                    $collMigrations->getInternalIterator()->rewind();

                    return $collMigrations;
                }

                if ($partial && $this->collMigrations) {
                    foreach ($this->collMigrations as $obj) {
                        if ($obj->isNew()) {
                            $collMigrations[] = $obj;
                        }
                    }
                }

                $this->collMigrations = $collMigrations;
                $this->collMigrationsPartial = false;
            }
        }

        return $this->collMigrations;
    }

    /**
     * Sets a collection of Migration objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $migrations A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Person The current object (for fluent API support)
     */
    public function setMigrations(PropelCollection $migrations, PropelPDO $con = null)
    {
        $migrationsToDelete = $this->getMigrations(new Criteria(), $con)->diff($migrations);


        $this->migrationsScheduledForDeletion = $migrationsToDelete;

        foreach ($migrationsToDelete as $migrationRemoved) {
            $migrationRemoved->setPerson(null);
        }

        $this->collMigrations = null;
        foreach ($migrations as $migration) {
            $this->addMigration($migration);
        }

        $this->collMigrations = $migrations;
        $this->collMigrationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Migration objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Migration objects.
     * @throws PropelException
     */
    public function countMigrations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMigrationsPartial && !$this->isNew();
        if (null === $this->collMigrations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMigrations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMigrations());
            }
            $query = MigrationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPerson($this)
                ->count($con);
        }

        return count($this->collMigrations);
    }

    /**
     * Method called to associate a Migration object to this object
     * through the Migration foreign key attribute.
     *
     * @param    Migration $l Migration
     * @return Person The current object (for fluent API support)
     */
    public function addMigration(Migration $l)
    {
        if ($this->collMigrations === null) {
            $this->initMigrations();
            $this->collMigrationsPartial = true;
        }

        if (!in_array($l, $this->collMigrations->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMigration($l);

            if ($this->migrationsScheduledForDeletion and $this->migrationsScheduledForDeletion->contains($l)) {
                $this->migrationsScheduledForDeletion->remove($this->migrationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Migration $migration The migration object to add.
     */
    protected function doAddMigration($migration)
    {
        $this->collMigrations[]= $migration;
        $migration->setPerson($this);
    }

    /**
     * @param	Migration $migration The migration object to remove.
     * @return Person The current object (for fluent API support)
     */
    public function removeMigration($migration)
    {
        if ($this->getMigrations()->contains($migration)) {
            $this->collMigrations->remove($this->collMigrations->search($migration));
            if (null === $this->migrationsScheduledForDeletion) {
                $this->migrationsScheduledForDeletion = clone $this->collMigrations;
                $this->migrationsScheduledForDeletion->clear();
            }
            $this->migrationsScheduledForDeletion[]= clone $migration;
            $migration->setPerson(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Person is new, it will return
     * an empty collection; or if this Person has previously
     * been saved, it will retrieve related Migrations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Person.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Migration[] List of Migration objects
     */
    public function getMigrationsJoinCountry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MigrationQuery::create(null, $criteria);
        $query->joinWith('Country', $join_behavior);

        return $this->getMigrations($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->first_name = null;
        $this->last_name = null;
        $this->birthday = null;
        $this->day_of_death = null;
        $this->denomination_id = null;
        $this->professional_category_id = null;
        $this->profession = null;
        $this->country_of_birth_id = null;
        $this->place_of_birth = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collMigrations) {
                foreach ($this->collMigrations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aDenomination instanceof Persistent) {
              $this->aDenomination->clearAllReferences($deep);
            }
            if ($this->aProfessionalCategory instanceof Persistent) {
              $this->aProfessionalCategory->clearAllReferences($deep);
            }
            if ($this->aCountry instanceof Persistent) {
              $this->aCountry->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collMigrations instanceof PropelCollection) {
            $this->collMigrations->clearIterator();
        }
        $this->collMigrations = null;
        $this->aDenomination = null;
        $this->aProfessionalCategory = null;
        $this->aCountry = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PersonPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
