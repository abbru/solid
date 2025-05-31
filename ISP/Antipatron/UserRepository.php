<?php

class UserRepository implements SubscriptionInterface
{
  public function connect()
  {
    echo "Conectando...\n";
  }

  public function saveUser(User $user)
  {
    echo "Guardando usuario {$user->name}\n";
  }

  public function updateClickStatus(Click $click)
  {
    // ¡No tiene sentido para UserRepository!
    throw new Exception("No implementado");
  }
}
