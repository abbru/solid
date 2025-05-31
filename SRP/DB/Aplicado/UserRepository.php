<?php

class UserRepository
{
  private $db;

  public function __construct(DatabaseConnection $db)
  {
    $this->db = $db;
  }

  public function save(User $user)
  {
    $sql = "INSERT INTO users (name) VALUES ('{$user->name}')";
    $this->db->query($sql);
  }
}
