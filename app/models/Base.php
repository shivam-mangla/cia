<?php

namespace Models;

class Base
{
  /**
   * Database connection instance
   */
  protected static $db;

  /**
   * The primary key of the model
   *
   * @var string
   */
  protected $primary_key = 'id';

  /**
   * The table associated with the model
   *
   * @var string
   */
  protected $table;

  /**
   * Indicates if the model should be timestamped
   *
   * @var bool
   */
  protected $timestamps = true;

  /**
   * Indicates if the ID is auto-incrementing
   *
   * @var bool
   */
  protected $incrementing = true;

  /**
   * Attributes of model which can be mass assigned
   *
   * @var array
   */
  protected $fillable = array();

  /**
   * The model's attributes
   *
   * @var array
   */
  protected $attributes = array();

  /**
   * The model attribute's original state
   *
   * @var array
   */
  protected $original = array();

  /**
   * The attributes that should be mutated to dates
   *
   * @var array
   */
  protected $dates = array();

  /**
   * The attributes that should be casted to native types
   */
  protected $casts = array();

  /**
   * Indicates if the model exists
   *
   * @var bool
   */
  public $exists = false;

  /**
   * The name of the "created at" column
   *
   * @var string
   */
  const CREATED_AT = 'created_at';

  /**
   * The name of the "updated at" column.
   *
   * @var string
   */
  const UPDATED_AT = 'updated_at';

  public function __construct(array $attributes = array())
  {
    self::$db = self::getDB();

    $this->fill($attributes);
  }

  public static function getDB()
  {
    global $APP_CONFIG;

    if( ! self::$db)
    {
      self::$db = new DatabaseConnection(
        $APP_CONFIG["db_host"],
        $APP_CONFIG["db_user"],
        $APP_CONFIG["db_pass"],
        $APP_CONFIG["db_name"],
        true
      );
    }

    return self::$db;
  }

  /**
   * Find a model by its primary key.
   *
   * @param mixed $id
   * @param array $columns
   * @return static|null
   */
  public static function find($id, $columns = array('*'))
  {
    $instance = new static;

    if (is_array($id) && empty($id)) return null;

    return $instance->findById($id, $columns);
  }

  public function findById($id, $columns = array('*'))
  {
    return $this->findWhere(array($this->getKeyName() => $id), $columns);
  }

  /**
   * Find a model by the given where clause
   * Note: The where clause must result in a single result
   *
   * @param array $where
   * @param array $columns
   *
   * @return $this|NULL
   */
  protected function findWhere($where, $columns = array('*'))
  {
    $columns = implode(', ', $columns);
    // store the columns fetched using the select statement
    $params = array();
    // store the data fetched using the select statement
    $row = array();
    $where_fields = array();

    foreach ($where as $attribute => $value) {
      $where_fields[] = "{$attribute} = ?";
    }

    $where_clause = implode(' AND ', $where_fields);

    $types = array_reduce(array_keys($where),
               function($carry, $attribute)
               {
                 $carry .= $this->getAttributeType($attribute);
                 return $carry;
               }
             );

    $bind_params = array();

    foreach($where as $key => $value)
    {
      $bind_params[] = &$where[$key];
    }

    array_unshift($bind_params, $types);

    $query = "SELECT {$columns} FROM {$this->getTable()} WHERE $where_clause";

    if($statement = self::$db->prepare($query))
    {
      call_user_func_array(array($statement, 'bind_param'), $bind_params);
      $statement->execute();
      $statement->store_result();

      // no row found
      if($statement->num_rows === 0)
      {
        return NULL;
      }

      $meta = $statement->result_metadata();

      while($field = $meta->fetch_field())
      {
        $params[] = &$row[$field->name];
      }

      call_user_func_array(array($statement, 'bind_result'), $params);

      $statement->fetch();
      $statement->close();

      foreach($row as $attribute => $value)
      {
        $this->setAttribute($attribute, $value);
      }

      $this->syncOriginal();

      // Now we will go ahead and set the exists property to true
      // this ensures that the model gets updated
      $this->exists = true;
    }

    return $this;
  }

  /**
   * Fill the model with an array of attributes
   *
   * @param array $attributes
   * @return $this
   */
  public function fill(array $attributes)
  {
    foreach($this->fillableFromArray($attributes) as $key => $value)
    {
      $this->setAttribute($key, $value);
    }

    return $this;
  }

  /**
   * Returns the fillable attributes of the model
   *
   * @param array $attributes
   * @return array
   */
  public function fillableFromArray(array $attributes)
  {
    if(count($attributes) > 0)
    {
      return array_intersect_key($attributes, array_flip($this->fillable));
    }

    return $attributes;
  }

