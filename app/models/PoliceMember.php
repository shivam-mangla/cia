<?php

namespace Models;

use Models\Base;

class PoliceMember extends Base
{
  protected $table = 'police_members';

  protected $fillable = array('username', 'passwd', 'first_name', 'last_name', 'role');

  protected $casts = array(
    'id' => 'integer',
    'username' => 'string',
    'passwd' => 'string',
    'first_name' => 'string',
    'last_name' => 'string',
    'role' => 'string',
    self::CREATED_AT => 'integer',
    self::UPDATED_AT => 'integer'
    );

  public static function findByUsername($username)
  {
    $instance = new static;

    return $instance->findWhere(array('username' => $username));
  }

  public function getStationId()
  {
    $id = $this->getKey();

    $query = "SELECT `station_id` FROM `police_station_and_member_map`
              WHERE `member_id` = {$id}";

    $station_id = NULL;

    if($statement = self::$db->prepare($query))
    {
      $statement->execute();
      $statement->bind_result($station_id);
      $statement->store_result();
      $statement->fetch();
      $statement->close();

      return $station_id;
    }
  }

}
