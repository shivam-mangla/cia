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

  public static function countOpenFor($member)
  {
    $query = "SELECT count(*) FROM `reports`
              WHERE `id` IN
              ( SELECT `report_id` FROM `report_police_member_map`
                WHERE `member_id` = {$member->id})
              AND `status` = 'open'";

    $open_report_count = 0;

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

}
