<?php

class ClickRepository implements ClickRepositoryInterface
{
  private $db;

  public function __construct(DatabaseConnectionInterface $db)
  {
    $this->db = $db;
  }

  public function updateClickStatus(Click $click)
  {
    $this->db->connect();
    $query = "UPDATE click set (idclick='{$click->idclick}') WHERE idclick='{$click->idclick}'";
    $this->db->execute($query);
  }
}
