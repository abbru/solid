<?php

interface DatabaseConnectionInterface
{
  public function connect();
  public function execute($query);
}
