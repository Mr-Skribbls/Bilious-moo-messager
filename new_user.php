<?php
require_once('login.php');
require_once('lib/PHPclasses.php');
$connection = new DB($db_hostname,$db_database,$db_username,$db_password);

// HTML structure
$html_head = <<<_END
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>New User</title>
    
    <link rel="stylesheet" href="css/style.css">
    
    <script src=""></script>
    
</head>
_END;

$html_open_body = "<body>";

$html_new_user_form = <<<_END
    <form action="new_user.php" method="post">
        <div><input type="text" name="username" class="login_text_input" placeholder="Username"></div>
        <div><input type="password" name="password1" class="login_text_input" placeholder="Enter Password"></div>
        <div><input type="password" name="password2" class="login_text_input" placeholder="Reenter Password"></div>
        <input type="submit" value="Create New User" class="login_submit">
    </form>
_END;

$link_to_login = "<div><a id='login_link' href='index.php'>Click Here to Login</a></div>";

$html_close_body = <<<_END
</body>
</html>

_END;



echo $html_head;
echo $html_open_body;
echo "<div id='login_box'>";

//check if the user has submited the new user form
if(isset($_POST['username']) && $_POST['username'] != "") {
    $username = $_POST['username'];
    
    //if username matches another username in the database
    $specific_users_query = "SELECT * FROM users WHERE user = '$username'";
    $result = $connection->Q($specific_users_query);
    if($result['num_rows'] > 0){
        echo "This Username is already in use.";
        echo $html_new_user_form;
        echo $link_to_login;
    }
    else { //if supplied username is not already in use
        //check if the user supplied passwords match
        if(isset($_POST['password1']) && isset($_POST['password2']) && $_POST['password1'] == $_POST['password2']) {
            $password = $_POST['password1'];
            //add new user to the db
            $token = hash('ripemd128', "$salt1$password$salt2");
            add_user($connection, $username, $token);

            echo "<div>$username. You can now login to send messages.</div>";
            echo "<div><a href='index.php' >Click here to login</a></div>";
        }
        else { //if the users passwords don't match
            echo "Your passwords didn't match.";
            echo $html_new_user_form;
            echo $link_to_login;
        }
    }
}
//if user submitted an empty username
elseif(isset($_POST['username']) && $_POST['username'] == "") {
    echo "Please enter a Username and Password";
    echo $html_new_user_form;
    echo $link_to_login;
}
else { //if the user hasn't submitted a new user form
    echo $html_new_user_form;
    echo $link_to_login;
}

echo "</div>";
echo $html_close_body;


//functions
function add_user($connection, $un, $pw)
  {
    $query  = "INSERT INTO users VALUES('$un', '$pw')";
    $result = $connection->Q($query);
    if (!$result) die("unable to add user");
  }
?>