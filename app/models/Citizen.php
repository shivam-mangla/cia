<?php

namespace Models;

use Models\Base;

class Citizen extends Base
{
  protected $table = 'citizens';

  protected $fillable = array('username', 'passwd', 'role', 'org_name', 'created_at', 'updated_at');


  protected $casts = array(
    'c_id' => 'integer',
    'username' => 'string',
    'passwd' => 'string',
    'role'  => 'string', 
    'org_name' => 'string',
    self::CREATED_AT => 'integer',
    self::UPDATED_AT => 'integer'
    );

  public static function findByCitizenname($citizenName)
  {
    $instance = new static;

    return $instance->findWhere(array('username' => $citizenName));
  }

  public static function findByCitizenId($forwardedCIRId)
  {

    $query = "SELECT username FROM `citizens`
              WHERE `c_id` =
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
