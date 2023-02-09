<?php
    include '../classess/User.php';

    $user = new User;


    $user->login($_POST);

?>