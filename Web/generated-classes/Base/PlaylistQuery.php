<?php

namespace Base;

use \Playlist as ChildPlaylist;
use \PlaylistQuery as ChildPlaylistQuery;
use \Exception;
use \PDO;
use Map\PlaylistTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playlist' table.
 *
 *
 *
 * @method     ChildPlaylistQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildPlaylistQuery groupById() Group by the id column
 *
 * @method     ChildPlaylistQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlaylistQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlaylistQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlaylistQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlaylistQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlaylistQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlaylistQuery leftJoinListener($relationAlias = null) Adds a LEFT JOIN clause to the query using the Listener relation
 * @method     ChildPlaylistQuery rightJoinListener($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Listener relation
 * @method     ChildPlaylistQuery innerJoinListener($relationAlias = null) Adds a INNER JOIN clause to the query using the Listener relation
 *
 * @method     ChildPlaylistQuery joinWithListener($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Listener relation
 *
 * @method     ChildPlaylistQuery leftJoinWithListener() Adds a LEFT JOIN clause and with to the query using the Listener relation
 * @method     ChildPlaylistQuery rightJoinWithListener() Adds a RIGHT JOIN clause and with to the query using the Listener relation
 * @method     ChildPlaylistQuery innerJoinWithListener() Adds a INNER JOIN clause and with to the query using the Listener relation
 *
 * @method     ChildPlaylistQuery leftJoinPlaylist($relationAlias = null) Adds a LEFT JOIN clause to the query using the Playlist relation
 * @method     ChildPlaylistQuery rightJoinPlaylist($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Playlist relation
 * @method     ChildPlaylistQuery innerJoinPlaylist($relationAlias = null) Adds a INNER JOIN clause to the query using the Playlist relation
 *
 * @method     ChildPlaylistQuery joinWithPlaylist($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Playlist relation
 *
 * @method     ChildPlaylistQuery leftJoinWithPlaylist() Adds a LEFT JOIN clause and with to the query using the Playlist relation
 * @method     ChildPlaylistQuery rightJoinWithPlaylist() Adds a RIGHT JOIN clause and with to the query using the Playlist relation
 * @method     ChildPlaylistQuery innerJoinWithPlaylist() Adds a INNER JOIN clause and with to the query using the Playlist relation
 *
 * @method     ChildPlaylistQuery leftJoinSpotifyPlaylist($relationAlias = null) Adds a LEFT JOIN clause to the query using the SpotifyPlaylist relation
 * @method     ChildPlaylistQuery rightJoinSpotifyPlaylist($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SpotifyPlaylist relation
 * @method     ChildPlaylistQuery innerJoinSpotifyPlaylist($relationAlias = null) Adds a INNER JOIN clause to the query using the SpotifyPlaylist relation
 *
 * @method     ChildPlaylistQuery joinWithSpotifyPlaylist($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SpotifyPlaylist relation
 *
 * @method     ChildPlaylistQuery leftJoinWithSpotifyPlaylist() Adds a LEFT JOIN clause and with to the query using the SpotifyPlaylist relation
 * @method     ChildPlaylistQuery rightJoinWithSpotifyPlaylist() Adds a RIGHT JOIN clause and with to the query using the SpotifyPlaylist relation
 * @method     ChildPlaylistQuery innerJoinWithSpotifyPlaylist() Adds a INNER JOIN clause and with to the query using the SpotifyPlaylist relation
 *
 * @method     \UserQuery|\ListensToQuery|\SpotifyPlaylistQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlaylist findOne(ConnectionInterface $con = null) Return the first ChildPlaylist matching the query
 * @method     ChildPlaylist findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlaylist matching the query, or a new ChildPlaylist object populated from the query conditions when no match is found
 *
 * @method     ChildPlaylist findOneById(int $id) Return the first ChildPlaylist filtered by the id column *

 * @method     ChildPlaylist requirePk($key, ConnectionInterface $con = null) Return the ChildPlaylist by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylist requireOne(ConnectionInterface $con = null) Return the first ChildPlaylist matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlaylist requireOneById(int $id) Return the first ChildPlaylist filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlaylist[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlaylist objects based on current ModelCriteria
 * @method     ChildPlaylist[]|ObjectCollection findById(int $id) Return ChildPlaylist objects filtered by the id column
 * @method     ChildPlaylist[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlaylistQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PlaylistQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'soapy', $modelName = '\\Playlist', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlaylistQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlaylistQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlaylistQuery) {
            return $criteria;
        }
        $query = new ChildPlaylistQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
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
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPlaylist|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PlaylistTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlaylistTableMap::DATABASE_NAME);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlaylist A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id FROM playlist WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPlaylist $obj */
            $obj = new ChildPlaylist();
            $obj->hydrate($row);
            PlaylistTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildPlaylist|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildPlaylistQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlaylistTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlaylistQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlaylistTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlaylistQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PlaylistTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PlaylistTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistQuery The current query, for fluid interface
     */
    public function filterByListener($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(PlaylistTableMap::COL_ID, $user->getPlaylistId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            return $this
                ->useListenerQuery()
                ->filterByPrimaryKeys($user->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByListener() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Listener relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlaylistQuery The current query, for fluid interface
     */
    public function joinListener($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Listener');

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
            $this->addJoinObject($join, 'Listener');
        }

        return $this;
    }

    /**
     * Use the Listener relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useListenerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinListener($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Listener', '\UserQuery');
    }

    /**
     * Filter the query by a related \ListensTo object
     *
     * @param \ListensTo|ObjectCollection $listensTo the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistQuery The current query, for fluid interface
     */
    public function filterByPlaylist($listensTo, $comparison = null)
    {
        if ($listensTo instanceof \ListensTo) {
            return $this
                ->addUsingAlias(PlaylistTableMap::COL_ID, $listensTo->getPlaylistId(), $comparison);
        } elseif ($listensTo instanceof ObjectCollection) {
            return $this
                ->usePlaylistQuery()
                ->filterByPrimaryKeys($listensTo->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlaylist() only accepts arguments of type \ListensTo or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Playlist relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlaylistQuery The current query, for fluid interface
     */
    public function joinPlaylist($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Playlist');

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
            $this->addJoinObject($join, 'Playlist');
        }

        return $this;
    }

    /**
     * Use the Playlist relation ListensTo object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ListensToQuery A secondary query class using the current class as primary query
     */
    public function usePlaylistQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlaylist($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Playlist', '\ListensToQuery');
    }

    /**
     * Filter the query by a related \SpotifyPlaylist object
     *
     * @param \SpotifyPlaylist|ObjectCollection $spotifyPlaylist the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistQuery The current query, for fluid interface
     */
    public function filterBySpotifyPlaylist($spotifyPlaylist, $comparison = null)
    {
        if ($spotifyPlaylist instanceof \SpotifyPlaylist) {
            return $this
                ->addUsingAlias(PlaylistTableMap::COL_ID, $spotifyPlaylist->getId(), $comparison);
        } elseif ($spotifyPlaylist instanceof ObjectCollection) {
            return $this
                ->useSpotifyPlaylistQuery()
                ->filterByPrimaryKeys($spotifyPlaylist->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySpotifyPlaylist() only accepts arguments of type \SpotifyPlaylist or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SpotifyPlaylist relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlaylistQuery The current query, for fluid interface
     */
    public function joinSpotifyPlaylist($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SpotifyPlaylist');

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
            $this->addJoinObject($join, 'SpotifyPlaylist');
        }

        return $this;
    }

    /**
     * Use the SpotifyPlaylist relation SpotifyPlaylist object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpotifyPlaylistQuery A secondary query class using the current class as primary query
     */
    public function useSpotifyPlaylistQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSpotifyPlaylist($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SpotifyPlaylist', '\SpotifyPlaylistQuery');
    }

    /**
     * Filter the query by a related User object
     * using the listensto table as cross reference
     *
     * @param User $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePlaylistQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlaylist $playlist Object to remove from the list of results
     *
     * @return $this|ChildPlaylistQuery The current query, for fluid interface
     */
    public function prune($playlist = null)
    {
        if ($playlist) {
            $this->addUsingAlias(PlaylistTableMap::COL_ID, $playlist->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playlist table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlaylistTableMap::clearInstancePool();
            PlaylistTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlaylistTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlaylistTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlaylistTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlaylistQuery
