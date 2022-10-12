<?php

namespace Models;

class User
{
  private $attributes = array();

  public function __construct()
  {
  }

  public function __set($name, $value)
  {
    $this->attributes[$name] = $value;
  }

  public function &__get($key)
  {
    $null = null;
    if (isset($this->attributes[$key]) && array_key_exists($key, $this->attributes)) {
      return $this->attributes[$key];
    } else {
      return $null;
    }
  }
}