  /**
  * Save the model to the database
  *
  * @param none
  * @return bool
  */
  public function save()
  {
    if($this->exists)
    {
      $saved = $this->performUpdate();
    }
    else
    {
      $saved = $this->performInsert();
    }

    if($saved) $this->finishSave();

    return $saved;
  }

  /**
   * Finish processing on a successful save operation
   *
   * @param none
   * @return void
   */
  protected function finishSave()
  {
    $this->syncOriginal();
  }

  /**
   * Perform a model update operation
   *
   * @param none
   * @return bool
   */
  protected function performUpdate()
  {
    $dirty = $this->getDirty();

    if (count($dirty) > 0)
    {
      // First we need to modify the update timestamp on the model
      // Then we will just continue saving the model instances
      if ($this->timestamps)
      {
        $this->updateTimestamps();
      }

      // We will now perform the update operation
      $dirty = $this->getDirty();

      if (count($dirty) > 0)
      {
        $this->update($dirty);
      }
    }

    return true;
  }

  /**
   * Perform a model insert operation
   *
   * @return bool
   */
  protected function performInsert()
  {
    // First we'll need to touch the creation and update timestamps on this model
    // After, we will just continue saving these model instances
    if ($this->timestamps)
    {
      $this->updateTimestamps();
    }

    // If the model has an incrementing key, we insert the attributes and
    // update the final inserted ID for this table from the database
    $attributes = $this->attributes;

    if($this->incrementing)
    {
      $this->insertAndSetId($attributes);
    }

    // If the table is not incrementing we'll simply insert this attributes as they
    // are, as this attributes arrays must contain an "id" column already placed
    // there by the developer as the manually determined key for these models.
    else
    {
      $this->insert($attributes);
    }

    // We will go ahead and set the exists property to true
    // This results in future update queries rather than insert query
    $this->exists = true;

    return true;
  }

  /**
   * Perform a raw update operation
   *
   * @param array $attributes
   * @return void
   */
  protected function update(array $attributes = array())
  {
    $updates = array();
    // parameters to pass to the bind_param function call
    $params = array();
    $fields = array_keys($attributes);
    $primaryId = $this->getKeyForSaveQuery();

    // insert primary key attribute after everything else
    $fields[] = $this->getKeyName();

    foreach($attributes as $key => $value)
    {
      $params[] = &$attributes[$key];
    }

    $params[] = &$primaryId;

    foreach($attributes as $key => $value)
    {
      $updates[] = "`{$key}` = ?";
    }

    $set_updates = implode(', ', $updates);

    $query = "UPDATE {$this->getTable()} SET {$set_updates} WHERE {$this->getKeyName()} = ?";

    if($statement = self::$db->prepare($query))
    {
      $types = implode('',
                       array_map(
                          function($key)
                          {
                            return $this->getAttributeType($key);
                          },
                          $fields
                        )
                      );

      array_unshift($params, $types);

      // bind the parameters to the statement
      call_user_func_array(array($statement, 'bind_param'), $params);

      $statement->execute();
      $statement->close();
    }
  }

  /**
   * Perform a raw insert operation while auto-incrementing the ID
   *
   * @param array $attributes
   * @return void
   */
  protected function insertAndSetId(array $attributes = array())
  {
    $columns = "`" . implode('`, `', array_keys($attributes)) . "`";
    $insert_values = implode(', ', array_fill(0, count($attributes), '?'));
    // parameters to pass to the bind_param function call
    $params = array();

    foreach($attributes as $key => $value)
    {
      $params[] = &$attributes[$key];
    }

    $query = "INSERT INTO {$this->getTable()} ({$columns}) VALUES ({$insert_values})";

    if($statement = self::$db->prepare($query))
    {
      $types = implode('',
                       array_map(
                          function($key)
                          {
                            return $this->getAttributeType($key);
                          },
                          array_keys($attributes)
                        )
                      );

      array_unshift($params, $types);

      // bind the parameters to the statement
      call_user_func_array(array($statement, 'bind_param'), $params);

      $statement->execute();

      $this->setAttribute($this->getKeyName(), $statement->insert_id);

      $statement->close();
    }
  }

  /**
   * Perform a raw insert operation
   *
   * @param array $attributes
   * @return void
   */
  protected function insert(array $attributes = array())
  {
    $columns = implode(', ', array_keys($attributes));
    $insert_values = implode(', ', array_fill(0, count($attributes), '?'));
    // parameters to pass to the bind_param function call
    $params = array();

    foreach($attributes as $key => $value)
    {
      $params[] = &$attributes[$key];
    }

    $query = "INSERT INTO {$this->getTable()} ({$columns}) VALUES ({$insert_values})";

    if($statement = self::$db->prepare($query))
    {
      $types = implode('',
                       array_map(
                          function($key)
                          {
                            return $this->getAttributeType($key);
                          },
                          array_keys($attributes)
                        )
                      );

      array_unshift($params, $types);

      // bind the parameters to the statement
      call_user_func_array(array($statement, 'bind_param'), $params);

      $statement->execute();
      $statement->close();
    }
  }

