<?php

class UserManager
{
  private $repository;
  private $logger;

  public function __construct()
  {
    $this->repository = new UserRepository(); // 👈 Violación del OCP
    $this->logger = new Logger();             // 👈 Violación del OCP
  }

  public function createUser($name)
  {
    $user = new User($name);
    $this->repository->save($user);
    $this->logger->log("Usuario {$user->name} creado.");
  }
}
