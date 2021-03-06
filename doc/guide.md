Instrucciones y Aclaraciones del proyecto Url Shortener
============================

## Postman
La colección de Postman se puede [descargar aquí](https://raw.githubusercontent.com/Pacolmg/url-shortener/master/doc/url-shortener.postman_collection.json) y necesita un entorno con dos variables que también se puede [descargar aquí](https://raw.githubusercontent.com/Pacolmg/url-shortener/master/doc/url-shortener-local.postman_environment.json). Estas variables son:
 - url-shortener.server
 - url-shortener.token
 
 Si accedemos al endpoint de "Login"  automáticamente se refresca la variable `url-shortener.token` si el login es correcto.
 
## Pruebas
Si se ha seguido la instalación del proyecto a través del README, se habrán cargado unas `fixtures` con diez usuarios, se puede utilizar cualquiera de ellos para probar.

Sus nombres de usuario son `user1`, `user2`, ... y la contraseña para todos es `123456`.


## El Acortador de Urls

El acortador funciona almacenando en una tabla la url original del sitio y la nueva url que hemos creado con el acortador.

Un usuario gestiona sus propias urls y lo único que puede editar de una url que ha acortado es el sitio original, al sitio al que se redireccionaría.

El dominio en el que se instalará el funcionamiento del acortador se puede cambiar mediante una variable de entorno en el `.env`, esta variable es `URL_SHORTENER_HOST`. 

El ranking de visitas es público y ordenable por número total de visitas y número de visitas en un periodo de tiempo, que de momento es una constate (las últimas 24 horas).

Un usuario **solo puede acceder a sus urls**, así que si hacemos login, podremos:
 - Listar nuestras urls (List)
 - Mostrar una sola poniendo su id (Show)
 - Eliminar una poniendo su id (Delete)
 - Modificar una url (Update)
  -- Solo se puede modificar la url original, así que son obligatorios los parámetros `original_url` y el `id`.
 - Crear una url (Create)
  -- Son obligatorios los parámetros `original_url`, `short_method`.
  
Además de lo que puede hacer el usuario, hay otro endpoint para el que no se requiere seguridad:
 - Ranking: muestra las urls con su número total de visitas y las visitas en un último periodo de tiempo (últimas 24 horas)


  ## Seguridad
  Para el login se ha utilizado un token jwt, ayúndandonos del bundle [lexik/LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle).
  
  Para evitar que un usuario actúe sobre urls que no son suyas, se ha implementado un Voter que comprueba si es el propietario de la url.
  
  Además, se ha implementado un filtro de doctrine, de tal manera que un usuario solo puede listar/ver o hacer cualquier operación sobre urls en las que él sea el propietario. 
  
  Se pueden crear/editar usuarios por línea de comandos:
  ```sh
  php bin/console app:create-update:user [nombre de usuario] [contraseña] [roles separados por comas]
  ```
  Ejemplo:
  ```sh
  php bin/console app:create-update:user user1 123456 ROLE_ADMIN
  ```
  
  Si el usuario existe, lo edita, si no, lo crea.
 
  
  Los usuarios deben tener `ROLE_ADMIN` para poder gestionar sus urls.

  ## Gestión de Errores en interfaz
  Para evitar que la api final entregada no se vea correctamente en el cliente si aparece un bug, las excepciones, antes de llegar al cliente se capturan con un EventListener, se han controlado: 
   - BadRequest que se lanzan si a los endpoints no llegan los parámetros necesarios
   - NotFound al poner una url incorrecta
   - UniqueConstraintValidator de la base de datos
   - Cualquier otro error será un 500, pero también se procesa y se muestra un json con el error controlado y sin la traza.
   
   Si queremos ver los errores completos, antes de mandarlos al usuario se guardan en el fichero de log.
   
   ## Mejoras
   Posibles mejoras del proyecto:
   
   - Habría sido conveniente paginar el listado de urls. Así como el ranking.
   - También se necesitaría un endpoint con el que registrar visitas.
   - De las visitas se podrían registrar en un campo de tipo json toda la información de la request que llegase a la url acortada.
   - Se podría pensar guardar en el log el body de la petición de edición y creación de urls, para depurar posibles bugs.
   
