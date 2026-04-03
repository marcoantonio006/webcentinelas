<?php

session_start();      

session_unset();      

session_destroy();    

header('Location: /centinela/index.php');
exit;                 