  /**
   * Delete the model from the database
   *
   * @return bool
   */
  public function delete()
  {
    if(NULL == $this->primary_key)
    {
      return false;
    }

    if($this->exists)
    {
      $deleted = $this->performDelete();

      if($deleted)
      {
        $this->exists = false;
      }

      return $deleted;
    }
  }

  /**
   * Perform a model delete operation
   *
   * @return bool
   * @throws \Exception
   */
  protected function performDelete()
  {
    $deleted = false;

    $query = "DELETE FROM {$this->getTable()} WHERE {$this->getKeyName()} = ?";

    $types = $this->getAttributeType($this->getKeyName());
    $id = $this->getKey();

    if($statement = self::$db->prepare($query))
    {
      $statement->bind_param($types, $id);
      $statement->execute();

      if($statement->affected_rows === 1)
      {
        $deleted = true;
      }
      elseif($statement->affected_rows > 1)
      {
        throw new \Exception("Multiple rows deleted, given key is not primary!");
      }

      $statement->close();
    }

    return $deleted;
  }

  /**
   * Get the primary key value for a save query
   *
   * @return mixed
   */
  protected function getKeyForSaveQuery()
  {
    if(isset($this->original[$this->getKeyName()]))
    {
      return $this->original[$this->getKeyName()];
    }

    return $this->getAttribute($this->getKeyName());
  }

  /**
   * Get the value of model's primary key
   *
   * @return mixed
   */
  protected function getKey()
  {
    return $this->getAttribute($this->getKeyName());
  }

  /**
   * Update the creation and update timestamps
   *
   * @return void
   */
  protected function updateTimestamps()
  {
    $time = $this->freshTimestamp();

    if ( ! $this->isDirty(static::UPDATED_AT))
    {
      $this->setUpdatedAt($time);
    }

    if ( ! $this->exists && ! $this->isDirty(static::CREATED_AT))
    {
      $this->setCreatedAt($time);
    }
  }

  /**
   * Set the value of the "created at" attribute
   *
   * @param  mixed  $value
   * @return void
   */
  public function setCreatedAt($value)
  {
    $this->{static::CREATED_AT} = $value;
  }

  /**
   * Set the value of the "updated at" attribute
   *
   * @param  mixed  $value
   * @return void
   */
  public function setUpdatedAt($value)
  {
    $this->{static::UPDATED_AT} = $value;
  }

  /**
   * Get the name of the "created at" column
   *
   * @return string
   */
  public function getCreatedAtColumn()
  {
    return static::CREATED_AT;
  }

  /**
   * Get the name of the "updated at" column
   *
   * @return string
   */
  public function getUpdatedAtColumn()
  {
    return static::UPDATED_AT;
  }

  /**
   * Get a fresh timestamp for the model
   *
   * @return DateTime
   */
  public function freshTimestamp()
  {
    return new \DateTime;
  }

  /**
   * Get the primary key for the model
   *
   * @return string
   */
  public function getKeyName()
  {
    return $this->primary_key;
  }

  /**
   * Get the table associated with the model
   *
   * @return string
   */
  public function getTable()
  {
    return $this->table;
  }

  /**
   * Determine if the given attribute may be mass assigned
   *
   * @param string $key
   * @return bool
   */
  public function isFillable($key)
  {
    return in_array($key, $this->fillable);
  }

  /**
   * Get the fillable attributes for the model
   *
   * @return array
   */
  public function getFillable()
  {
    return $this->fillable;
  }

  /**
   * Set the fillable attributes for the model
   *
   * @param  array  $fillable
   * @return $this
   */
  public function setFillable(array $fillable)
  {
    $this->fillable = $fillable;

    return $this;
  }

  /**
   * Set a given attribute on the model
   *
   * @param string $key
   * @param mixed  $value
   * @return void
   */
  public function setAttribute($key, $value)
  {
    if(in_array($key, $this->getDates()) && $value)
    {
      $value = $this->fromDateTime($value);
    }

    $this->attributes[$key] = $value;
  }

  /**
   * Get the attributes that should be converted to dates
   *
   * @return array
   */
  public function getDates()
  {
    $defaults = array(static::CREATED_AT, static::UPDATED_AT);

    return array_merge($this->dates, $defaults);
  }

  public function getDateFormat()
  {
    return 'U';
  }

