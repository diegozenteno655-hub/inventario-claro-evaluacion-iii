# Inventario Claro

Sistema web de inventario hecho con Laravel 12, Blade, PHP y MySQL para la Evaluación III de Desarrollo de Aplicaciones Web. Los datos iniciales son ficticios.

## Qué problema resuelve

Una pequeña empresa puede perder el control de sus existencias si lleva productos en hojas dispersas. El encargado de bodega o administrador necesita registrar artículos, corregir sus datos y saber qué productos requieren reposición. Inventario Claro reúne la información en una base MySQL y muestra alertas cuando la cantidad disponible es menor o igual al stock mínimo configurado para cada artículo.

## Requisitos

- PHP 8.2 o superior, con `pdo_mysql`, `mbstring`, `openssl`, `fileinfo` y `xml` habilitados.
- Composer 2 y MySQL (puedes usar MySQL o MariaDB de XAMPP).
- Internet la primera vez para que Composer descargue Laravel. El estilo funciona sin internet con fuentes alternativas.

## Instalación en Windows con XAMPP

1. Descomprime este proyecto, por ejemplo en `C:\xampp\htdocs\InventarioClaro`.
2. Abre el panel de XAMPP e inicia **MySQL**. En `http://localhost/phpmyadmin`, crea una base vacía llamada `inventario_claro` con codificación `utf8mb4`. No importes tablas manualmente.
3. Abre PowerShell en la carpeta del proyecto. Si `php` no se reconoce, ejecuta primero `$env:Path="C:\xampp\php;$env:Path"` en esa ventana. Composer debe estar instalado por separado.
4. Ejecuta los comandos:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

5. Abre `http://127.0.0.1:8000/login`. Si MySQL de XAMPP usa otro puerto o contraseña, edita `DB_PORT` y `DB_PASSWORD` en `.env` antes de migrar.

> Usa `php artisan serve` desde la raíz del proyecto. Para servir con Apache configura DocumentRoot hacia `InventarioClaro/public`, nunca hacia la raíz donde está `.env`.

### Cuenta ficticia

- Correo: `demo@inventarioclaro.test`
- Contraseña: `Demo2026!`

La cuenta y cinco productos de ejemplo se crean con `php artisan migrate --seed`. Cambia la contraseña si publicarás el proyecto en algún servidor; el usuario de muestra está pensado solamente para evaluación local.

## Funciones frente a la pauta

| Criterio | Implementación | Dónde verlo |
| --- | --- | --- |
| Login y logout | Autenticación de Laravel, sesión regenerada, rutas internas protegidas y cierre por POST | `/login`, `/dashboard`, menú lateral |
| Dashboard | Total de productos, unidades, productos con alerta y valor de inventario | `/dashboard` |
| MySQL | Migraciones para `users` y `products`; modelos Eloquent y datos de prueba | phpMyAdmin, tablas `users` y `products` |
| 1 Registrar productos | Formulario con nombre, SKU, categoría, cantidad, precio y mínimo | `/productos/crear` |
| 2 Editar información y stock | Formulario de actualización con validación | Botón **Editar** en `/productos` |
| 3 Detectar stock bajo | Consulta `quantity <= minimum_stock`, etiqueta, filtro y alertas en dashboard | `/productos?filter=low` |

El SKU es único; las cantidades y precios negativos están prohibidos. El valor del inventario se calcula como la suma de `cantidad × precio`.

## Diseño técnico

Flujo: navegador → rutas web → middleware de autenticación → controladores → modelos Eloquent → MySQL → vistas Blade. `AuthController` controla el acceso; `DashboardController` calcula los indicadores; `ProductController` registra, consulta, filtra y edita. Las migraciones definen el esquema; `DatabaseSeeder` incorpora datos ficticios; las vistas y `public/css/app.css` componen la interfaz adaptable a teléfono y escritorio.

## Pruebas y evidencia para la entrega

Ejecuta `php artisan test` con la extensión `pdo_sqlite` habilitada. Las pruebas automatizadas comprueban acceso restringido, login y logout, alta y edición, alertas, validación de SKU repetido y bloqueo de cantidades negativas. La aplicación normal usa MySQL; las pruebas usan SQLite temporal en memoria para evitar modificar los datos de la demostración.

Capturas recomendadas **después de ejecutar el proyecto en tu PC**:

1. Login; intenta entrar a `/dashboard` sin sesión y verifica que vuelva a `/login`.
2. Dashboard con los cuatro indicadores y dos alertas de muestra.
3. Formulario al registrar un producto ficticio y listado donde aparece.
4. Edición del stock del producto y resultado actualizado.
5. Filtro **Stock bajo** antes y después de modificar existencias.
6. phpMyAdmin con los registros de `products`.
7. Terminal con resultado de `php artisan test`.

No se incluyen capturas ni resultados inventados: debes producir esa evidencia en tu instalación. Si trabajas en equipo, completa quién investigó el contexto, quién desarrolló autenticación y datos, quién hizo interfaz y pruebas; todos deben poder explicar el funcionamiento.

## Presentación breve sugerida

> Elegimos gestión de inventario para una pequeña empresa. El encargado necesita registrar productos y detectar a tiempo lo que debe reponer. Construimos Inventario Claro con Laravel, Blade y MySQL. Un usuario autenticado puede registrar artículos, editar sus datos y stock, y ver alertas cuando la cantidad es igual o inferior al mínimo. El dashboard muestra productos, unidades, alertas y valor total. Demostramos el flujo creando un producto, cambiando su cantidad, comprobando el filtro y mostrando el registro en phpMyAdmin.

## Estructura principal

```text
app/Http/Controllers/   Acceso, panel y productos
app/Models/              Usuario y producto
database/migrations/     Esquema MySQL
database/seeders/        Datos ficticios
resources/views/         Vistas Blade
public/css/              Diseño visual
routes/web.php           Rutas y protección
 tests/Feature/           Pruebas automáticas
```
