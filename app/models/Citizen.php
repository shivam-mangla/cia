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

  public static function findByUsername($username)
  {
    $instance = new static;

    return $instance->findWhere(array('username' => $username));
  }

}
