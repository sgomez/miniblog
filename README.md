# README #

---

Este repositorio contiene una pequeña aplicación web de prueba realizada con Silex que usé como demostración en la [Cuarta BetaBeers de Córdoba](http://betabeers.com/event/4-betabeers-cordoba-626/). Trata de un blog muy simple, con un frontend y un backend sencillo para la creación de nuevas entradas.

La aplicación está basada en la aplicación [Bilbostack](https://github.com/javiereguiluz/bilbostack) realizada por [Javier Eguiluz](https://twitter.com/javiereguiluz), al cual agradezco que me haya dejado usar su material como apoyo a mi charla. Podéis encontrar [más material sobre Silex](http://symfony.es/noticias/2013/01/30/silex-desarrollo-web-%C3%A1gil-y-profesional-con-php/) en su página web.

**Cómo instalar el proyecto**

    $ git clone git://github.com/sgomez/miniblog.git miniblog
    $ cd miniblog
    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar install

Si al probar el proyecto por primera vez te aparece una página en blanco o
cualquier mensaje de error, es casi seguro que debes cambiar los permisos
de los directorios logs/ y/o cache/   El usuario con el que se ejecuta el
servidor web debe tener permisos de escritura en esos directorios.

La aplicación cuenta con una parte de administración o *backend* accesible en la
URL `/admin`. El usuario para acceder a ella es `admin` y su contraseña es `1234`.

La información de los usuarios registrados se guarda en una base de datos de
tipo SQLite (asegúrate de que tu PHP tiene soporte de SQLite y de PDO). Para
inicializar las tablas en la base de datos, ejecuta el siguiente comando de consola:

```
$ ./console create-schema
```
Para poder insertar registros en la base de datos, tanto el archivo como el directorio
donde se encuentra tienen que tener permisos de escritura para el servidor web. En
este ejemplo sencillo puedes resolverlo dando permisos de escritura a "otros":

```
$ chmod o+w config
$ chmod o+w config/schema.sqlite
```