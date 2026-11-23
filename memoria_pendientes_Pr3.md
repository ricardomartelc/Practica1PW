# Memoria Práctica 3 — apartados pendientes (investigado sobre el código real)

Proyecto: `c:\laragon\www\pweb26`. Todo lo marcado «Verificado» sale de leer el código (ruta:línea). Nada se ha ejecutado salvo `php artisan route:list` (solo lectura). No se ha modificado ningún archivo del proyecto; este es el único archivo creado.

Hallazgos importantes que afectan a la memoria (léelos antes de copiar nada):
1. `DatabaseSeeder` **no llama** a `AlmacenProductoresSeeder` ni a `TiendaVendedoresSeeder` (ver D1/D2). Con `php artisan db:seed` se insertan 55 de los 84 registros.
2. El `.env` usa `DB_CONNECTION=sqlite`; las migraciones solo crean los CHECK si el driver es MySQL/MariaDB (ver C3).
3. La vista `miPortal.acerca` no existe, pero la ruta `/miPortal/acerca` y el menú la usan (ver F3).
4. Hay **más de nueve** migraciones: las nueve de `2026_10_28_00000X` más `personal_access_tokens` y una nueva de `2026_11_23` (ver C1).

---

## A. Bloque 1 – menuArchivos

### A1. Comparación con la regla del switch

**Verificado en el código**

Hay tres versiones en `public/misPhp`:

| Fichero | ¿Aplica la regla (solo `*Publico.html`, `*Privado.html` y `.php` de misPhp)? |
|---|---|
| `menuArchivos2.php` | **Sí.** `switch ($titulo)` en la línea 23, con `case "public"` (l.24-25: exige `Publico.html`), `case "practica"` (l.29-30: exige `Privado.html`) y `case "misPhp"` (l.34-35: exige `.php`). |
| `menuArchivos.php` | **No.** Enlaza casi todo (detalle abajo). |
| `menuArchivos copy.php` | **No.** Solo salta `.` y `..` y enlaza todo lo de `public`. |

Qué hace el código **actual de `menuArchivos.php`** con los archivos que preguntas (carpeta `public`, rama `if ($tipo === 'public')`, líneas 14-24):

| Archivo | ¿Se enlaza? | Por qué |
|---|---|---|
| `.htaccess` | **No** (ni siquiera se lista) | l.10 `if (str_starts_with($item, '.')) continue;` salta todo lo que empieza por punto. |
| `favicon.ico` | **Sí** → `http://pweb26.test/favicon.ico` | Es un archivo (no dir): l.17-23 enlazan cualquier archivo; como no es `.html`, l.20 usa `"/$item"`. |
| `index.php` | **Sí** → `/index.php` | Mismo motivo. |
| `robots.txt` | **Sí** → `/robots.txt` | Mismo motivo. |
| `Public.html` | **Sí** → `/Public` | Es `.html`; l.19 `str_replace('Publico','',…)` no cambia `Public` (no contiene «Publico») y l.20 genera `/Public`. No hay ruta `/Public` en `routes/web.php`, así que esa URL probablemente no funciona. |

Además, en `menuArchivos.php`:
- Rama `practica` (l.25-31): enlaza **todos** los archivos de `practica` sin comprobar el sufijo, incluido `rutasPorDefecto.txt` → `/paginaPersonal/rutasPorDefecto` (la ruta `routes/web.php:74-79` busca `rutasPorDefectoPrivado.html`, que no existe → 404 al abrirlo).
- Hay comentarios `// ...` escritos **fuera** de las etiquetas `<?php ?>` en las líneas 55 y 57 (dentro del HTML), así que el navegador los muestra como texto visible.
- Usa `htmlspecialchars`, `pathinfo`, `str_starts_with` y `__DIR__`, que no están en `menuArchivos2.php`.

`menuArchivos copy.php` (l.24 solo salta `.` y `..`; l.34): enlaza todo archivo de `public` con `http://pweb26.test/$item`, **incluido `.htaccess`**; la rama `practica` (l.37-38) calcula `$nombreSinExt` pero no imprime nada.

**Respuesta corta para la memoria:** el código que cumple la regla es `menuArchivos2.php` (con `switch`). Describir `menuArchivos.php` como «solo enlaza Publico/Privado/php» sería falso: enlaza también `favicon.ico`, `index.php`, `robots.txt` y `Public.html`; `.htaccess` no se enlaza solo porque se omite por empezar con punto.

**Probable (sin verificar)**
- Que el profesor espere la regla del enunciado: conviene entregar `menuArchivos2.php` o corregir `menuArchivos.php`. Qué versión pide el enunciado no está en el repositorio.

### A2. Nombre del formulario y del fichero

**Verificado en el código**

| Menú | Atributo `action` | Fichero destino |
|---|---|---|
| `public/misPhp/menuArchivos.php:55` | `ejecutarArchivo.php` | existe: `public/misPhp/ejecutarArchivo.php` |
| `public/misPhp/menuArchivos copy.php:62` | `ejecutarArchivo.php` | el mismo |
| `public/misPhp/menuArchivos2.php:57` | `ejecutarArchivo2.php` | existe: `public/misPhp/ejecutarArchivo2.php` |

Los formularios usan `method="post"` y un `<select name="orden">`. En `menuArchivos.php` el valor del `<option>` es la URL sin comillas, pero el texto visible lleva comillas simples (l.23, 31, 36: `$opciones[$url] = "'$url'"`).

---

## B. Bloque 1 – ejecutarArchivo

### B1. Código completo y explicación línea a línea

**Verificado en el código** — `public/misPhp/ejecutarArchivo.php` (el que usa `menuArchivos.php`):

```php
<?php

$orden = $_POST['orden'] ?? null; // Obtenemos el valor de 'orden' del formulario enviado por POST, si no existe, se asigna null

if ($orden !== null && $orden !== '') { // Verificamos que la variable $orden no sea nula ni esté vacía
    // Eliminamos comillas simples de los extremos si las hubiera
    $ordenLimpia = trim($orden, "'"); // Eliminamos comillas simples de los extremos de la cadena
    $urlSegura = htmlspecialchars($ordenLimpia, ENT_QUOTES, 'UTF-8'); // Escapamos caracteres especiales para prevenir inyecciones de código y XSS
    
    // Generamos la etiqueta HTML de hipervínculo <a>
    echo "La orden a ejecutar es <a href='" . $urlSegura . "'>" . $urlSegura . "</a>"; // Mostramos la orden como un enlace seguro en la página web
} else {
    echo "No existe ninguna orden a ejecutar";
}
?>
```

