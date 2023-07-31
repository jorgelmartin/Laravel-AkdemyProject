## Backend - AkdemyProject

### Tecnologías utilizadas:

<div align="center">

![PHP](https://img.shields.io/badge/php-%23232D2F.svg?style=for-the-badge&logo=php&logoColor=white) ![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white) ![MySQL Workbench](https://img.shields.io/badge/mysql%20workbench-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white) ![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)

</div>

### Sobre el proyecto

El proyecto AkdemyProject es una aplicación que ofrece diferentes programas y convocatorias a sus usuarios. Los usuarios pueden registrarse en la academia y acceder a una variedad de funcionalidades según su rol. Los administradores tienen privilegios adicionales para gestionar convocatorias y solicitudes de inscripción.

Las funcionalidades disponibles para los usuarios en la plataforma AkdemyProject son diversas y están diseñadas para brindar una experiencia completa y enriquecedora. Una vez registrados en la academia, los usuarios tendrán acceso a su propio perfil, donde podrán visualizar y editar su información personal. Además, podrán explorar todos los programas académicos ofrecidos por la institución, obtener detalles sobre cada uno de ellos y tomar decisiones informadas sobre sus intereses educativos.

Una de las características destacadas para los usuarios es la posibilidad de ver todas las convocatorias vigentes, lo que les permitirá estar al tanto de las oportunidades disponibles para inscribirse en los programas de su elección. Los usuarios podrán realizar solicitudes de inscripción a las convocatorias deseadas y, una vez enviadas, recibirán un correo electrónico con la documentación necesaria para completar el proceso de inscripción. Además, serán notificados cuando su solicitud haya sido revisada y aprobada, brindándoles una comunicación efectiva y transparente durante todo el proceso. Los usuarios también podrán rastrear el estado de sus solicitudes en su perfil personal dentro de la plataforma.

Además, los usuarios tendrán un historial completo de sus convocatorias aceptadas, lo que les permitirá mantener un seguimiento detallado de su progreso académico y su participación en los diferentes programas. También tendrán la opción de eliminar su cuenta si así lo desean.

En resumen, AkdemyProject ofrece a sus usuarios la posibilidad de aprovechar al máximo las oportunidades educativas que la academia proporciona, con un enfoque en la accesibilidad, la transparencia y la facilidad de uso. Los usuarios podrán gestionar su propia trayectoria académica y explorar una diversidad de programas y convocatorias que enriquecerán su desarrollo educativo y profesional.


!['diagrama'](./public/images/diagram.png)

### Instalación en local
1. Clona el repositorio `$git clone 'url-repository'`
2. Instala las dependencias `composer install`
3. Crea el archivo `.env` y configura la base de datos
4. Ejecuta las migraciones y seeders `php artisan migrate` `php artisan db:seed`
5. Conectamos el servidor`php artisan serve`

### Endpoints

#### Autenticación

* POST - Registro
* POST - Login

#### Usuarios

* GET - Perfil.
* GET - Ver todos los usuarios (Admin).
* PUT - Editar perfil.
* DELETE - Borrar cuenta.

#### Convocatorias

* GET - Ver todas las convocatorias.
* POST - Crear convocatorias(admin)
* PUT - Modificar convocatorias(admin).

#### Usuario convocatorias

* POST - Crear solicitud inscripción(user).
* GET - Ver solicitudes pendientes(admin).
* POST - Aceptar solicitudes pendientes(admin).
* GET - Ver solicitudes aceptadas(user).

#### Programas

* GET - Ver todos los programas.