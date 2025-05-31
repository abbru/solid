<?php

class Click
{
  private $db;

  public function __construct(DatabaseConnectionInterface $db)
  {
    $this->db = $db;
  }

  public function findClick(string $idclick)
  {
    // busca el click
  }

  public function updateClickStatus(Click $click)
  {
    //update status click
  }
}
