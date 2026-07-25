# NUESTRA BASE PARA LA CONSTRUCCIÓN DE LA IMAGEN SERÁ LA IMAGEN OFICIAL DE PHP
FROM php:8.3-fpm

# EJECUTAMOS COMANDOS LINUX CUYO PROPOSITO ES OBTENER DE EL ADMINISTRADOR "APT"
# EL LISTADO ACTUALIZADO DE LOS PROGRAMAS DISPONIBLES "apt-get update"
# Y SI ESTE LOGRA EJECUTARSE "apt-get update &&" ENTONCES LE DECIMOS 
# QUE QUEREMOS INSTALARLO SEGUIDO A ESTO, INSTALAMOS GIT, UNZIP Y ZIP
# ACEPTANDO AUTOMATICAMENTE CUALQUIERO CONFIRMACIÓN "-y"
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip
# ESTE ES UN SCRIPT QUE VIENE INCLUIDO EN LA IMAGEN DE PHP
# EJECUTA E INSTALA EXTENSIONES DE PHP, EN ESTE CASO PDO Y PDO_MYSQL
RUN docker-php-ext-install pdo pdo_mysql

# Lo que hacemos es ir e a la imagen composer:2, 
# toma el archivo /usr/bin/composer y 
# cópialo al directorio /usr/bin de mi imagen con el mismo nombre.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Le indicamos a Docker que, a partir de este momento,
# esta será la carpeta principal donde trabajará nuestro proyecto.
# Como analogía, puedes pensar en la carpeta htdocs de XAMPP,
# ya que aquí será donde estará ubicado nuestro proyecto.
WORKDIR /var/www

COPY . .

# Indica qué debe hacer el contenedor cuando se inicie.
# En nuestro caso, iniciar PHP para que quede listo
# para ejecutar nuestra aplicación.
CMD ["php-fpm"]