<?php

namespace Models;

use Models\Base;

class Report extends Base
{
  protected $table = 'reports';

  protected $fillable = array('aadhaar_no', 'status');

  protected $casts = array(
    'id' => 'integer',
    'aadhaar_no' => 'string',
    'status' => 'string',
    self::CREATED_AT => 'integer',
    self::UPDATED_AT => 'integer'
    );

  public static function getReportIdsFor($aadhaar_no)
  {
    $query = "SELECT `id` FROM `reports` WHERE `aadhaar_no` = {$aadhaar_no}";

    $report_id = NULL;

    $report_ids = array();

    if($statement = self::$db->prepare($query))
    {
      $statement->execute();
      $statement->bind_result($report_id);
      $statement->store_result();

      while($statement->fetch())
      {
        $report_ids[] = $report_id;
      }

      $statement->close();

      return $report_ids;
    }
  }

  public static function countWithStatusFor($status, $member)
  {
    $query = "SELECT count(*) FROM `reports`
              WHERE `id` IN
              ( SELECT `report_id` FROM `report_police_member_map`
                WHERE `member_id` = {$member->id})
              AND `status` = '{$status}'";

    $report_count = 0;

    if($statement = self::$db->prepare($query))
    {
      $statement->execute();
      $statement->bind_result($report_count);
      $statement->store_result();
      $statement->fetch();
      $statement->close();

      return $report_count;
    }

  }

  public static function findWithStatusFor($status, $member_id)
  {
    $query = "SELECT `id` FROM `reports`
              WHERE `id` IN
              ( SELECT `report_id` FROM `report_police_member_map`
                WHERE `member_id` = {$member_id})
              AND `status` = '{$status}'";

    $report_id = NULL;

    $report_ids = array();

    if($statement = self::$db->prepare($query))
    {
      $statement->execute();
      $statement->bind_result($report_id);
      $statement->store_result();

      while($statement->fetch())
      {
        $report_ids[] = $report_id;
      }

      $statement->close();

      return $report_ids;
    }
  }

  public static function findRequestsForStation($station_id)
  {
    $query = "SELECT `id` FROM `reports`
              WHERE `station_id` = {$station_id}
              AND `id` NOT IN
              (SELECT `report_id` FROM `report_police_member_map`
                WHERE `member_id` IN
                (SELECT `member_id` FROM `police_station_and_member_map`
                  WHERE `station_id` = {$station_id}))";

    $report_id = NULL;

    $report_ids = array();

    if($statement = self::$db->prepare($query))
    {
      $statement->execute();
      $statement->bind_result($report_id);
      $statement->store_result();

      while($statement->fetch())
      {
        $report_ids[] = $report_id;
      }

      $statement->close();

      return $report_ids;
    }
  }

  public static function findReportsAwaitingReviewForStation($station_id)
  {
    $query = "SELECT `id` FROM `reports`
              WHERE `station_id` = {$station_id}
              AND `status` = 'in_review'";

    $report_id = NULL;

    $report_ids = array();

    if($statement = self::$db->prepare($query))
    {
      $statement->execute();
      $statement->bind_result($report_id);
      $statement->store_result();

      while($statement->fetch())
      {
        $report_ids[] = $report_id;
      }

      $statement->close();

      return $report_ids;
    }
  }

  public function markVerifiedBy($member_id)
  {
    $id = $this->getKey();
    $timestamp = time();

    $data = array(
              "id" => $id,
              "member_id" => $member_id,
              "created_at" => $timestamp,
              "updated_at" => $timestamp
            );

    $query = "INSERT INTO `report_verification_map`
              (`report_id`, `verified_by`, `created_at`, `updated_at`)
              VALUES (?, ?, ?, ?)";

    $statement = self::$db->prepare($query);

    $statement->bind_param("iiii", $id, $member_id, $timestamp, $timestamp);

    $statement->execute();

    return $statement->affected_rows === 1;
  }

}
