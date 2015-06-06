<?php

namespace Models;

class DatabaseConnection extends \mysqli
{

  /**
   * Create connection with database
   *
   * @param string $host
   * @param string $username
   * @param string $password
   * @param string $database
   * @param bool $persistent
   */
  public function __contruct($host, $username, $password, $database, $persistent = true)
  {
    if($persistent)
    {
      $host = "p:" . $host;
    }

    try
    {
      parent::__contruct($host, $username, $password, $database);
    }
    catch(mysqli_sql_exception $e)
    {
      @error_log('Database Connection failed: ' . $e->errorMessage());
    }
  }
}