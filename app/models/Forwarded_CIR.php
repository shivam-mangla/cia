<?php

namespace Models;

use Models\Base;

// Crime investigation reports sent by employees to their employers for checking
class Forwarded_CIR extends Base
{
  protected $table = 'forwarded_CIR';

  protected $fillable = array('cf_id', 'cir_id', 'status', 'sent_at', 'rcvd_at', 'seen_at');

  protected $casts = array(
    'cf_id' => 'integer',
    'cir_id' => 'integer',
    'status' => 'string',
    self::SENT_AT => 'integer',
    self::RCVD_AT => 'integer'
    self::SEEN_AT => 'integer'
    );

  public static function findByCIRId($cir_id)
  {
    $instance = new static;

    return $instance->findWhere(array('cir_id' => $cir_id));
  }

}
