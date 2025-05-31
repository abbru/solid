## 🟪 OCP – Principio de Abierto/Cerrado
```
"Las entidades de software (clases, módulos, funciones, etc.) deben estar abiertas para su extensión, pero cerradas para su modificación."
```

Esto significa que deberíamos poder agregar nuevas funcionalidades sin necesidad de modificar el código existente, lo cual minimiza riesgos de romper algo que ya funciona.

### ❌ Ejemplo que Viola OCP

Este ejemplo muestra una clase `UserRepository` acoplada directamente a una conexión MySQL y un `Logger`, lo que hace que modificar o cambiar alguna de esas dependencias requiera cambiar también el código del `UserManager`.

```php
<?php

class Logger
{
  public function log($message)
  {
    echo "Log: $message\n";
  }
}

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

class User
{
  public $name;

  public function __construct($name)
  {
    $this->name = $name;
  }
}

class UserRepository
{
  private $db;

  public function __construct()
  {
    $this->db = new MySQLConnection(); // 👈 Violación del OCP
  }

  public function save(User $user)
  {
    $this->db->connect();
    $this->db->execute("INSERT INTO users (name) VALUES ('{$user->name}')");
  }
}

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

```

Si quisiéramos usar otra base de datos o cambiar el logger, tendríamos que modificar directamente las clases UserRepository y UserManager, lo que rompe OCP.


### ✅ Ejemplo Aplicando OCP Correctamente

Refactorizamos usando interfaces para desacoplar componentes. Así podemos extender (por ejemplo, usando PostgreSQL o un nuevo logger) sin modificar las clases que ya funcionan.

***Interfaces***

```php
<?php
interface DatabaseConnectionInterface
{
  public function connect();
  public function execute($query);
}

interface LoggerInterface
{
  public function log($message);
}

interface UserRepositoryInterface
{
  public function save(User $user);
}
```

***Implementaciones***
```php
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

class Logger implements LoggerInterface
{
  public function log($message)
  {
    echo "Log: $message\n";
  }
}
```

***Entidad***
```php
<?php 
class User
{
  public $name;

  public function __construct($name)
  {
    $this->name = $name;
  }
}
```

***Repositorio***
```php
<?php 
class UserRepository implements UserRepositoryInterface
{
  private $db;

  public function __construct(DatabaseConnectionInterface $db)
  {
    $this->db = $db;
  }

  public function save(User $user)
  {
    $this->db->connect();
    $query = "INSERT INTO users (name) VALUES ('{$user->name}')";
    $this->db->execute($query);
  }
}
```

***Coordinador: UserManager***

```php
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
```

***🧪 index.php (ejecución)***

```php
<?php
$db = new MySQLConnection();
$repository = new UserRepository($db);
$logger = new Logger();

$manager = new UserManager($repository, $logger);
$manager->createUser("Abbru");
```

### 🧠 Beneficios del diseño

## ✅ Este diseño cumple OCP porque:
- Podés agregar nuevas conexiones (PostgreSQLConnection, SQLiteConnection) sin tocar UserRepository.
- Podés crear otros tipos de logger (FileLogger, DatabaseLogger) sin modificar UserManager.
- Las clases están cerradas a modificaciones y abiertas a extensión mediante nuevas implementaciones.

---

