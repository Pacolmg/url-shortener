# Instrucciones Url Shortener
============================

## Postman
La colección de Postman se puede [descargar aquí](https://raw.githubusercontent.com/Pacolmg/url-shortener/master/doc/url-shortener.postman_collection.json) y necesita un entorno con dos variables que también se puede [descargar aquí](https://raw.githubusercontent.com/Pacolmg/url-shortener/master/doc/url-shortener-local.postman_environment.json). Estas variables son:
 - url-shortener.server
 - url-shortener.token
 
 Si accedemos al endpoint de "Login"  automáticamente se refresca la variable `url-shortener.token` si el login es correcto.

## El Acortador de Urls

El acortador funciona almacenando en una tabla la url original del sitio y la nueva url que hemos creado con el acortador.

Un usuario gestiona sus propias urls y lo único que puede editar de una url que ha acortado es el sitio original, al sitio al que se redireccionaría.

El dominio en el que se instalará el funcionamiento del acortador se puede cambiar mediante una variable de entorno en el `.env`, esta variable es `URL_SHORTENER_HOST`. 

El ranking de visitas es público y ordenable por número total de visitas y número de visitas en un periodo de tiempo, que de momento es una constate (el último día).

Un usuario **solo puede acceder a sus urls**, así que si hacemos login, podremos:
 - Listar nuestras urls (List)
 - Mostrar una sola poniendo su id (Show)
 - Eliminar una poniendo su id (Delete)
 - Modificar una url (Update)
  -- Solo se puede modificar la url original, así que son obligatorios los parámetros `original_url` y el `id`.
 - Crear una url (Create)
  -- Son obligatorios los parámetros `original_url`, `short_method`.
  
Además de lo que puede hacer el usuario, hay otro endpoint para el que no se requiere seguridad:
 - Ranking: muestra las urls con su número total de visitas y las visitas en un último periodo de tiempo (último día)


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
  
  Si el usuario existe, lo edita.
  
  
  Los usuarios deben tener `ROLE_ADMIN` para poder gestionar sus urls.

  ## Gestión de Errores en interfaz
  Para evitar que el producto que se entrega no se vea correctamente si aparece en bug, los errores se capturan con un EventListener, y se han controlado: 
   - Los BadRequest que se lanzan si a los endpoints no llegan los parámetros necesarios
   - Los NotFound al poner una url incorrecta
   - Los UniqueConstraintValidator de la base de datos
   - Cualquier otro error será un 500, pero también se captura y se muestra un json con el error controlado y sin la traza.
   
   Si queremos ver los errores completos, antes de mandarlos al usuario se guardan en el fichero de log.
