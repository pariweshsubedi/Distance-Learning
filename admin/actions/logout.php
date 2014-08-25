<?php
session_start();
session_destroy();
header("Location: /vline/index.php");
?>