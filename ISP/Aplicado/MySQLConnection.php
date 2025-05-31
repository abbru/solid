<?php

class MySQLConnection implements DatabaseConnectionInterface
{
  public function connect()
  {
    echo "Conectando a MySQL...\n";
  }

  public function execute($query)
  {
    echo "Ejecutando query en MySQL: $query\n";
  }
}
