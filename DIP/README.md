## 🔁 DIP – Dependency Inversion Principle
```
“Los módulos de alto nivel no deben depender de módulos de bajo nivel. Ambos deben depender de abstracciones.”
“Las abstracciones no deben depender de los detalles. Los detalles deben depender de las abstracciones.”
— Robert C. Martin
```

### 🔥 Violación del DIP

En este ejemplo, UserManager depende directamente de una clase concreta MySQLDatabase. Esto genera un acoplamiento fuerte: si en el futuro queremos cambiar la base de datos a PostgreSQL o MongoDB, tenemos que modificar UserManager.

```php

<?php

class MySQLDatabase
{
  public function connect()
  {
    echo "Conectando a MySQL...\n";
  }

  public function save(string $data)
  {
    echo "Guardando en MySQL: $data\n";
  }
}

class UserManager
{
  private $db;

  public function __construct()
  {
    $this->db = new MySQLDatabase(); // ❌ Instancia concreta
  }

  public function register(string $name)
  {
    $this->db->connect();
    $this->db->save($name);
  }
}

// Uso
$manager = new UserManager();
$manager->register("Abbru");

```

❌ ¿Qué está mal acá?
- UserManager depende directamente de una clase concreta (MySQLDatabase).
- No podemos reutilizar UserManager con otro tipo de base de datos sin modificar su código interno.

## ✅ Aplicando DIP

Ahora invertimos la dependencia: UserManager depende de una abstracción (DatabaseInterface). Las clases concretas como MySQLDatabase o PostgreSQLDatabase implementan esa interfaz.

```php
<?php
<?php

interface DatabaseInterface
{
  public function connect(): void;
  public function save(string $data): void;
}

class MySQLDatabase implements DatabaseInterface
{
  public function connect(): void
  {
    echo "Conectando a MySQL...\n";
  }

  public function save(string $data): void
  {
    echo "Guardando en MySQL: $data\n";
  }
}

class UserManager
{
  private DatabaseInterface $db;

  public function __construct(DatabaseInterface $db)
  {
    $this->db = $db;
  }

  public function register(string $name)
  {
    $this->db->connect();
    $this->db->save($name);
  }
}

// Uso
$mysql = new MySQLDatabase();
$manager = new UserManager($mysql);
$manager->register("Abbru");
```


✅ ¿Qué logramos?
- UserManager no conoce los detalles de la base de datos.
- Podemos cambiar la implementación (ej. PostgreSQLDatabase) sin tocar UserManager.
- El código es más flexible, testeable (con mocks) y abierto a la extensión.

