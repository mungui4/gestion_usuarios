***API de Gestión de Usuarios***

**Autenticación**

*Registro de Usuario*
- URL: /api/register
- Método: POST
- Descripción: Registra un nuevo usuario con rol "user".
- Cuerpo de la solicitud:
  {
    "name": "string",
    "last_name": "string",
    "address": "string",
    "email": "string",
    "phone": "string",
    "password": "string"
  }
- Respuesta exitosa:
  {
    "message": "Usuario registrado con éxito",
    "data": { ... }
  }

*Inicio de Sesión*
- URL: /api/login
- Método: POST
- Descripción: Inicia sesión para usuarios con rol "user" y "admin".
- Cuerpo de la solicitud:
  {
    "email": "string",
    "password": "string"
  }
- Respuesta exitosa:
  {
    "token": "string"
  }

**Rutas Protegidas**

*Obtener Perfil*
- URL: /api/profile
- Método: GET
- Descripción: Muestra los datos del usuario que ha iniciado sesión.
- Autenticación: Bearer Token
- Respuesta exitosa:
  {
    "id": "integer",
    "name": "string",
    "last_name": "string",
    "address": "string",
    "email": "string",
    "phone": "string",
    "role": "string"
  }

*Actualizar Usuario*
- URL: /api/update/{id}
- Método: PUT
- Descripción: Actualiza la información de un usuario.
- Autenticación: Bearer Token
- Cuerpo de la solicitud:
  {
    "name": "string",
    "last_name": "string",
    "address": "string",
    "email": "string",
    "phone": "string",
    "password": "string"
  }
- Respuesta exitosa:
  {
    "message": "Actualizado con éxito",
    "data": { ... }
  }

*Eliminar Usuario*
- URL: /api/delete/{id}
- Método: DELETE
- Descripción: Elimina un usuario. Solo los administradores pueden realizar esta acción y no pueden eliminar a otros administradores.
- Autenticación: Bearer Token
- Respuesta exitosa:
  {
    "message": "Usuario eliminado"
  }

*Usuarios por Fecha*
- URL: /api/users/by-date
- Método: GET
- Descripción: Muestra los usuarios registrados en una fecha específica.
- Autenticación: Bearer Token
- Parámetros de consulta:
  - date: Fecha en formato YYYY-MM-DD
- Respuesta exitosa:
  {
    "message": "Usuarios registrados en la fecha: YYYY-MM-DD",
    "data": [ ... ]
  }

*Usuarios por Rango de Fechas*
- URL: /api/users/by-dates
- Método: GET
- Descripción: Muestra los usuarios registrados en un rango de fechas.
- Autenticación: Bearer Token
- Parámetros de consulta:
  - date1: Fecha de inicio en formato YYYY-MM-DD
  - date2: Fecha de fin en formato YYYY-MM-DD
- Respuesta exitosa:
  {
    "message": "Usuarios registrados entre las fechas: YYYY-MM-DD y YYYY-MM-DD",
    "data": [ ... ]
  }

*Refrescar Token*
- URL: /api/refresh-token
- Método: POST
- Descripción: Genera y retorna un token actualizado.
- Autenticación: Bearer Token
- Respuesta exitosa:
  {
    "token": "string"
  }

**Errores**

*Formato de Fecha Incorrecto*
- Código de estado: 400
- Respuesta:
  {
    "message": "Formato de fecha incorrecto"
  }

*No Autorizado*
- Código de estado: 401
- Respuesta:
  {
    "message": "No tienes permisos para realizar esta acción"
  }

*No Encontrado*
- Código de estado: 404
- Respuesta:
  {
    "message": "No encontrado"
  }

*Prohibido*
- Código de estado: 403
- Respuesta:
  {
    "message": "No puedes eliminar a otro administrador"
  }

**Notas**
- *Todas las rutas protegidas requieren autenticación mediante un token Bearer.*
- *Asegúrate de enviar las fechas en el formato YYYY-MM-DD para las rutas que lo requieran.*
