<?php

class UserRepository
{
  private $db;

  public function __construct()
  {
    $this->db = new MySQLConnection(); // 👈 Violación del OCP
  }

  public function save(User $user)
  {
    $this->db->connect();
    $this->db->execute("INSERT INTO users (name) VALUES ('{$user->name}')");
  }
}