Explicación para principiantes:
- **L.3** `$_POST['orden']` es el valor que mandó el formulario (el `name="orden"` del `<select>`). `?? null` significa «si no ha llegado, usa `null`» (así no da error).
- **L.5** `if` comprueba dos cosas: que no sea `null` y que tampoco sea cadena vacía. `!==` compara valor y tipo.
- **L.7** `trim($orden, "'")` quita las comillas simples del principio y del final. Hace falta porque `menuArchivos.php` envía el texto con comillas (`'/misPhp/x.php'`).
- **L.8** `htmlspecialchars(...)` convierte caracteres peligrosos (`<`, `>`, `'`, `"`) en texto inofensivo, para que nadie pueda colar HTML o JavaScript.
- **L.11** `echo` escribe en la página: «La orden a ejecutar es» + un enlace `<a>` cuyo destino y texto son la orden.
- **L.12-14** `else`: si no llegó nada, escribe «No existe ninguna orden a ejecutar».
- **L.15** `?>` cierra PHP.

Versión simple `public/misPhp/ejecutarArchivo2.php` (la que usa `menuArchivos2.php`):

```php
<?php
// Comprobar si llega la orden por POST
if (!empty($_POST["orden"])) {
    $orden = $_POST["orden"];
    echo "La orden a ejecutar es <a href='$orden'>$orden</a>";
} else {
    echo "No existe ninguna orden a ejecutar";
}

```

- **L.3** `!empty($_POST["orden"])` es verdadero si `orden` existe y no está vacío (equivale a las dos comprobaciones de la versión larga).
- **L.4-5** guarda la orden y la muestra como enlace.
- **L.6-7** si no, muestra «No existe ninguna orden a ejecutar».

### B2. Mensaje «No existe ninguna orden a ejecutar»

**Verificado en el código:** sí, en ambos ficheros.
- `ejecutarArchivo.php:13` (rama `else` de la l.12, cuando `$orden` es `null` o `''`).
- `ejecutarArchivo2.php:7` (rama `else` de la l.6, cuando `$_POST["orden"]` no existe o está vacío).

**Solo se ve ejecutando:** se activa abriendo la URL directamente con GET (no hay `$_POST`). Ver H1 para la captura.

---

## C. Bloque 2 – migraciones

### C1. Lista de migraciones

**Verificado en el código** (`database/migrations/`, orden alfabético = orden de ejecución):

| Orden | Fichero | Tabla |
|---|---|---|
| 1 | `0001_01_01_000000_create_users_table.php` | `users` (+ tablas de plantilla de Laravel) |
| 2 | `0001_01_01_000001_create_cache_table.php` | `cache` |
| 3 | `0001_01_01_000002_create_jobs_table.php` | `jobs` |
| 4 | `2026_10_28_000001_create_clientes_table.php` | `clientes` |
| 5 | `2026_10_28_000002_create_vendedores_table.php` | `vendedores` |
| 6 | `2026_10_28_000003_create_productores_table.php` | `productores` |
| 7 | `2026_10_28_000004_create_productos_table.php` | `productos` |
| 8 | `2026_10_28_000005_create_almacen_productores_table.php` | `almacen_productores` |
| 9 | `2026_10_28_000006_create_tienda_vendedores_table.php` | `tienda_vendedores` |
| 10 | `2026_10_28_000007_create_sensores_table.php` | `sensores` |
| 11 | `2026_10_28_000008_create_pedidos_table.php` | `pedidos` |
| 12 | `2026_10_28_000009_create_detalles_table.php` | `detalles` |
| 13 | `2026_10_28_090648_create_personal_access_tokens_table.php` | `personal_access_tokens` (Sanctum) |
| 14 | `2026_11_23_000001_add_periodicidad_to_sensores_table.php` | modifica `sensores`: añade `periodicidad` (Práctica 4) |

Las «nueve migraciones» de la memoria son las filas 4 a 12. El orden respeta las claves foráneas (productores y productos antes de `almacen_productores`, etc.).

**Probable (sin verificar):** los nombres `…_00000X_…` no son los que genera `make:migration` (que usa la hora real, como `2026_10_28_090648`), así que se pusieron o renombraron a mano para fijar el orden.

### C2. Código de las ocho migraciones pedidas

**Verificado en el código** (copia literal de los ficheros):

#### clientes — `2026_10_28_000001_create_clientes_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nif', 50)->unique();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('nombre', 255);
            $table->string('apellidos', 255)->nullable();
            $table->enum('tipo', ['particular', 'empresa'])->default('particular');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};

```

#### vendedores — `2026_10_28_000002_create_vendedores_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->id();
            $table->string('nif', 50)->unique();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('nombre_puesto', 255);
            $table->string('ubicacion', 255);
            $table->enum('entrega', ['recogida', 'envio'])->default('recogida');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendedores');
    }
};

```

#### productores — `2026_10_28_000003_create_productores_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productores', function (Blueprint $table) {
            $table->id();
            $table->string('nif', 50)->unique();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('nombre', 255);
            $table->string('municipio', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productores');
    }
};

```

#### productos — `2026_10_28_000004_create_productos_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('categoria', 100);
            $table->string('unidad', 50);
            $table->decimal('co2_por_unidad', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};

```

#### tienda_vendedores — `2026_10_28_000006_create_tienda_vendedores_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tienda_vendedores', function (Blueprint $table) {
            $table->foreignId('vendedor_id')->constrained('vendedores')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('stock')->default(0);
            $table->decimal('precio_b2c', 10, 2);
            $table->decimal('precio_b2b', 10, 2);
            $table->integer('minimo_b2b')->default(1);
            $table->integer('descuento_pct')->default(0);
            $table->primary(['vendedor_id', 'producto_id']);
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE tienda_vendedores ADD CONSTRAINT ck_tienda_stock CHECK (stock >= 0)');
            DB::statement('ALTER TABLE tienda_vendedores ADD CONSTRAINT ck_tienda_descuento CHECK (descuento_pct BETWEEN 0 AND 100)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tienda_vendedores');
    }
};

```

#### sensores — `2026_10_28_000007_create_sensores_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->constrained('vendedores')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('codigo_dispositivo', 100)->unique();
            $table->enum('tipo', ['temperatura', 'humedad'])->default('temperatura');
            $table->decimal('valor_actual', 10, 2)->nullable();
            $table->decimal('umbral_min', 10, 2);
            $table->decimal('umbral_max', 10, 2);
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE sensores ADD CONSTRAINT ck_sensores_umbrales CHECK (umbral_min < umbral_max)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sensores');
    }
};