  /**
   * Convert a DateTime to a storable string
   *
   * @param \DateTime|int $value
   * @return string
   */
  public function fromDateTime($value)
  {
    $format = $this->getDateFormat();

    // If the value is already a DateTime instance, we will just skip the rest of
    // these checks since they will be a waste of time, and hinder performance
    // when checking the field. We will just return the DateTime right away.
    if($value instanceof DateTime)
    {
      //
    }

    elseif(is_numeric($value))
    {
      $datetime = new \DateTime();
      $datetime->setTimestamp($value);

      $value = $datetime;
    }

    return $value->format($format);
  }

  /**
   * Sync the original attributes with the current
   *
   * @return $this
   */
  public function syncOriginal()
  {
    $this->original = $this->attributes;

    return $this;
  }

  /**
   * Determine if the model or given attribute(s) have been modified
   *
   * @param array|string|null $attributes
   * @return bool
   */
  public function isDirty($attributes = null)
  {
    $dirty = $this->getDirty();

    if(NULL === $attributes) return count($dirty) > 0;

    if( ! is_array($attributes)) $attributes = func_get_args();

    foreach($attributes as $attribute)
    {
      if(array_key_exists($attribute, $dirty)) return true;
    }

    return false;
  }

  /**
   * Get the attributes that have been changed since last sync
   *
   * @return array $dirty
   */
  public function getDirty()
  {
    $dirty = array();

    foreach($this->attributes as $key => $value)
    {
      if( ! array_key_exists($key, $this->original))
      {
        $dirty[$key] = $value;
      }
      elseif($value !== $this->original[$key])
      {
        $dirty[$key] = $value;
      }
    }

    return $dirty;
  }

  /**
   * Indicates if the model exists
   *
   * @return bool
   */
  public function exists()
  {
    return $this->exists;
  }


  /**
   * Get the type of cast for a model attribute
   *
   * @param string $key
   * @return string
   */
  protected function getCastType($key)
  {
    return trim(strtolower($this->casts[$key]));
  }

  /**
   * Get the type of an attribute
   * Note: refers to i, d, s or b for use with MySQL
   *
   * @param string $key
   * @param mixed $value
   * @return mixed
   */
  protected function getAttributeType($key)
  {
    switch ($this->getCastType($key))
    {
      case 'int':
      case 'integer':
      case 'bool':
      case 'boolean':
        return 'i';
      case 'real':
      case 'float':
      case 'double':
        return 'd';
      case 'string':
        return 's';
      case 'object':
      case 'array':
      case 'json':
        return 'b';
      default:
        return 'b';
    }
  }

  /**
   * Cast an attribute to a native PHP type
   *
   * @param string $key
   * @param mixed $value
   * @return mixed
   */
  protected function castAttribute($key, $value)
  {
    switch ($this->getCastType($key))
    {
      case 'int':
      case 'integer':
        return (int) $value;
      case 'real':
      case 'float':
      case 'double':
        return (float) $value;
      case 'string':
        return (string) $value;
      case 'bool':
      case 'boolean':
        return (bool) $value;
      case 'object':
        return json_decode($value);
      case 'array':
      case 'json':
        return json_decode($value, true);
      default:
        return $value;
    }
  }

  /**
   * Determine whether an attribute should be casted to a native type
   *
   * @param string $key
   * @return bool
   */
  protected function hasCast($key)
  {
    return array_key_exists($key, $this->casts);
  }

  /**
   * Get a plain attribute (not a relationship).
   *
   * @param string $key
   * @return mixed
   */
  protected function getAttributeValue($key)
  {
    $value = $this->getAttributeFromArray($key);

    // If the attribute exists within the cast array, we will convert it to
    // an appropriate native PHP type
    if ($this->hasCast($key))
    {
      $value = $this->castAttribute($key, $value);
    }

    return $value;
  }

  /**
   * Get an attribute from the $attributes array.
   *
   * @param string $key
   * @return mixed
   */
  protected function getAttributeFromArray($key)
  {
    if(array_key_exists($key, $this->attributes))
    {
      return $this->attributes[$key];
    }
  }

  /**
   * Get an attribute from the model
   *
   * @param string $key
   * @return mixed
   */
  public function getAttribute($key)
  {
    return $this->getAttributeValue($key);
  }

  /**
   * Dynamically retrieve attributes on the model
   *
   * @param string $key
   * @return mixed
   */
  public function __get($key)
  {
    return $this->getAttribute($key);
  }

  /**
   * Dynamically set attributes on the model
   *
   * @param  string  $key
   * @param  mixed   $value
   * @return void
   */
  public function __set($key, $value)
  {
    $this->setAttribute($key, $value);
  }
}