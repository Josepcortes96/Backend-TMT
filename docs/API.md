# 📘 Documentación de Rutas de la API

Versión base: `/api/v1`  
Formato de respuesta: `application/json`  
Autenticación: Algunas rutas requieren JWT en el encabezado:

```http
Authorization: Bearer <token>
```

---

## 👤 Usuarios

**Grupo**: `/api/v1/usuarios`  
**Middleware**: `JwtMiddleware` (requerido)

| Método | Ruta                                | Descripción                                               |
|--------|-------------------------------------|-----------------------------------------------------------|
| GET    | `/api/v1/usuarios`                  | Obtiene todos los usuarios.                              |
| GET    | `/api/v1/usuarios/centro`           | Lista usuarios filtrados por centro.                     |
| POST   | `/api/v1/usuarios`                  | Crea un nuevo usuario.                                   |
| GET    | `/api/v1/usuarios/{id}`             | Obtiene un usuario por su ID.                            |
| GET    | `/api/v1/usuarios/nombre/{nombre}`  | Busca usuarios por nombre.                               |
| PUT    | `/api/v1/usuarios/{id}`             | Actualiza un usuario existente.                          |
| DELETE | `/api/v1/usuarios/{id}`             | Elimina un usuario.                                      |
| PATCH  | `/api/v1/usuarios/{id}/inactivar`   | Inactiva (desactiva) un usuario sin eliminarlo.          |

---

## 🏢 Centros

**Grupo**: `/api/v1/centros`

| Método | Ruta                   | Descripción                               |
|--------|------------------------|-------------------------------------------|
| POST   | `/api/v1/centros`      | Crea un nuevo centro.                     |
| GET    | `/api/v1/centros`      | Obtiene todos los centros registrados.    |
| PUT    | `/api/v1/centros/{id}` | Actualiza los datos de un centro por ID.  |
| DELETE | `/api/v1/centros/{id}` | Elimina un centro por ID.                 |

---

## 🍽️ Comidas

**Grupo**: `/api/v1/comidas`

| Método | Ruta                                  | Descripción                                       |
|--------|---------------------------------------|--------------------------------------------------|
| POST   | `/api/v1/comidas`                     | Crea una nueva comida con alimentos.             |
| POST   | `/api/v1/comidas/agregar-alimento`    | Agrega un alimento a una comida existente.       |

---

## 🥗 Dietas

**Grupo**: `/api/v1/dietas`  
**Middleware**: `JwtMiddleware` (requerido)

| Método | Ruta                                | Descripción                                           |
|--------|-------------------------------------|-------------------------------------------------------|
| POST   | `/api/v1/dietas`                    | Crea una nueva dieta con macros.                     |
| POST   | `/api/v1/dietas/asociar-comidas`    | Asocia comidas a una dieta.                          |
| PUT    | `/api/v1/dietas/{id}`               | Actualiza los datos (macros) de una dieta.           |
| DELETE | `/api/v1/dietas/{id}`               | Elimina una dieta por su ID.                         |
| GET    | `/api/v1/dietas`                    | Lista todas las dietas registradas.                  |
| GET    | `/api/v1/dietas/{id}`               | Obtiene una dieta específica por su ID.              |

---

## 📊 Datos de Control

**Grupo**: `/api/v1/datos`  
**Middleware**: `JwtMiddleware` (requerido)

| Método | Ruta                                               | Descripción                                           |
|--------|----------------------------------------------------|-------------------------------------------------------|
| POST   | `/api/v1/datos`                                    | Crea un nuevo registro de control.                   |
| GET    | `/api/v1/datos/ultimos/{id_usuario}`               | Obtiene los últimos controles del usuario.           |
| GET    | `/api/v1/datos/last/{id_usuario}`                  | Obtiene el último control registrado del usuario.    |
| GET    | `/api/v1/datos/usuario/{id_usuario}`               | Lista todos los controles del usuario.               |
| GET    | `/api/v1/datos/usuario/{id_usuario}/control/{nombre}` | Obtiene un control específico por nombre.         |
| GET    | `/api/v1/datos/detalle/{id}`                       | Obtiene los detalles de un control por su ID.        |
| PUT    | `/api/v1/datos/{id}`                               | Actualiza un control existente.                      |
| DELETE | `/api/v1/datos/{id}`                               | Elimina un control por su ID.                        |

---

## ⚖️ Equivalencias

**Grupo**: `/api/v1/equivalencias`

| Método | Ruta                          | Descripción                                 |
|--------|-------------------------------|---------------------------------------------|
| GET    | `/api/v1/equivalencias/calcular` | Calcula equivalencias de alimentos.       |

---

## 🔐 Autenticación

**Grupo**: `/api/v1/auth`

| Método | Ruta                   | Descripción                          |
|--------|------------------------|--------------------------------------|
| POST   | `/api/v1/auth/login`   | Inicia sesión y genera un token JWT. |
| GET    | `/api/v1/auth/check`   | Verifica validez del token JWT.      |

---

## 🛡️ Seguridad

Las rutas que requieren autenticación están protegidas con `JwtMiddleware`. Asegúrate de enviar el token en cada request protegido:

```http
Authorization: Bearer <token>
```

---

## 📎 Notas Finales

- Todas las rutas están organizadas bajo controladores dedicados.
- Se utiliza inyección de dependencias y buenas prácticas RESTful.
- El sistema usa versionado semántico (`/api/v1`) para escalabilidad.