```

#### pedidos — `2026_10_28_000008_create_pedidos_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnUpdate();
            $table->foreignId('vendedor_id')->constrained('vendedores')->cascadeOnUpdate();
            $table->dateTime('fecha')->useCurrent();
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('estado', ['pendiente', 'listo_recoger', 'recogido', 'enviado', 'recibido'])
                  ->default('pendiente');
            $table->string('direccion_entrega', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};

```

#### detalles — `2026_10_28_000009_create_detalles_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnUpdate();
            $table->integer('cantidad');
            $table->decimal('precio_aplicado', 10, 2);
            $table->unique(['pedido_id', 'producto_id'], 'uq_detalles_pedido_producto');
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE detalles ADD CONSTRAINT ck_detalles_cantidad CHECK (cantidad > 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles');
    }
};

```

#### (extra, Práctica 4) periodicidad — `2026_11_23_000001_add_periodicidad_to_sensores_table.php`
```php
<?php

// Práctica 4, Bloque 2 (RMC) — Añade a cada sensor la escala de periodicidad
// de sus muestras: minuto, hora o día. Los sensores existentes quedan en 'hora'.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sensores', function (Blueprint $table) {
            $table->enum('periodicidad', ['minuto', 'hora', 'dia'])->default('hora')->after('umbral_max');
        });
    }

    public function down(): void
    {
        Schema::table('sensores', function (Blueprint $table) {
            $table->dropColumn('periodicidad');
        });
    }
};

```

### C3. ¿Alguna migración hace algo distinto a lo normal?

**Verificado en el código**
- **CHECK con `DB::statement`** (solo si `DB::getDriverName()` es `mysql` o `mariadb`):
  - `almacen_productores`: `ck_almacen_stock` → `stock >= 0` (…000005, l.21).
  - `tienda_vendedores`: `ck_tienda_stock` → `stock >= 0` y `ck_tienda_descuento` → `descuento_pct BETWEEN 0 AND 100` (…000006, l.24-25).
  - `sensores`: `ck_sensores_umbrales` → `umbral_min < umbral_max` (…000007, l.23).
  - `detalles`: `ck_detalles_cantidad` → `cantidad > 0` (…000009, l.22).
- **ENUM** (`$table->enum`): `clientes.tipo` (particular/empresa), `vendedores.entrega` (recogida/envio), `sensores.tipo` (temperatura/humedad), `pedidos.estado` (5 estados), `sensores.periodicidad` (minuto/hora/dia).
- **Claves primarias compuestas**: `almacen_productores` (`productor_id`, `producto_id`; …000005, l.17) y `tienda_vendedores` (`vendedor_id`, `producto_id`; …000006, l.20). No tienen `id`.
- **Restricción única compuesta**: `detalles` `unique(['pedido_id','producto_id'], 'uq_detalles_pedido_producto')` (…000009, l.18).
- **Claves foráneas con reglas distintas**: `cascadeOnDelete()` en las tablas asociativas, en `sensores` y en `detalles.pedido_id`; en `pedidos` y en `detalles.producto_id` solo `cascadeOnUpdate()` (no se puede borrar un cliente, vendedor o producto con pedidos).
- `pedidos.fecha` usa `->useCurrent()`; ninguna tabla propia usa `timestamps()`.

**Importante (verificado):** el `.env` tiene `DB_CONNECTION=sqlite`. Con SQLite, la condición `in_array(DB::getDriverName(), ['mysql','mariadb'])` es falsa y **los CHECK no se crean**. Si afirmas en la memoria que la base tiene esos CHECK, solo es cierto con MySQL/MariaDB.

---

## D. Bloque 2 – seeders

### D1. Los nueve seeders y número de registros

**Verificado en el código.** Los nueve seeders (`database/seeders/*Seeder.php`) tienen el mismo cuerpo: `DB::unprepared(file_get_contents(database_path('seeders/sql/<tabla>.sql')))`. No usan arrays ni `create`; los registros están en `database/seeders/sql/*.sql` como filas de un `INSERT` (cuento las líneas que empiezan por `(`):

| Seeder | SQL | Registros |
|---|---|---|
| `ClientesSeeder.php` | `clientes.sql` | 5 |
| `ProductoresSeeder.php` | `productores.sql` | 4 |
| `ProductosSeeder.php` | `productos.sql` | 12 |
| `VendedoresSeeder.php` | `vendedores.sql` | 3 |
| `SensoresSeeder.php` | `sensores.sql` | 5 |
| `PedidosSeeder.php` | `pedidos.sql` | 8 |
| `DetallesSeeder.php` | `detalles.sql` | 18 |
| `AlmacenProductoresSeeder.php` | `almacen_productores.sql` | 16 |
| `TiendaVendedoresSeeder.php` | `tienda_vendedores.sql` | 13 |
| **Total** | | **84** |

**Coincide con los 84 registros de la tabla de la memoria** (5+4+12+3+5+8+18+16+13 = 84).

**Matiz verificado (importante):** `DatabaseSeeder.php:14-22` llama solo a 7 seeders (Clientes, Productores, Productos, Vendedores, Sensores, Pedidos, Detalles). **Faltan `AlmacenProductoresSeeder` y `TiendaVendedoresSeeder`** (16 + 13 = 29 registros). Con `php artisan db:seed` se insertarían 55, no 84, y `almacen_productores` y `tienda_vendedores` quedarían vacías (y con ellas lo que muestran las vistas de puestos, productos y productores). No hay ninguna otra llamada a esos dos seeders en el proyecto. Para tener los 84 habría que ejecutarlos aparte (`php artisan db:seed --class=...`) o añadirlos a `DatabaseSeeder` (no lo he tocado).

**NO VERIFICADO — estado real de la base de datos:** no he abierto ni consultado la base SQLite. Cuántos registros hay realmente en tu máquina solo se sabe ejecutando consultas.
- **Probable (sin verificar):** que en tu base local estén los 84 si ejecutaste los dos seeders a mano; el repositorio no contiene la base.

### D2. Seeder de ejemplo y cómo los llama DatabaseSeeder

**Verificado en el código** — el más simple (todos tienen la misma forma), `ProductoresSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoresSeeder extends Seeder
{
    /**
     * Inicializa la tabla productores ejecutando su fichero SQL.
     */
    public function run(): void
    {
        DB::unprepared(file_get_contents(database_path('seeders/sql/productores.sql')));
    }
}

