<?php

class Logger implements LoggerInterface
{
  public function log($message)
  {
    echo "Log: $message\n";
  }
}
