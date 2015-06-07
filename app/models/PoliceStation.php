<?php

namespace Models;

use Models\Base;

class PoliceStation extends Base
{
  protected $table = 'police_stations';

  protected $fillable = array('stationname', 'name', 'address', 'type');

  protected $casts = array(
    'id' => 'integer',
    'stationname' => 'string',
    'name' => 'string',
    'address' => 'string',
    'type' => 'string',
    self::CREATED_AT => 'integer',
    self::UPDATED_AT => 'integer'
    );

  public static function findByStationname($stationname)
  {
    $instance = new static;

    return $instance->findWhere(array('stationname' => $stationname));
  }

  public function newRequestsCount()
  {
    $id = $this->getKey();

    $query = "SELECT count(*) FROM `reports`
              WHERE `station_id` = {$id}
              AND `id` NOT IN
              (SELECT `report_id` FROM `report_police_member_map`
                WHERE `member_id` IN
                (SELECT `member_id` FROM `police_station_and_member_map`
                  WHERE `station_id` = {$id}))";

    $open_report_count = NULL;

    if($statement = self::$db->prepare($query))
    {
      $statement->execute();
      $statement->bind_result($open_report_count);
      $statement->store_result();
      $statement->fetch();
      $statement->close();

      return $open_report_count;
    }
  }

  public function reviewRequestsCount()
  {
    $id = $this->getKey();

    $query = "SELECT count(*) FROM `reports`
              WHERE `station_id` = {$id}
              AND `status` = 'in_review'";

    $review_report_count = NULL;

    if($statement = self::$db->prepare($query))
    {
      $statement->execute();
      $statement->bind_result($review_report_count);
      $statement->store_result();
      $statement->fetch();
      $statement->close();

      return $review_report_count;
    }
  }

}
