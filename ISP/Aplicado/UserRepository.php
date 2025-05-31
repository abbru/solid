<?php
class UserRepository implements UserRepositoryInterface
{
  private $db;

  public function __construct(DatabaseConnectionInterface $db)
  {
    $this->db = $db;
  }

  public function save(User $user)
  {
    $this->db->connect();
    $query = "INSERT INTO users (name) VALUES ('{$user->name}')";
    $this->db->execute($query);
  }
}
