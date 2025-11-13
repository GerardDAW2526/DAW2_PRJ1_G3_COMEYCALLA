# Proyecto Come & Calla

## Descripción

**Come & Calla** es una aplicación web diseñada para optimizar la gestión de mesas en un restaurante. Permite a los camareros registrar la ocupación de mesas en diferentes salas, consultar su estado en tiempo real y mantener un historial de uso.  
El proyecto busca mejorar la organización interna del restaurante, reducir errores humanos y facilitar la coordinación entre el personal de sala.

---

## Funcionalidades principales

- Inicio de sesión seguro para camareros.  
- Gestión de salas (terraza, comedor, privada).  
- Asignación y control de mesas según su tipo y estado (libre u ocupada).  
- Registro histórico de ocupaciones, indicando qué camarero usó cada mesa y cuándo.  
- Liberación de mesas con actualización automática de su estado.  
- Interfaz sencilla y funcional para facilitar el trabajo del personal.

---

## Credenciales de prueba

Puedes acceder al sistema utilizando las siguientes credenciales:

| Usuario | Contraseña |
|----------|-------------|
| jgomez   | 123456      |
| mlopez   | 123456      |
| rcano    | 123456      |

---

## Estructura de la base de datos

**Base de datos:** `db_comeycalla`

### Tablas principales

1. **tbl_usuarios**  
   - `id_usuario` (INT, PK, AI)  
   - `username` (VARCHAR 50, único)  
   - `nombre_completo` (VARCHAR 100)  
   - `password` (VARCHAR 255)

2. **tbl_salas**  
   - `id_sala` (INT, PK, AI)  
   - `nombre_sala` (VARCHAR 50)  
   - `tipo` (ENUM: terraza, comedor, privada)  
   - `capacidad_total` (INT)

3. **tbl_mesas**  
   - `id_mesa` (INT, PK, AI)  
   - `id_sala` (INT, FK → tbl_salas)  
   - `num_sillas` (INT)  
   - `estado` (ENUM: libre, ocupada)  
   - `tipo_mesa` (ENUM: cuadrada, rectangular, redonda, especial)  
   - `descripcion` (VARCHAR 255)

4. **tbl_ocupaciones**  
   - `id_ocupacion` (INT, PK, AI)  
   - `id_mesa` (INT, FK → tbl_mesas)  
   - `id_usuario` (INT, FK → tbl_usuarios)  
   - `fecha_ocupacion` (DATETIME, por defecto `CURRENT_TIMESTAMP`)  
   - `fecha_liberacion` (DATETIME, NULL)

---

## Estructura del equipo

| Miembro | Rol |
|----------|------|
| Gerard | Impulsor |
| Eric | Finalizador |
| Joel | Coordinador |
| Jesús | Cohesionador |

---

## Planificación del proyecto

### Herramientas utilizadas
- Visual Studio Code como entorno de desarrollo.  
- MySQL para la base de datos local.  
- GitHub para control de versiones y colaboración.  

### Metodología de trabajo
- Planificación semanal mediante reuniones y reparto de tareas.  
- Trabajo colaborativo en ramas individuales dentro del repositorio principal.  
- Integración y validación final por parte del finalizador.  
