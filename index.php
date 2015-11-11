<?php
session_start();
require_once('login.php');
require_once('lib/PHPclasses.php');


// security functions
function q_prep($connection, $var) {
    if(get_magic_quotes_gpc()) {
        $var = stripslashes($var);
    }
    $var = htmlentities($var);
    return $connection->real_escape_string($var);
}


//database connection code
$message_connect = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if($message_connect->connect_error) die($connection->connect_error);


// logout code goes here
if(isset($_POST['logout']) && $_POST['logout'] == "true") {
    unset($_SESSION['user']);
    unset($_SESSION['convo_with']);
}

//login code
if( isset($_POST['login']) && $_POST['login'] == "true" &&
    isset($_POST['user']) &&
    isset($_POST['passwd'])) {
    $pw = $_POST['passwd'];
    $passwd = hash('ripemd128', "$salt1$pw$salt2");
    
    $u = q_prep($message_connect, $_POST['user']);
    $users_query = "Select * FROM users WHERE user = '$u'";
    $user = $message_connect->query($users_query);
    $n = $user->num_rows;
    if($n > 0) {
        $logged_in_user = mysqli_fetch_array($user);
        $logged_in_user_name = $logged_in_user['user'];
        $logged_in_user_password = $logged_in_user['passwd'];
        if ($logged_in_user_password == $passwd) {
            $login_status = "Password verified!";
            $_SESSION['user'] = $logged_in_user_name;
            $_SESSION['convo_with'] = "";
        }
        else {
            $login_status = "<div id='not_login'>Wrong username or password</div>";
        }
    }
    else {
        $login_status = "<div id='not_login'>Wrong username or password</div>";
    }
}
else {
    $login_status = "<div id='not_login'>Please login</div>";
}

require_once('lib/HTMLstructure.php');


if(isset($_SESSION['user'])) {
    $user = stripslashes(htmlentities((string) $_SESSION['user']));

    // delete message code goes here
    if( isset($_POST['delete']) && isset($_POST['m_num']) ) {
        $m_num = q_prep($message_connect, $_POST['m_num']);
        $query = "DELETE FROM messages WHERE num = '$m_num'";
        $result = $message_connect->query($query);
        if(!$result) echo "DELETE failed: $query<br>" . $message_connect->error . "<br><br>";
    }

    // post message code goes here
    if( isset($_SESSION['user']) && 
        isset($_POST['post']) && $_POST['post'] == "true") {
        
        if( isset($_POST['new_m']) &&
            isset($_POST['sentto'])) {
            
            $_SESSION['convo_with'] = $_POST['sentto'];
            
            $user = q_prep($message_connect, $_SESSION['user']);
            $new_m = q_prep($message_connect, $_POST['new_m']);
            $to = q_prep($message_connect, $_POST['sentto']);
            $query = "INSERT INTO messages VALUES(NULL, '$user', '$to', '$new_m')";
            $result = $message_connect->query($query);
            if(!$result) echo "Failed to post message:<br />" . $message_connect->error . "<br />";
            $message_status = "";
        }
        else {
            $message_status = "Unable to post your message. ";
        }
    }


    echo $head;
    echo html_log($user);
    if(isset($message_status)) {
        echo $message_status;
    }
    echo body1($message_connect);
    echo "<div id='messages'>";
    echo "</div>";
    echo $html_close;
    
}
else {
    // Login form goes here
    echo $head;
    echo "<div id='login_box'>";
    echo $login_status;
    echo $body2;
    echo "</div>";
    echo $html_close;
    //$message_connect->close;
}
/*if(isset($_SESSION)) {
    print_r($_SESSION);
}*/
?>