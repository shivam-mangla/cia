<?php

namespace Models;

use Models\Base;

class Citizen extends Base
{
  protected $table = 'citizens';

  protected $fillable = array('aadhaar_no', 'username', 'passwd', 'role', 'org_name', 'created_at', 'updated_at');


  protected $casts = array(
    'id' => 'integer',
    'aadhaar_no' => 'string',
    'username' => 'string',
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

}
