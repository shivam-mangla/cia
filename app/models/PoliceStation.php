<?php

namespace Models;

use Models\Base;

class PoliceStation extends Base
{
  protected $table = 'police_stations';

  protected $fillable = array('stationname', 'name', 'address', 'type');

  protected $casts = array(
    'id' => 'integer',
    'stationname' => 'string',
    'name' => 'string',
    'address' => 'string',
    'type' => 'string',
    self::CREATED_AT => 'integer',
    self::UPDATED_AT => 'integer'
    );

  public static function findByStationname($stationname)
  {
    $instance = new static;

    return $instance->findWhere(array('stationname' => $stationname));
  }

}
