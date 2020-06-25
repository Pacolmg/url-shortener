Url Shortener - Prueba para Irontec
====================================

A Symfony project created on June 23, 2020, 7:30 pm.

Created by pacolmg@gmail.com

## Requerimientos
Se necesita el siguiente software instalado en la máquina de desarrollo:
- [Docker](https://docs.docker.com/install)
- [Docker-Compose](htps://docs.docker.com/compose/install)
- [PHP-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) (Opcional)

## Aplicación
- La aplicación desarrolla un entorno API cuyo funcionamiento podemos leer [aquí](doc/guide.md) y su colección de Postman se encuentra [aquí](https://raw.githubusercontent.com/Pacolmg/url-shortener/master/doc/url-shortener.postman_collection.json)

## Introducción

- El proyecto está en symfony 5.1 y es un administrador de acortador de url's

## Instalación del Proyecto

Después de hacer el clone hay que ejecutar los siguientes pasos:

1.Crear el fichero .env para el docker:

```sh
cp .infra/docker/dist.env .infra/docker/.env
```

2.Modificar las variables de entorno del docker (.infra/docker/.env). 

- El fichero ya está preparado para trabajar con la carpeta root del proyecto, con lo que no habría que tocarlo:
```code
APP_DIR=../..
``` 
- Usando el puerto 80 para el http:
```code
HTTP_PORT=80
``` 

- Y el 3306 para la base de datos:
```code
DB_PORT=3306
``` 

3.Script para usar los comandos del docker
Desde el directorio raíz ejecutar el script que creará los symlinks que nos facilitarán el trabajo a la hora de usar docker en el proyecto:
```sh
sh .infra/docker/bin/docker-symlinks-up
```
Desde ahora en esta guía se usarán los atajos creados en el paso previo.

4.Levantar docker

```sh
sh dup
```

5.Instalar los paquetes con composer
```sh
sh dcomposer install
```

Ahora ya se podrá acceder al proyecto:
```sh
http://localhost:80
```

**Importante!!! Cambiar el 80 por el puerto que se ha configurado en la variable HTTP_PORT en el .env del docker**

6.Extra: Si actualizas tu fichero /etc/hosts añadiendo esta línea:
```sh
127.0.0.1 url-shortener.test
```
Podrás acceder a través del navegador escribiendo (recuerda el 80 por el puerto que pongas en el .env):
```sh
http://url-shortener.test:80
```

7.Configurar Git (solo si tienes php-cs-fixer instalado): ponemos un hook antes de hacer el commit para comprobar la codificación (pre-commit hook):
```sh
cd .infra/git
cp hooks/pre-commit ../../.git/hooks/pre-commit
chmod a+x ../../.git/hooks/pre-commit
```

8.Base de datos: La crearemos con la consola de symfony con dos comandos (uno para crear la base de datos, otro para 
crear las tablas)
```sh
sh dsymfony doctrine:database:create
sh dsymfony doctrine:schema:update --dump-sql -f
```

Además precargaremos una serie de datos:
```sh
sh dsymfony doctrine:fixtures:load
```



Una vez hecho esto el proyecto debe funcionar correctamente. Podemos acceder a la url `/api/url-shortened/ranking` para comprobarlo

9.El proyecto, es una API, los endpoints para POSTMAN se encuentran en 
este enlace: 
[Colección de Postman de Url Shortener](https://raw.githubusercontent.com/Pacolmg/url-shortener/master/doc/url-shortener.postman_collection.json)

Se habrán cargado diez usuarios con las fixtures "user1", "user2", ... todos con la contraseña "123456".

10.Se pueden crear nuevos usuarios o actualizar los existenes a través de la línea de comandos
```sh
sh dsymfony app:create-update:user nuevo_usuario 123456 ROLE_ADMIN
```


### Terminal

Podemos acceder **como root** (no recomendado) al servicio de docker via terminal con el comando:

```sh
# from the root folder
sh droot bash
```

Y como usuario no administrador, con el usuario www-data:

```sh
# from the root folder
sh dexec bash
```

### Comandos de docker (Symlinks)

Nos hemos permitido crear una serie de comandos para que sea más cómodo utilizar docker. Todos los comandos están localizados en ``.infra/docker/bin`` y se instalan lanzando el script desde la raíz:
```sh
sh .infra/docker/bin/docker-symlinks-up
```

#### Symlinks List sorted alphabetically

Use Composer:

```sh
sh dcomposer [ARGS]
```

Shutdown docker:

```sh
sh ddown
```

Execute any command in www service as www-data user:

```sh
sh dexec [ARGS]
```

Reset docker:

```sh
sh dreset
```

Start docker:

```sh
sh dstart
```

Stop docker:

```sh
sh dstop
```

Execute any symfony command in console as www-data user:
```sh
sh dsymfony [ARGS]
```

Build docker:

```sh
sh dup
```
