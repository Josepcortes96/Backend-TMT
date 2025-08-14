# TMT-API

Backend  construido  para tmtgroup utilizando **Vanilla PHP 8.3**, este backend consiste en la creación de una API para la intranet del grupo. Donde se pueden crear dietas y realizar controles de seguimiento para cada usuario.

<img width="1809" height="919" alt="image" src="https://github.com/user-attachments/assets/5a0901b2-386e-4866-9b7a-ec4a1e2125e8" />


## 🚀 Tecnologías utilizadas

- **PHP 8.3**
- **Slim Framework 4**
- **PHP-DI** 
- **Slim PSR-7**
- **Firebase PHP-JWT** 
- **vlucas/phpdotenv** 

## 📁 Estructura del proyecto
```
api
├── composer.json
├── public/
│   └── index.php         
├── src/                 
│ └── Repositories/
        └── Interfaces/
  └──  Services/
        └── Interfaces/
  └── Routes/
  └── Models/
  └── Controllers/
├── .env                 
└── vendor/               
```

## ⚙️ Instalación

1. Clona el repositorio:

2. Instala las dependencias con Composer:

3. Crea tu archivo `.env` con tus variables de configuración:

4. Inicia un servidor de desarrollo:

## 🧠 Autoload (PSR-4)

Se utiliza autoloading basado en **PSR-4** para mapear el espacio de nombres `App\` al directorio `src/`.


## 🧪 Requisitos

- PHP 8.3 o superior
- Composer


## 🔐 Seguridad

Todos los endpoints de este grupo están protegidos con middleware `JwtMiddleware`, por lo tanto es obligatorio enviar un token válido en el encabezado:

```http
Authorization: Bearer <token>

## 📜 Licencia


