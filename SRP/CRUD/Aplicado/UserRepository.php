<?php

class UserRepository
{
  public function save(User $user)
  {
    echo "Guardando {$user->name} en la base de datos.\n";
  }
}
