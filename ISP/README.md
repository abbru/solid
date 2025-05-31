## 🧩 Principio de Segregación de Interfaces (ISP)

```
Definición:
    Ningún cliente debe verse forzado a depender de interfaces que no utiliza.
```

En otras palabras: es preferible tener interfaces pequeñas y específicas, en lugar de una gran interfaz con métodos que no todos sus implementadores necesitan.

### 📛 Ejemplo que VIOLA el ISP

Supongamos que creamos una interfaz ***SubscriptionInterface*** muy genérica, para combinar responsabilidades de guardar usuarios, tracking de clics , y conectarse a bases de datos:

```php
<?php
interface SubscriptionInterface
{
  public function connect();
  public function saveUser(User $user);
  public function updateClickStatus(Click $click);
}
```

```php
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

```

```
🔥 Problema: UserRepository está obligado a implementar un método que no tiene sentido para su contexto.
```


## ✅ Correcta aplicación del ISP

Para aplicar ISP, separás las interfaces según el rol real que representan. Así:

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

interface ClickRepositoryInterface
{
  public function findClick(string $idclick);
  public function updateClickStatus(Click $click);
}

```

## 🧱 Implementaciones específicas

```php
<?php
class Click implements ClickRepositoryInterface
{
  private $db;

  public function __construct(DatabaseConnectionInterface $db)
  {
    $this->db = $db;
  }

  public function updateClickStatus(Click $click) {
      //update status click
  }
}

class UserManager
{
  private $repository;
  private $logger;
  private $clickTracker;

  public function __construct(
    UserRepositoryInterface $repository,
    LoggerInterface $logger,
    ClickTrackable $clickTracker // ✅ solo si se necesita
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
    $click = new Click();
    $click = $click->findClick($idclick);
    $this->clickTracker->updateClickStatus($click);
  }
}
```

---

### 🎯 Resultado
- UserRepository ya no depende de updateClickStatus(), que no usa.
- ClickTracker solo se enfoca en su tarea: trackear clics.
- Cada clase implementa solo lo que necesita, sin métodos sin sentido.

### 🧠 Recuerda la regla de oro del ISP:
- ❝ Es mejor tener muchas interfaces pequeñas que una grande y forzosa. ❞