```

Fichero SQL que ejecuta (`database/seeders/sql/productores.sql`):

```sql
-- Valores iniciales de la tabla productores (casos de uso de la práctica 2)
INSERT INTO productores (id, nif, email, password, nombre, municipio) VALUES
(1, 'B35444441', 'info@fincalosalmendros.example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Finca Los Almendros',              'Agüimes'),
(2, 'F35444442', 'info@coopvalsequillo.example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Cooperativa Agrícola Valsequillo', 'Valsequillo'),
(3, 'B35444443', 'info@quesosdeguia.example.com',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quesería Artesanal de Guía',       'Santa María de Guía'),
(4, 'B35444444', 'info@molinodegaldar.example.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Molino y Obrador de Gáldar',       'Gáldar');

```

`DatabaseSeeder.php` (l.14-22 llaman a los seeders con `$this->call([...])`, en este orden, que respeta las claves foráneas):

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClientesSeeder::class,
            ProductoresSeeder::class,
            ProductosSeeder::class,
            VendedoresSeeder::class,
            SensoresSeeder::class,
            PedidosSeeder::class,
            DetallesSeeder::class,
        ]);
    }
}
```

---

## E. Bloque 3 – modelos

### E1. Ficha de cada modelo

**Verificado en el código** (todos en `app/Models/`; todos con `$timestamps = false` salvo `User`):

| Modelo (fichero) | `$table` | `$fillable` | Relaciones | Casts / otros |
|---|---|---|---|---|
| `AlmacenProductor` (`app/Models/AlmacenProductor.php`), extiende `Pivot` | `almacen_productores` (l.10) | `productor_id, producto_id, stock, precio_origen` (l.17) | `belongsTo(Productor)`, `belongsTo(Producto)` (l.23-31) | `precio_origen` → `decimal:2`; `$incrementing = false` (clave compuesta) |
| `Cliente` (`app/Models/Cliente.php`) | `clientes` (l.10) | `nif, email, password, nombre, apellidos, tipo` (l.16) | `hasMany(Pedido)` (l.27-30) | `password` → `hashed`; `$hidden = ['password']` |
| `Detalle` (`app/Models/Detalle.php`) | `detalles` (l.9) | `pedido_id, producto_id, cantidad, precio_aplicado` (l.13) | `belongsTo(Pedido)`, `belongsTo(Producto)` | `precio_aplicado` → `decimal:2` |
| `Pedido` (`app/Models/Pedido.php`) | `pedidos` (l.9) | `cliente_id, vendedor_id, fecha, total, estado, direccion_entrega` (l.13) | `belongsTo(Cliente)`, `belongsTo(Vendedor)`, `hasMany(Detalle)` | `fecha` → `datetime`, `total` → `decimal:2` |
| `Producto` (`app/Models/Producto.php`) | `productos` (l.10) | `nombre, categoria, unidad, co2_por_unidad` (l.14) | `belongsToMany(Productor)` vía `almacen_productores` con `->using(AlmacenProductor)` y `withPivot(stock, precio_origen)`; `belongsToMany(Vendedor)` vía `tienda_vendedores` con `->using(TiendaVendedor)` y `withPivot(stock, precio_b2c, precio_b2b, minimo_b2b, descuento_pct)`; `hasMany(Detalle)` | `co2_por_unidad` → `decimal:2` |
| `Productor` (`app/Models/Productor.php`) | `productores` (l.9) | `nif, email, password, nombre, municipio` (l.13) | `belongsToMany(Producto)` vía `almacen_productores` con `AlmacenProductor` y pivot `stock, precio_origen` | `password` → `hashed`; `$hidden = ['password']` |
| `Sensor` (`app/Models/Sensor.php`) | `sensores` (l.9) | `vendedor_id, codigo_dispositivo, tipo, valor_actual, umbral_min, umbral_max, periodicidad` (l.13) | `belongsTo(Vendedor)` | `valor_actual`, `umbral_min`, `umbral_max` → `decimal:2` |
| `TiendaVendedor` (`app/Models/TiendaVendedor.php`), extiende `Pivot` | `tienda_vendedores` (l.10) | `vendedor_id, producto_id, stock, precio_b2c, precio_b2b, minimo_b2b, descuento_pct` (l.17) | `belongsTo(Vendedor)`, `belongsTo(Producto)` | `precio_b2c`, `precio_b2b` → `decimal:2`; `$incrementing = false` |
| `User` (`app/Models/User.php`) | no define `$table` (usa `users` por convención) | `name, email, password` (atributo `#[Fillable]`, l.13) | ninguna | `casts()`: `email_verified_at` → `datetime`, `password` → `hashed`; `#[Hidden(['password','remember_token'])]`; usa `HasFactory, Notifiable` |
| `Vendedor` (`app/Models/Vendedor.php`) | `vendedores` (l.9) | `nif, email, password, nombre_puesto, ubicacion, entrega` (l.13) | `belongsToMany(Producto)` vía `tienda_vendedores` con `TiendaVendedor` y pivot `stock, precio_b2c, precio_b2b, minimo_b2b, descuento_pct`; `hasMany(Sensor)`; `hasMany(Pedido)` | `password` → `hashed`; `$hidden = ['password']` |

### E2. Comando de Artisan usado para crear los modelos

**NO VERIFICADO.** El código no guarda el comando.

**Verificado en el código (pistas):**
- El historial de git tiene «Bloque 3: modelos Eloquent de Ecodirecto» (commit `0d04971`) y «modelos adaptados al modelo de datos de 9 tablas» (`d303823`): los modelos se crearon y luego se reescribieron a mano.
- `database/factories/` solo contiene `UserFactory.php`: no hay factories de los modelos propios (y solo `User` usa `HasFactory`), así que no se generaron con la opción `-f`.
- `AlmacenProductor` y `TiendaVendedor` extienden `Pivot` (clase para tablas intermedias).

**Probable (sin verificar):** `php artisan make:model Cliente` (y análogos) para los modelos normales y `php artisan make:model AlmacenProductor --pivot` para los pivotes. Las migraciones se habrían creado con `make:migration create_<tabla>_table` y luego renombrado, porque su numeración `_00000X_` no es la que genera Artisan. Es lo más verosímil porque las pistas concuerdan, pero no se puede asegurar.

### E3. Restricciones de uso de los modelos (resumen)

**Verificado en el código**
1. Ningún modelo propio usa timestamps (`$timestamps = false`), porque las tablas no tienen `created_at` ni `updated_at`.
2. La asignación en masa está limitada con `$fillable` en cada modelo; el `id` nunca es asignable.
3. `Cliente`, `Productor` y `Vendedor` ocultan `password` en JSON (`$hidden`) y lo cifran automáticamente con el cast `hashed`.
4. Las relaciones son: Cliente 1—N Pedido, Vendedor 1—N Pedido, Pedido 1—N Detalle, Producto 1—N Detalle, Vendedor 1—N Sensor, y dos N:M con tabla pivote propia: Producto↔Productor (`almacen_productores`) y Producto↔Vendedor (`tienda_vendedores`).
5. Las N:M usan modelos pivote (`->using(...)`) con `withPivot` para leer stock y precios; los pivotes tienen `$incrementing = false` porque su clave primaria es compuesta.
6. Los importes se convierten con `decimal:2` y la fecha del pedido con `datetime`.
7. Los modelos **no contienen validaciones**: las reglas están en los controladores (`GestionPuestoController.php` l.14-20 y `Api/SensorController.php` l.18-24 y 36-39) y, en MySQL/MariaDB, en los CHECK de las migraciones (ver C3).
8. `User` es el modelo estándar de Laravel con atributos `#[Fillable]` y `#[Hidden]`.

---

## F. Bloque 4 – controladores, vistas y rutas

### F1. Controladores (excluido `Api/`)

**Verificado en el código** (`app/Http/Controllers/`)

| Controlador | Método | Para qué sirve |
|---|---|---|
| `Controller.php` | — | Clase base abstracta vacía. |
| `ProductoController.php` | `index()` (l.10) | Lista todos los productos ordenados por nombre → vista `productos.index`. |
| | `show(Producto $producto)` (l.18) | Ficha de un producto → vista `productos.show`. |
| `VendedorController.php` | `index()` (l.10) | Lista los puestos ordenados por `nombre_puesto` → `puestos.index`. |
| | `show(Vendedor $vendedor)` (l.18) | Página de un puesto con sus productos → `puestos.show`. |
| `ProductorController.php` | `index()` (l.10) | Lista los productores → `productores.index`. |
| | `show(Productor $productor)` (l.18) | Ficha de un productor con lo que tiene en su finca → `productores.show`. |
| `GestionPuestoController.php` | `index(Vendedor)` (l.33) | Productos del puesto + productos del catálogo que aún no vende → `gestion.index`. |
| | `store(Request, Vendedor)` (l.44) | Valida y añade un producto al puesto (`attach` en `tienda_vendedores`), redirige con mensaje. |
| | `edit(Vendedor, Producto)` (l.54) | Formulario de edición; usa `findOrFail` sobre los productos del puesto → `gestion.edit`. |
| | `update(Request, Vendedor, Producto)` (l.62) | Valida y actualiza los datos del pivote (`updateExistingPivot`). |
| | `destroy(Vendedor, Producto)` (l.72) | Quita el producto del puesto (`detach`); el producto sigue en el catálogo. |

Reglas de validación de `GestionPuestoController` (l.14-20): `stock` entero ≥0; `precio_b2c` y `precio_b2b` numéricos ≥0; `minimo_b2b` entero ≥1; `descuento_pct` entero 0-100; en `store` además `producto_id` obligatorio y existente en `productos`. Mensajes en español en l.23-30.

### F2. Vistas y datos que reciben

**Verificado en el código** (`resources/views/`; variables que usa cada plantilla):

| Vista | Variables | Quién la usa |
|---|---|---|
| `welcome.blade.php` | ninguna | `/` (web.php:14) |
| `elGrupo.blade.php` | ninguna; incluye `partials.header` y `partials.footer` | `/elGrupo` |
| `paginaPersonal_Grupo4.blade.php` | `$nombre`, `$contenido` | `/paginaPersonal/{nombre}` (web.php:90) |
| `partials/header.blade.php`, `partials/footer.blade.php` | ninguna | parciales |
| `miPortal/layout.blade.php` | `@yield('content')`; incluye `miPortal.partials.header` y `partials.footer` | base de las demás |
| `miPortal/partials/header.blade.php` | ninguna (menú con `route(...)`) | layout |
| `miPortal/home.blade.php` | ninguna (`@extends layout`) | `/miPortal`, `/miPortal/home` |
| `productos/index.blade.php` | `$productos` (itera `$producto`, incluye `tarjeta`) | `ProductoController@index` |
| `productos/partials/tarjeta.blade.php` | `$producto` (`nombre, categoria, unidad`) | parcial |
| `productos/show.blade.php` | `$producto` (`nombre, categoria, unidad`; `vendedores` con `pivot->precio_b2c`, `pivot->stock`) | `ProductoController@show` |
| `puestos/index.blade.php` | `$vendedores` (incluye `tarjeta`) | `VendedorController@index` |
| `puestos/partials/tarjeta.blade.php` | `$vendedor` (`nombre_puesto, ubicacion, entrega`) | parcial |
| `puestos/show.blade.php` | `$vendedor` (`nombre_puesto, ubicacion, entrega`; `productos` con `pivot->precio_b2c`, `pivot->stock`; enlace a `gestion.index`) | `VendedorController@show` |
| `productores/index.blade.php` | `$productores` (`nombre, municipio`) | `ProductorController@index` |
| `productores/show.blade.php` | `$productor` (`nombre, municipio`; `productos` con `pivot->stock`) | `ProductorController@show` |
| `gestion/index.blade.php` | `$vendedor`, `$disponibles`; usa `$errors` y `route('gestion.*')` | `GestionPuestoController@index` |
| `gestion/edit.blade.php` | `$vendedor`, `$producto` (`$producto->pivot` se pasa a `campos`); `@method('PUT')` | `GestionPuestoController@edit` |
| `gestion/partials/campos.blade.php` | `$valores` (`stock, precio_b2c, precio_b2b, minimo_b2b, descuento_pct`; `null` en el alta) | parcial |
| `practica4/basicoRMC.blade.php` | ninguna | `/practica4/basicoRMC` (Práctica 4) |
| `practica4/sensoresRMC.blade.php` | `$sensores` (cada `$sensor`: `id, codigo_dispositivo, tipo, umbral_min, umbral_max, periodicidad, vendedor->nombre_puesto`) | `/practica4/sensoresRMC` (Práctica 4) |

**Falta:** no existe `resources/views/miPortal/acerca.blade.php` (la carpeta `miPortal` solo tiene `home`, `layout` y `partials`).

### F3. Rutas (`routes/web.php`) y `php artisan route:list`

**Verificado.** Salida real de `php artisan route:list` (solo la parte web; se omiten `storage`, `up`, `sanctum/csrf-cookie` y la API, ver G3):

| Método | URI | Controlador / acción | Vista |
|---|---|---|---|
| GET | `/` (web.php:14) | closure | `welcome` |
| GET | `/Ricardo`, `/Rosalinda`, `/Adrian` (l.24-34) | closure → redirige a `*Publico.html` | — (archivo en `public`) |
| GET | `/RicardoPrivado`, `/RosalindaPrivado`, `/AdrianPrivado` (l.44-66) | closure → `response()->file(practica/…Privado.html)` o 404 | — |
| GET | `/paginaPersonal/{nombre}` (l.74) | closure (lee `practica/{nombre}Privado.html`) | `paginaPersonal_Grupo4` |
| GET | `/elGrupo` (l.102) | closure | `elGrupo` |
| GET | `/miPortal`, `/miPortal/home` (l.112-118) | closure | `miPortal.home` |
| GET | `/miPortal/acerca` (l.126) | closure | `miPortal.acerca` (**el fichero no existe**) |
| GET | `/productos` (`productos.index`) | `ProductoController@index` | `productos.index` |
| GET | `/productos/{producto}` (`productos.show`) | `ProductoController@show` | `productos.show` |
| GET | `/puestos` (`puestos.index`) | `VendedorController@index` | `puestos.index` |
| GET | `/puestos/{vendedor}` (`puestos.show`) | `VendedorController@show` | `puestos.show` |
| GET | `/productores` (`productores.index`) | `ProductorController@index` | `productores.index` |
| GET | `/productores/{productor}` (`productores.show`) | `ProductorController@show` | `productores.show` |
| GET | `/puestos/{vendedor}/gestion` (`gestion.index`) | `GestionPuestoController@index` | `gestion.index` |
| POST | `/puestos/{vendedor}/gestion` (`gestion.store`) | `GestionPuestoController@store` | redirige a `gestion.index` |
| GET | `/puestos/{vendedor}/gestion/{producto}/editar` (`gestion.edit`) | `GestionPuestoController@edit` | `gestion.edit` |
| PUT | `/puestos/{vendedor}/gestion/{producto}` (`gestion.update`) | `GestionPuestoController@update` | redirige a `gestion.index` |
| DELETE | `/puestos/{vendedor}/gestion/{producto}` (`gestion.destroy`) | `GestionPuestoController@destroy` | redirige a `gestion.index` |
| GET | `/practica4/basicoRMC` (`practica4.basicoRMC`, l.184) | closure | `practica4.basicoRMC` |
| GET | `/practica4/sensoresRMC` (`practica4.sensoresRMC`, l.195) | closure (`Sensor::with('vendedor')`) | `practica4.sensoresRMC` |

**Nota (cambio en curso en tu árbol de trabajo, sin commit):** al terminar vi que `routes/web.php` ya tiene una ruta nueva `GET /practica4/graficosRMC` (`practica4.graficosRMC`, Práctica 4 Bloque 4, al final del fichero) con la vista `resources/views/practica4/graficosRMC.blade.php`, y que el menú tiene la entrada «Gráficos RMC». No la he tocado; la tabla de arriba es del momento de la lectura y no la incluye.

**Solo se ve ejecutando:** qué responde `/miPortal/acerca`. Por la vista que falta cabe esperar un error 500 «View not found» (probable, sin verificar).

### F4. Sitemap o listado de páginas

**Verificado en el código:** no hay `sitemap.xml` ni ruta o vista llamada sitemap (`public/` solo tiene `robots.txt`, `index.php`, `Public.html`, `*Publico.html`, `css`, `img`, `js` y `misPhp`). El único listado de páginas es el menú `resources/views/miPortal/partials/header.blade.php` (l.2-17): Home (`/miPortal/home`), El equipo (`/elGrupo`), Productos, Puestos, Productores, Acerca de (`/miPortal/acerca`) y el desplegable Práctica4 (Básico RMC, Sensores RMC y, añadido mientras redactaba esto, Gráficos RMC). Además, `public/misPhp/menuArchivos*.php` lista archivos del disco (no rutas).

Comparación con las rutas:
- En el menú y en rutas: `/miPortal/home`, `/elGrupo`, `/productos`, `/puestos`, `/productores`, `/miPortal/acerca` (ruta sí, **vista no**), `/practica4/basicoRMC`, `/practica4/sensoresRMC`.
- En rutas pero no en el menú: `/`, `/miPortal`, `/Ricardo|Rosalinda|Adrian`, `/*Privado`, `/paginaPersonal/{nombre}`, las fichas `/productos/{producto}`, `/puestos/{vendedor}` y `/productores/{productor}` (se llega desde las listas) y las rutas de gestión (se llega desde `puestos.show`, l.33).

### F5. Qué hace el controlador principal de cada caso de uso

**NO VERIFICADO — numeración:** el código solo etiqueta el «Caso de uso 4» (comentario en `GestionPuestoController.php:9`). Los casos 1, 2 y 3 no están numerados en el código; los asigno por el orden de las rutas del Bloque 4, así que la correspondencia 1 = productos, 2 = puestos, 3 = productores es una suposición. Confírmala con el enunciado.

**Verificado en el código**
1. **Productos — `ProductoController`:** `index` pide todos los productos con Eloquent ordenados por nombre y los pasa a la vista; `show` recibe el producto por *route model binding* (404 si el id no existe) y muestra su ficha, con los puestos que lo venden.
2. **Puestos — `VendedorController`:** `index` lista los vendedores ordenados por `nombre_puesto`; `show` muestra un puesto con sus productos, precios y stock a través de la relación `productos()`.
3. **Productores — `ProductorController`:** `index` lista los productores y `show` muestra la ficha de uno con lo que tiene en su finca (relación `productos()` con el stock del pivote).
4. **Gestión del puesto — `GestionPuestoController`:** permite ver los productos de un puesto, añadir uno del catálogo, editar precios, stock y descuento, y quitarlo, validando con las reglas de la l.14-20 y usando `attach`, `updateExistingPivot` y `detach` sobre `tienda_vendedores`. El puesto se elige por la URL (comentario l.10: cuando haya login será el del vendedor conectado).

---

## G. Bloque 5 – API de sensores

### G1. Código completo de `app/Http/Controllers/Api/SensorController.php`

**Verificado en el código**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index()
    {
        return Sensor::all();
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'vendedor_id'        => 'required|exists:vendedores,id',
            'codigo_dispositivo' => 'required|string|max:100|unique:sensores,codigo_dispositivo',
            'tipo'               => 'required|in:temperatura,humedad',
            'umbral_min'         => 'required|numeric',
            'umbral_max'         => 'required|numeric|gt:umbral_min',
        ]);

        return response()->json(Sensor::create($datos), 201);
    }

    public function show(Sensor $sensor)
    {
        return $sensor;
    }

    public function update(Request $request, Sensor $sensor)
    {
        $datos = $request->validate([
            'umbral_min' => 'sometimes|numeric',
            'umbral_max' => 'sometimes|numeric',
        ]);

        $sensor->update($datos);
        return $sensor;
    }

    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        return response()->noContent(); // 204
    }

    // Endpoint extra: registrar una lectura y devolver si hay alerta (regla R6)
    public function lectura(Request $request, Sensor $sensor)
    {
        $datos = $request->validate(['valor' => 'required|numeric']);

        $sensor->update(['valor_actual' => $datos['valor']]);

        $alerta = $sensor->valor_actual < $sensor->umbral_min
               || $sensor->valor_actual > $sensor->umbral_max;

        return response()->json([
            'sensor'  => $sensor->codigo_dispositivo,
            'valor'   => $sensor->valor_actual,
            'alerta'  => $alerta,
        ]);
    }
}
```

### G2. Qué hace cada método

**Verificado en el código** (`Api/SensorController.php`)
- `index()` (l.11-14): devuelve todos los sensores (`Sensor::all()`) como JSON.
- `store(Request)` (l.16-27): valida `vendedor_id` (obligatorio, existente en `vendedores`), `codigo_dispositivo` (obligatorio, texto ≤100, único en `sensores`), `tipo` (`temperatura` o `humedad`), `umbral_min` (numérico) y `umbral_max` (numérico y mayor que `umbral_min`). Crea el sensor y responde `201` con el sensor creado. Si la validación falla, Laravel responde `422` con los errores (en JSON por `bootstrap/app.php:19-21`).
- `show(Sensor $sensor)` (l.29-32): devuelve un sensor por id (404 si no existe, por *route model binding*).
- `update(Request, Sensor)` (l.34-43): valida `umbral_min` y `umbral_max` como opcionales (`sometimes`) y numéricos, actualiza y devuelve el sensor. **No** comprueba aquí que `umbral_min < umbral_max` ni permite cambiar otros campos.
- `destroy(Sensor)` (l.45-49): borra el sensor y responde `204 No Content`.
- **`lectura(Request, Sensor)` (l.51-66): SÍ existe.**
  - Recibe: el campo `valor` en el cuerpo de la petición y el sensor por la URL.
  - Valida: `valor` obligatorio y numérico.
  - Guarda: `valor_actual` del sensor (`$sensor->update(['valor_actual' => …])`, l.56).
  - Calcula `alerta` = el valor es menor que `umbral_min` **o** mayor que `umbral_max` (l.58-59).
  - Devuelve JSON con `sensor` (el `codigo_dispositivo`), `valor` (el `valor_actual`) y `alerta` (true/false). El comentario de l.51 lo relaciona con la «regla R6».
  - No guarda historial de lecturas: solo sobrescribe `valor_actual`.

**Probable (sin verificar):**
- Como `valor_actual` tiene cast `decimal:2` (`Sensor.php:16`), el JSON probablemente devuelve `valor` como texto (`"9.20"`) y no como número; solo se confirma ejecutando.
- En MySQL/MariaDB, un `update` que deje `umbral_min >= umbral_max` violaría el CHECK de la base y daría error 500; en SQLite (donde los CHECK no se crean) se guardaría sin error.

### G3. `routes/api.php`

**Verificado en el código**

```php
<?php

