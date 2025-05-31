<?php

class MySQLConnection
{
  public function connect()
  {
    echo "Conectando a MySQL...\n";
  }

  public function execute($query)
  {
    echo "Ejecutando query: $query\n";
  }
}
