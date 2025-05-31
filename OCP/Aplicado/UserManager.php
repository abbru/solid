<?php
class UserManager
{
  private $repository;
  private $logger;

  public function __construct(UserRepositoryInterface $repository, LoggerInterface $logger)
  {
    $this->repository = $repository;
    $this->logger = $logger;
  }

  public function createUser($name)
  {
    $user = new User($name);
    $this->repository->save($user);
    $this->logger->log("Usuario {$user->name} creado.");
  }
}
