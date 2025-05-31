<?php

class UserManager
{
  public function createUser($name)
  {
    // Lógica para crear usuario
    echo "Usuario $name creado.\n";
    // Además, guarda en base de datos y registra evento
    $this->saveToDatabase($name);
    $this->logCreation($name);
  }

  private function saveToDatabase($name)
  {
    echo "Guardando $name en la base de datos.\n";
  }

  private function logCreation($name)
  {
    echo "Registrando la creación del usuario $name en log.\n";
  }
}