use App\Http\Controllers\Api\SensorController;
use Illuminate\Support\Facades\Route;

Route::apiResource('sensores', SensorController::class)->parameters([
    'sensores' => 'sensor'
]);

Route::post('sensores/{sensor}/lectura', [SensorController::class, 'lectura']);
```

- **Usa `Route::apiResource`** (l.6), con `->parameters(['sensores' => 'sensor'])` para que el parámetro se llame `sensor` (si no, Laravel esperaría `{sensore}` por el plural en español) y poder usar *route model binding*.
- La ruta de lectura **está escrita aparte, a mano** (l.10): `POST sensores/{sensor}/lectura`.
- `php artisan route:list` confirma (prefijo `api/` por `bootstrap/app.php:11`): `GET|POST api/sensores`, `GET|PUT|PATCH|DELETE api/sensores/{sensor}` y `POST api/sensores/{sensor}/lectura`.

### G4. Comandos de Artisan para la API

**NO VERIFICADO** (el código no guarda los comandos). **Verificado (pistas):**
- Existen `routes/api.php` y `bootstrap/app.php:11` registra `api:`; `composer.json:11` requiere `laravel/sanctum`; existe `config/sanctum.php`; hay la migración `2026_10_28_090648_create_personal_access_tokens_table.php` y la ruta `sanctum/csrf-cookie` aparece en `route:list`. Eso es lo que deja `php artisan install:api`.
- `SensorController` está en el namespace `App\Http\Controllers\Api` y tiene `index, store, show, update, destroy` (más `lectura`), con `Sensor $sensor` tipado en `show/update/destroy`: es la forma típica de `make:controller --api --model=Sensor`.
- **Sí tiene el modelo `Sensor` asociado:** `use App\Models\Sensor;` (l.6) y los métodos reciben `Sensor $sensor`.

**Probable (sin verificar):**
- `php artisan install:api` (crea `routes/api.php`, instala Sanctum y la migración de tokens).
- `php artisan make:controller Api/SensorController --api --model=Sensor`, que genera los 5 métodos con el modelo inyectado; el método `lectura` y las validaciones se añadieron a mano.
- El historial de git no guarda los comandos, solo el commit «Bloque 5: API REST de sensores terminada» (`9eadbd3`).

---

## H. Lo que solo se ve con la aplicación en marcha

### H1. Capturas que faltan

**NO VERIFICADO** (requiere ejecutar la aplicación; no lo he hecho y no invento resultados).
1. **ejecutarArchivo sin parámetro:** abrir directamente `http://pweb26.test/misPhp/ejecutarArchivo.php` (o `ejecutarArchivo2.php`) en el navegador → debería verse «No existe ninguna orden a ejecutar».
2. **ejecutarArchivo con parámetro:** abrir `menuArchivos.php` (o `menuArchivos2.php`), elegir una opción y pulsar «Ejecutar» → «La orden a ejecutar es …».
3. **Menú de archivos** (`menuArchivos2.php`) con la lista y el desplegable.
4. **Vistas del Bloque 4:** `/productos`, `/productos/1`, `/puestos`, `/puestos/1`, `/productores`, `/productores/1` y la gestión `/puestos/1/gestion` (añadir, editar, quitar y un error de validación). Ojo: si no se ejecutaron `AlmacenProductoresSeeder` y `TiendaVendedoresSeeder` (ver D1), las fichas saldrán sin productos.
5. **API (Postman o curl):** GET lista, GET uno, POST crear, PUT actualizar, DELETE y POST `lectura` (un caso con `alerta: true`, otro con `false` y un `422` de validación).
6. **Evitar:** `/miPortal/acerca` (falta la vista) salvo que la crees.

