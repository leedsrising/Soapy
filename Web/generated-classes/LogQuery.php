<?php

use Base\LogQuery as BaseLogQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'log' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class LogQuery extends BaseLogQuery
{
  public static function bathrooms() {
    $query = LogQuery::create()
      ->select(array('bathroom'))
      ->groupBy('bathroom')
      ->find()
      ->toArray();

    return $query;
  }
}
