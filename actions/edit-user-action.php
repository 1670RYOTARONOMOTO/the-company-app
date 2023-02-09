<?php
    include '../classess/User.php';

    $user = new User;

    $user->upDate($_POST,$_FILES);


?>