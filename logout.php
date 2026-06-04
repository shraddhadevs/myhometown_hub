<?php

session_start();

session_destroy();

// used ../ to move out the php folder
header("Location: ../index.html");

exit();

?>