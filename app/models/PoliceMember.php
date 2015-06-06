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

}
