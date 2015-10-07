<?php

$enlace = mysql_connect("localhost",
                       "root",
                       "andromeda");
mysql_select_db("MAB");
mysql_query('RENAME TABLE localidad TO localidadsiep');
mysql_close($enlace);

