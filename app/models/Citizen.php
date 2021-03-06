<?php

namespace Models;

use Models\Base;

class Citizen extends Base
{
  protected $table = 'citizens';

  protected $fillable = array('aadhaar_no', 'username', 'name', 'dob', 'email', 'phone', 'passwd', 'role', 'org_name', 'created_at', 'updated_at');


  protected $casts = array(
    'id' => 'integer',
    'aadhaar_no' => 'string',
    'username' => 'string',
    'name' => 'string',
    'dob' => 'string',
    'email' => 'string',
    'phone' => 'string',
    'passwd' => 'string',
    'role'  => 'string',
    'org_name' => 'string',
    self::CREATED_AT => 'integer',
    self::UPDATED_AT => 'integer'
    );

  public static function findByUsername($username)
  {
    $instance = new static;

    return $instance->findWhere(array('username' => $username));
  }

  public static function findByCitizenId($forwardedCIRId)
  {

    $query = "SELECT username FROM `citizens`
              WHERE `id` =
              ( SELECT `c_id_rcvr` FROM `report_police_member_map`
                WHERE `cf_id` = {$forwardedCIRId} )";

    $name = '';

    if($statement = self::$db->prepare($query))
    {
      $statement->execute();
      $statement->bind_result($name);
      $statement->store_result();
      $statement->fetch();
      $statement->close();

      return $name;
    }
  }

  public function getReportIds()
  {
    $id = $this->getKey();

    $query = "SELECT `report_id` FROM `forwarded_cir`
              WHERE `receiver_id` = {$id}
              AND `status` NOT IN ('accepted', 'rejected')";

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

}
