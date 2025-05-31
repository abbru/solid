## 📌 Principio de Responsabilidad Única (SRP)

### ¿Qué es SRP?

El **Principio de Responsabilidad Única (SRP)** establece que **una clase debe tener una única razón para cambiar**, es decir, debe tener una única responsabilidad o función dentro del sistema. Esto facilita la mantenibilidad y reduce el acoplamiento.

---

### Ejemplo general

Imaginemos una clase que hace varias tareas:
```php
class UserManager {
    public function createUser($name) {
        // Lógica para crear usuario
        echo "Usuario $name creado.\n";
        // Además, guarda en base de datos y registra evento
        $this->saveToDatabase($name);
        $this->logCreation($name);
    }

    private function saveToDatabase($name) {
        echo "Guardando $name en la base de datos.\n";
    }

    private function logCreation($name) {
        echo "Registrando la creación del usuario $name en log.\n";
    }
}
```
Esta clase tiene varias responsabilidades: creación de usuario, almacenamiento y logging. Esto rompe el SRP.

---

### Aplicando SRP: separando responsabilidades

Dividimos la clase en varias con responsabilidades únicas:

```php
class User {
    public $name;
    public function __construct($name) {
        $this->name = $name;
    }
}

class UserRepository {
    public function save(User $user) {
        echo "Guardando {$user->name} en la base de datos.\n";
    }
}

class Logger {
    public function log($message) {
        echo "Log: $message\n";
    }
}

class UserManager {
    private $repository;
    private $logger;

    public function __construct(UserRepository $repository, Logger $logger) {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function createUser($name) {
        $user = new User($name);
        $this->repository->save($user);
        $this->logger->log("Usuario {$user->name} creado.");
    }
}
```
---
Ahora cada clase tiene una única responsabilidad:

- User: representa un usuario (modelo de datos).
- UserRepository: se encarga del almacenamiento en base de datos.
- Logger: se encarga de registrar eventos.
- UserManager: coordina el proceso de creación.
---
## Ejemplo con conexión a base de datos (simplificado) 

### 🧨 Ejemplo que Viola SRP (Mala práctica) 

```php
class UserManager {
    private $db;

    public function __construct() {
        $this->db = new DatabaseConnection();
    }

    public function createUser($name) {
        // Crear el objeto usuario
        $user = new User($name);

        // Guardar en base de datos
        $sql = "INSERT INTO users (name) VALUES ('{$user->name}')";
        $this->db->query($sql);

        // Loguear acción
        echo "Log: Usuario {$user->name} creado con éxito.\n";
    }
}

class DatabaseConnection {
    public function query($sql) {
        echo "Ejecutando consulta SQL: $sql\n";
    }
}

class User {
    public $name;

    public function __construct($name) {
        $this->name = $name;
    }
}

// Uso
$userManager = new UserManager();
$userManager->createUser("Juan");

```

### 🔍 En este caso, UserManager:
- Crea usuarios.
- Guarda usuarios en base de datos.
- Loguea acciones.

➡️ Tiene múltiples responsabilidades, lo que viola SRP.

---

### ✅ Ejemplo que Respeta SRP (Buena práctica)


```php
class DatabaseConnection {
    public function query($sql) {
        echo "Ejecutando consulta SQL: $sql\n";
        // Aquí iría la ejecución real de la consulta.
    }
}

class UserRepository {
    private $db;

    public function __construct(DatabaseConnection $db) {
        $this->db = $db;
    }

    public function save(User $user) {
        $sql = "INSERT INTO users (name) VALUES ('{$user->name}')";
        $this->db->query($sql);
    }
}

class Logger {
    public function log($message) {
        echo "Log: $message\n";
    }
}

class UserManager {
    private $repository;
    private $logger;

    public function __construct(UserRepository $repository, Logger $logger) {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function createUser($name) {
        $user = new User($name);
        $this->repository->save($user);
        $this->logger->log("Usuario {$user->name} creado con éxito.");
    }
}

// Uso
$db = new DatabaseConnection();
$userRepo = new UserRepository($db);
$logger = new Logger();
$userManager = new UserManager($userRepo, $logger);

$userManager->createUser("Juan");
```
### Salida Esperada

```
Ejecutando consulta SQL: INSERT INTO users (name) VALUES ('Juan')
Log: Usuario Juan creado con éxito.
```

### ➡️ Ahora cada clase tiene una sola responsabilidad:
- UserManager solo coordina la creación del usuario.
- UserRepository se encarga de guardar.
- Logger se encarga de registrar.
- DatabaseConnection ejecuta las consultas.


### Beneficios del SRP

- Cambios en la lógica de almacenamiento no afectan el logging ni la lógica de creación.
- Facilita testear cada clase por separado.
- El código es más legible y modular.

---

