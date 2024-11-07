## Sistema

Guía para la configuracion de procesos y/o servicios.

## Características

- CRUD de Tareas: Crea, consulta, actualiza y elimina tareas.
- Validación: Validación de datos en cada endpoint.
- Documentación de API: Documentación automática de la API con Swagger UI y ReDoc.
- Formato de Respuestas: Todas las respuestas se proporcionan en formato JSON.

## Requisitos Previos

Asegúrate de tener instalados los siguientes elementos:

- PHP >= 7.4
- Composer
- MySQL u otro motor de base de datos compatible
- Node.js y npm (opcional, si usas Vue.js o algún front-end)

## Instalación

- Instalar dependencias de Laravel con "composer install"
- Configurar variables de entorno
    - Hay un ejemplo de este archivo en `.env.sample`, sacar copia y nombrarlo `.env` y
      configurarlo.
    - Completar las constantes de base de datos y otros.
- Importar estructura de base de datos
    - La última base de datos se encuentra en **/database/[nombre_y_timestamp].sql**
- Descripcion y uso de Endpoints
    - Obtener todas las tareas: 
     - Método: GET
     - Endpoint: /api/tasks
     - Descripción: Devuelve una lista de todas las tareas.
    - Crear una nueva tarea: 
     - Método: POST
     - Endpoint: /api/tasks
     - Descripción: Crea una nueva tarea con los datos enviados en el cuerpo de la solicitud.
    - Obtener una tarea específica: 
     - Método: GET
     - Endpoint: /api/tasks/{id}
     - Descripción: Devuelve los detalles de una tarea específica.
    - Actualizar una tarea existente: 
     - Método: PUT
     - Endpoint: /api/tasks/{id}
     - Descripción: Actualiza una tarea específica con los datos enviados en el cuerpo de la solicitud.
    - Eliminar una tarea: 
     - Método: DELETE
     - Endpoint: /api/tasks/{id}
     - Descripción: Elimina una tarea específica de la base de datos.
- Iniciar el servicio de Apache y MySQL con Xampp.
- Iniciar el servidor de desarrollo.
- Iniciar Postman para realizar las pruebas correspondientes. También podrás realizarlas por Swagger <<URL:port/api/documentation>> o ReDoc <<URL:port/api/redoc>>.