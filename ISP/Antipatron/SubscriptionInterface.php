<?php

interface SubscriptionInterface
{
  public function connect();
  public function saveUser(User $user);
  public function updateClickStatus(Click $click);
}
