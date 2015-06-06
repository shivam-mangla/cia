<?php

namespace Models;

use Models\Base;

// Crime investigation reports sent by employees to their employers for checking
class Forwarded_CIR extends Base
{
  protected $table = 'forwarded_cir';

  protected $fillable = array('cf_id', 'cir_id', 'status', 'sent_at', 'rcvd_at', 'seen_at');

  protected $casts = array(
    'cf_id' => 'integer',
    'cir_id' => 'integer',
    'status' => 'string',
    self::SENT_AT => 'integer',
    self::RCVD_AT => 'integer',
    self::SEEN_AT => 'integer'
    );

  public static function getStatuses($cir_id)
  {
    $instance = new static;

    return $instance->findWhere(array('cir_id' => $cir_id));

    $query = "SELECT `cf_id`, `status`, `rcvd_at`, `seen_at`, `report_id` 
              FROM `forwarded_cir`
              WHERE `cir_id` = {$cir_id}";

    // TODO: Get names of people by using this cir_id to get corresponding
    //       c_id of receiver and show their names

    SELECT `username` FROM  

    $forwarded_cir_statuses = array();

    if($statement = self::$db->prepare($query))
    {
      $statement->execute();
      $statement->bind_result($forwarded_cir_statuses);
      $statement->store_result();
      $statement->fetch();
      $statement->close();

      return $forwarded_cir_statuses;
    }
  }

}
