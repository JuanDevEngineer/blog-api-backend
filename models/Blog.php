<?php

namespace Models;

class Blog
{
  private $attributes = array();

  public function __construct()
  {
  }

  public function __set($key, $value)
  {
    $this->attributes[$key] = $value;
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
