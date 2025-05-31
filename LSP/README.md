# 🔁 Principio de Sustitución de Liskov (Liskov Substitution Principle - LSP)

El **Principio de Sustitución de Liskov (LSP)** dice que:

> "Si S es una subclase de T, entonces los objetos de tipo T pueden ser reemplazados por objetos de tipo S sin alterar las propiedades deseables del programa (exactitud, ejecución, etc.)."

En otras palabras: **una clase hija debe poder sustituir a su clase padre sin romper el comportamiento esperado**.

---
## ❌ Ejemplo que Viola LSP

Vamos a crear una nueva conexión a base de datos: `BrokenConnection`, que hereda de `DatabaseConnectionInterface`, pero no se comporta como una conexión válida. Esto rompe la lógica del programa.

```php
<?php
class BrokenConnection implements DatabaseConnectionInterface
{
  public function connect()
  {
    // No hace nada, ni siquiera lanza error
  }

  public function execute($query)
  {
    // Rompe el contrato esperado: no ejecuta, ni lanza excepción
    echo "No se puede ejecutar la query: $query\n";
  }
}
```

Ahora usamos esta clase:

```php
<?php
$badDb = new BrokenConnection();
$repository = new UserRepository($badDb); // Parece una conexión válida...

$manager = new UserManager($repository, new Logger());
$manager->createUser("Juan"); // ❌ El usuario no se guarda correctamente
```

Problema: Aunque BrokenConnection implementa la interfaz, rompe el contrato esperado. El programa depende de que connect() y execute() realmente hagan algo. Esto viola LSP.

---



### ✅ Ejemplo Corrigiendo la Violación de LSP

Aseguramos que todas las implementaciones respeten el comportamiento esperado, no solo la firma del método.

```php
<?php
class PostgreSQLConnection implements DatabaseConnectionInterface
{
  public function connect()
  {
    echo "Conectando a PostgreSQL...\n";
  }

  public function execute($query)
  {
    echo "Ejecutando query en PostgreSQL: $query\n";
  }
}
```


Probamos la sustitución:

```php
<?php
$db = new PostgreSQLConnection(); // Se sustituye sin romper nada
$repository = new UserRepository($db);
$logger = new Logger();

$manager = new UserManager($repository, $logger);
$manager->createUser("Ana"); // ✅ Funciona correctamente
```

### 🧠 Conclusión

Para cumplir LSP, las clases hijas o implementaciones:
- Deben respetar el contrato (comportamiento, no solo la firma).
- No deben lanzar excepciones inesperadas o ignorar operaciones críticas.
- No deben modificar la semántica esperada de la clase padre o interfaz.

---


