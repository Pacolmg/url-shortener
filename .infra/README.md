# Entorno de desarrollo local con Docker

## Estructura del entorno

* ```.infra``` Carpeta con todo lo necesario para utilizar el entorno Docker
* ```.infra/docker/docker-compose.yml``` Archivo de definición Docker Compose. Aquí se definen los servicios que utilizará nuestro entorno Docker y la configuración de la red.
* ```.infra/docker/bin``` Carpeta destinada a los scripts custom de Docker
* ```.infra/docker/build```
  * ```.infra/docker/build/www``` Carpetas con servicios custom, su Dockerfile y sus archivos de configuración.
  * ```.infra/docker/build/data``` Directorio bindeado a la base de datos del contenedor
  * ```.infra/docker/build/logs``` Directorio de logging.
* ```.infra/git``` Carpeta destinada a los git hooks.

## Configurar entorno

```sh
cp .infra/docker/bin/dist.env .infra/docker/bin/.env
nano .infra/docker/bin/.env
```
A continuación se explica para qué se utiliza cada variable de entorno:
* ```COMPOSE_PROJECT_NAME``` Variable nativa de Docker Compose para identificar el proyecto.
* ```APP_DIR``` Variable por defecto de la ruta del proyecto con respecto al docker-compose.yml. (NO CAMBIAR)
* ```[PROTOCOLO]_PORT``` Variables que definen el puerto con el que la máquina anfitriona accederá al entorno Docker. Si se quieren levantar N entornos de proyecto Docker simultáneamente, estas variables tienen que ser diferentes en los .env de cada proyecto.
* ```DB_PROVIDER``` Variable que define con qué DB trabajar. Actualmente únicamente se ha probado con ```mysql``` o ```mariadb```. 
* ```DB_VERSION``` Variables que define la versión de la DB definida en la variable anterior.
* ```DB_ROOT_PASSWORD``` Contraseña del usuario ROOT.
* ```DB_USER``` Usuario con privilegios sobre la base de datos del proyecto.
* ```DB_PASSWORD``` Contraseña del usuario con privilegios sobre la base de datos del proyecto.
* ```DB_NAME``` Nombre de la base de datos del proyecto.

## Levantar entorno

```sh
# Crear los enlaces simbólicos
sh .infra/docker/docker-symlinks-up

# Levantar el entorno Docker
sh dup

```

## Bajar entorno

```sh
# Tumbar el entorno Docker
sh ddown

# Eliminar los enlaces simbólicos
sh .infra/docker/docker-symlinks-down
```

## Comandos personalizados

Se han creado una serie de comandos personalizados que nos permiten ejecutar desde la raíz del proyecto los comandos Docker más utilizados. A continuación una lista con los comandos que utiliza este proyecto:

* Crear los enlaces simbólicos en la raíz del proyecto
```sh
sh .infra/docker/bin/docker-symlinks-up
``` 
* Borrar los enlaces simbólicos de la raíz del proyecto
```sh
sh .infra/docker/bin/docker-symlinks-down
```
* Levantar Docker
```sh
sh dup
```
* Tumbar Docker
```sh
sh ddown
```
* Iniciar Docker (Sólo si los contenedores están levantados y detenidos)
```sh
sh dstart
```
* Detener Docker (Detiene los contenedores Docker sin eliminarlos)
```sh
sh dstop
```
* Resetear Docker forzando compilación
```sh
sh dreset
```
* Ejecutar cualquier comando en el servicio www con usuario www-data
```sh
sh dexec [COMANDO] [ARGS]
```
* Ejecutar cualquier comando de Composer en el servicio www
```sh
sh dcomposer [COMANDO] [ARGS]
```
* Ejecutar cualquier comando de la consola de Symfony en el servicio www
```sh
sh dsymfony [COMANDO] [ARGS]
```
* Ejecutar cualquier comando de Yarn en el servicio www
```sh
sh yarn [COMANDO] [ARGS]
```
* Ejecutar Webpack Encore en el servicio www
```sh
sh dencore [ARGS]
```

