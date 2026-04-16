Como ejecutar la pagina desde tu equipo:

asegurate de tener instalado composer y xampp. en la raiz del proyecto ejecuta en la terminal:

composer install

Esto agregara las librerias necesarias.

renombra config_ejemplo.php por config.php

Descarga XAMPP y activa SQL y Apache, añade la carpeta centinela dentro de C:xampp/htdocs (en el caso de que hayas instalado xampp en esa ruta), luego entra al navegador y escribe localhost, te deberia redirigir al dashboard de xampp, para abrir la pagina centinela agrega en la url /centinela

Importa la base de datos (basededatos.sql) en phpmyadmin

Para acceder a funciones administrativas, inicia sesion con las siguientes credenciales:

correo: centinelas@gmail.com
password: root

Si las credenciales son correctas te redirige a el panel de administrador, si colocas credenciales incorrectas, te dice en que te equivocaste.