### H2. Comandos curl para la API

Los datos de ejemplo salen de `database/seeders/sql/sensores.sql`: sensor 1 `SN-HV-001` (temperatura, umbrales 2-8, valor 6.50, vendedor 1), sensor 3 `SN-LQ-001` (temperatura, umbrales 2-8, valor 9.20, ya en alerta, vendedor 2) y sensor 4 `SN-LQ-002` (humedad, 70-90, sin lectura). Hay 3 vendedores (ids 1-3). La URL base de los comentarios del proyecto es `http://pweb26.test` (en tu práctica 3 mencionaste `pweb25.test`; ajusta al host de tu máquina). Se añade `Accept: application/json` para que los errores 404/422 salgan en JSON.

```bash
BASE=http://pweb26.test/api

# 1) Listar todos (index) -> 200 + array con los sensores
curl -i -H "Accept: application/json" $BASE/sensores

# 2) Ver uno (show) -> 200
curl -i -H "Accept: application/json" $BASE/sensores/1

# 2b) Uno que no existe -> 404
curl -i -H "Accept: application/json" $BASE/sensores/999

# 3) Crear (store) -> 201
curl -i -X POST $BASE/sensores \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"vendedor_id":3,"codigo_dispositivo":"SN-PN-002","tipo":"humedad","umbral_min":40,"umbral_max":80}'

# 3b) Crear con datos inválidos (umbral_max menor que umbral_min) -> 422
curl -i -X POST $BASE/sensores \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"vendedor_id":3,"codigo_dispositivo":"SN-PN-003","tipo":"humedad","umbral_min":80,"umbral_max":40}'

# 3c) Crear con código repetido (ya existe SN-HV-001) -> 422
curl -i -X POST $BASE/sensores \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"vendedor_id":1,"codigo_dispositivo":"SN-HV-001","tipo":"temperatura","umbral_min":2,"umbral_max":8}'

# 4) Actualizar umbrales (update) -> 200
curl -i -X PUT $BASE/sensores/1 \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"umbral_min":3,"umbral_max":9}'

# 5) Lectura sin alerta (sensor 1, rango 3-9 tras el paso 4) -> alerta:false
curl -i -X POST $BASE/sensores/1/lectura \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"valor":6.5}'

# 5b) Lectura con alerta (supera el máximo) -> alerta:true
curl -i -X POST $BASE/sensores/1/lectura \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"valor":12}'

# 5c) Lectura en el sensor 4 (humedad, 70-90, sin lectura todavía) -> alerta:false
curl -i -X POST $BASE/sensores/4/lectura \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"valor":75}'

# 5d) Lectura inválida (sin campo valor) -> 422
curl -i -X POST $BASE/sensores/1/lectura \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{}'

# 6) Borrar (destroy) el sensor creado en el paso 3 (id 6 si la tabla tenía 5) -> 204
curl -i -X DELETE -H "Accept: application/json" $BASE/sensores/6
```

