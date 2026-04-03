Como ejecutar la pagina desde tu equipo:

asegurate de tener instalado composer y en la raiz del proyecto ejecuta en la terminal:

composer install

Esto agregara las librerias necesarias.

renombra config_ejemplo.php por config.php

Descarga XAMPP y activa SQL y Apache, añade la carpeta centinela dentro de C:xampp/htdocs (en el caso de que hayas instalado xampp en esa ruta), luego entra al navegador y escribe localhost, te deberia redirigir al dashboard de xampp, para abrir la pagina centinela agrega en la url /centinela

Para acceder a funciones administrativas, inicia sesion con las siguientes credenciales:

correo: centinelas@gmail.com
password: root

Si las credenciales son correctas te redirige a el panel de administrador, si colocas credenciales incorrectas, te dice en que te equivocaste.

Pestañas vacías como Contacto, Eventos y Plantilla, están vacías porque se hará un CRUD en el que el administrador puedas agregar información a esas pestañas, pero aun no esta listo.