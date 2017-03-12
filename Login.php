<?php session_start();
    include("http://cs411foodzilla.web.engr.illinois.edu/Login.php");
    $con = mysqli_connect('http://webhost.engr.illinois.edu', 'cs411foodzilla_users', 'cs411');

    if(!$con){
      die('Could not connect: '.mysql_error());
    }
    mysql_select_db('cs411foodzilla_users');

    $email = $_POST["email"];
    $password = $_POST["password"];

    $check= "SELECT * FROM users WHERE email = '$_POST['email']'";
    $rs = mysqli_query($con, $check);
    $data = mysqli_fetch_array($rs, MYSQLI_NUM);

    if($data[0] > 1) {
      //select a user from a database where the email and password matches
      $statement = mysqli_prepare($con, "SELECT * FROM user WHERE email = ? AND password = ?");
      mysqli_stmt_bind_param($statement, "ss", $email, $password);
      mysqli_stmt_execute($statement);

      mysqli_stmt_store_result($statement);
      mysqli_stmt_bind_result($statement, $email, $password);

      $response = array();
      $response["success"] = false;

      //gets the data from the database
      while(mysqli_stmt_fetch($statement)){
          $response["success"] = true;
          $response["email"] = $email;
          $response["password"] = $password;
      }

      //encodes it in json format
      echo json_encode($response);
    }

    else{
        /*
        $newUser = "INSERT INTO users(email, password) values('$_POST[email]','$_POST[password]')";
        if (mysqli_query($con, $newUser)){
            echo "You are now registered<br/>";
        }else{
            echo "Error adding user in database<br/>";
        }
        */

        $statement = mysqli_prepare($con, "INSERT INTO users (email, password) VALUES (?, ?)");
        mysqli_stmt_bind_param($statement, "siss", $email, $password);
        mysqli_stmt_execute($statement);

        $response = array();
        $response["success"] = true;

        echo json_encode($response);
    }

    mysql_close($con);

?>
