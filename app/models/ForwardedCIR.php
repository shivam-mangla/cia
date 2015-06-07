<?php

namespace Models;

use Models\Base;

// Crime investigation reports sent by employees to their employers for checking
class ForwardedCIR extends Base
{
  protected $table = 'forwarded_cir';

  protected $fillable = array('report_id', 'status', 'receiver_id', 'seen_at', 'created_at', 'updated_at');

  protected $casts = array(
    'id' => 'integer',
    'report_id' => 'integer',
    'status' => 'string',
    'receiver_id' => 'integer',
    'seen_at' => 'integer',
    self::CREATED_AT => 'integer',
    self::UPDATED_AT => 'integer'
    );

  public static function getMultipleStatusFor($cir_id)
  {
    $query = "SELECT `report_id`, `id`, `receiver_id`, `status`, `seen_at`, `updated_at`
              FROM `forwarded_cir`
              WHERE `report_id` = {$cir_id}";

    // TODO: Get names of people by using this cir_id to get corresponding
    //       c_id of receiver and show their names

    $multiple_status = array();

    if($statement = self::$db->prepare($query))
    {
      $statement->execute();
      $statement->bind_result($report_id, $id, $receiver_id, $status, $seen_at, $updated_at);
      $statement->store_result();

      while($statement->fetch())
      {
        $multiple_status[] = array(
          "id" => $id,
          "report_id" => $report_id,
          "receiver_id" => $receiver_id,
          "status" => $status,
          "seen_at" => $seen_at,
          "updated_at" => $updated_at
          );
      }

      $statement->close();

      return $multiple_status;
    }
  }

}
