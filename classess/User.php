<?php
    require_once 'Database.php';


    class User extends Database {
        public function store($request){
            $first_name = $request['firstname'];
            $last_name = $request['lastname'];
            $username = $request['username'];
            $user_password = $request['password'];

            $password = password_hash($user_password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users(`first_name`, `last_name`, `username`, `password`) VALUES('$first_name','$last_name','$username','$password')";

            if($this->conn->query($sql)) {
                header('location: ../views/index.php');
                exit();
            }else{
                die("ERROR IN INSERTING DATA " . $this->conn->error);
            }
        }

        public function login($request){
            $username = $request['username'];
            $password = $request['password'];

            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = $this->conn->query($sql);
            if($result->num_rows == 1) {

                $user = $result->fetch_assoc();

                if(password_verify($password, $user['password'])){

                    session_start();
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['fullname'] = $user['first_name'] . " " . $user['last_name'];

                    header('location: ../views/dashboard.php');
                    exit;
                }else{
                    die("PASSWORD IS INCORRECT");
                }
            }else{
                die("USERNAME IS NOT FOUND");
            }
            
        }
        public function logout(){
            session_start();
            session_unset();
            session_destroy();

            header('location: ../views/index.php');
            exit;
        }
        public function getAllUsers(){
            $sql = "SELECT id, first_name, last_name, username, photo FROM users";
            if ($result = $this->conn->query($sql)) {
                return $result;
            }else{
                die("ERROR IN RETRIEVING USERS " . $this->conn->error);
            }
        }

        public function getUser(){
            
            $id = $_SESSION['id'];

            $sql = "SELECT `first_name`, `last_name`, `username`, `photo` FROM users WHERE id = '$id'";

            if ($result = $this->conn->query($sql)) {
                return $result->fetch_assoc();
            } else {
                die("ERROR IN RETRIEVING USERES: " . $this->conn->error);
            }
        }
        public function upDate($request, $files){
            session_start();
            $id = $_SESSION['id'];
            $first_name = $request['first_name'];
            $last_name = $request['last_name'];
            $username = $request['username'];

            $photo = $files['photo']['name'];
            $tmp_photo = $files['photo']['tmp_name'];

            $sql = "UPDATE users 
            SET `first_name` = '$first_name',
                `last_name` = '$last_name', 
                `username` = '$username' 
            WHERE `id` = '$id'";

            if ($this->conn->query($sql)) {
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = "$first_name  $last_name" ;

                if ($photo) {
                    $sql = "UPDATE users SET `photo` = '$photo' WHERE `id` = '$id'";
                    $destinamtion = "../assets/images/$photo";

                    if ($this->conn->query($sql)) {
                        if(move_uploaded_file($tmp_photo, $destinamtion)) {
                            header("location:../views/dashboard.php");
                            exit;
                        } else {
                            die("ERROR MOVING THE FILE TO THE DESTINATION");
                        }
                    }
                } else {
                    die("ERROR UPLOADING PHOTO " . $this->conn->error);
                } 
                //header("location:../views/dashboard.php");
            }else{
                die("ERROR IN UPDATING THE USER " . $this->conn->error);
                exit;  
            }

            
        }
        public function delete() {
            session_start();
            $id = $_SESSION['id'];

            $sql = "DELETE FROM users WHERE `id`='$id'";

            if ($this->conn->query($sql)) {
                
                $this->logout();

            }else {
                die("ERRPR DELETING YOU ACCOUNT: " . $this->conn->error);
            }
        }
    }

?>