Notas:
- En Windows, con `curl.exe` y PowerShell las comillas del `-d` cambian; en Git Bash funcionan tal cual.
- El `id` del sensor creado en el paso 3 depende del estado real de la tabla; se ve en la respuesta del POST.
- Estos comandos **modifican la base de datos** (crean, cambian y borran filas). **NO VERIFICADO:** no los he ejecutado; los códigos HTTP indicados son los que se deducen del código, no resultados observados.

---

## Qué confirmar a mano antes de entregar

1. **Seeders (D1):** comprueba si en tu base local están las 84 filas. `DatabaseSeeder` solo inserta 55; si ejecutaste `php artisan migrate:fresh --seed`, `almacen_productores` y `tienda_vendedores` están vacías. Decide si añades esos dos seeders a `DatabaseSeeder` o lo dejas como está y lo explicas en la memoria.
2. **Base de datos usada (C3):** `.env` dice `sqlite`; los CHECK no existen en SQLite. Confirma con qué motor vas a entregar y demostrar, y no afirmes los CHECK si es SQLite.
3. **Versión del menú (A1):** decide si la memoria describe `menuArchivos2.php` (cumple la regla con `switch`) o `menuArchivos.php` (enlaza `favicon.ico`, `index.php`, `robots.txt` y `Public.html`, enlaza archivos de `practica` sin filtrar y tiene `//` visibles en el HTML).
4. **Vista que falta (F2/F3):** `miPortal/acerca.blade.php` no existe; abre `/miPortal/acerca` para ver si da error y créala o quita el enlace antes de capturar pantallas.
5. **Numeración de los casos de uso (F5):** el código solo marca el caso 4; confirma con tu enunciado qué controlador es el caso 1, 2 y 3.
6. **Comandos de Artisan (E2, G4):** no están guardados en ningún sitio; confirma los que usaste realmente (`make:model`, `make:migration`, `install:api`, `make:controller Api/SensorController --api --model=Sensor`).
7. **Capturas y pruebas de API (H1, H2):** hay que ejecutar la aplicación para sacarlas; no he comprobado respuestas ni códigos HTTP reales.
8. **Dominio:** el código usa `pweb26.test` (p. ej. `menuArchivos.php:22`, comentarios de `web.php`); tú mencionaste `pweb25.test`. Usa el que corresponda a tu máquina.
