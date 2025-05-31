<?php

class UserManager
{
  private $db;

  public function __construct()
  {
    $this->db = new DatabaseConnection();
  }

  public function createUser($name)
  {
    // Crear el objeto usuario
    $user = new User($name);

    // Guardar en base de datos
    $sql = "INSERT INTO users (name) VALUES ('{$user->name}')";
    $this->db->query($sql);

    // Loguear acción
    echo "Log: Usuario {$user->name} creado con éxito.\n";
  }
}
