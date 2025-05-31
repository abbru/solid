<?php

class UserManager
{
  private $repository;
  private $logger;
  private $clickTracker;

  public function __construct(
    UserRepositoryInterface $repository,
    LoggerInterface $logger,
    ClickRepositoryInterface $clickTracker // ✅ solo si se necesita
  ) {
    $this->repository = $repository;
    $this->logger = $logger;
    $this->clickTracker = $clickTracker;
  }

  public function createUser($name, $idclick)
  {
    $user = new User($name);
    $this->repository->save($user);
    $this->logger->log("Usuario {$user->name} creado.");

    // Lógica opcional: rastreo de clic inicial
    $click = new Click($idclick);
    $this->clickTracker->updateClickStatus($click);
  }
}
