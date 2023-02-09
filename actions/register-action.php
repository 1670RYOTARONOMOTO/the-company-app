<?php
    include '../classess/User.php';

    $user = new User;


    $user->store($_POST);
?>