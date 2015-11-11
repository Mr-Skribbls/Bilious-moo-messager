<?php
if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
else {
    $user = "";
}
function body1($connect) {
$b1 = <<<_END

    <form id="post_message_form" action="index.php" method="post">
        <input type="hidden" name="post" value="true">
        <div>Send a Message To:</div>
        
_END;
    
    $b2 = "";
    
    $query = "SELECT * FROM users";
    $result = $connect->query($query);
    if(isset($_SESSION['convo_with'])) {
        while($row = mysqli_fetch_array($result)) {
            //if $row['user'] is not the same as the logged in user
            if($row['user'] != $_SESSION['user']) {
                if($_SESSION['convo_with'] == $row['user']) {
                    $checked = "checked='checked'";
                }
                else {
                    $checked = "";
                }
                $b2 = $b2 . "<div class='mess_to_option'><label><input type='radio' name='sentto' value='" . $row['user'] . "' " . $checked . ">" . $row['user'] . "</label></div>";
            }
        }
    }
    else {
        while($row = mysqli_fetch_array($result)){
            $b2 = $b2 . "<div><label><input type='radio' name='sentto' value='" . $row['user'] . " " . $checked . "'>" . $row['user'] . "</label></div>";
        }
    }
            
$b3 = <<<_END
        <textarea id="mess_text_input" name="new_m" form="post_message_form" rows="5" cols="50"></textarea>
        <div><input type="submit" value="Post Message"></div>
    </form>
    
_END;
    
    return $b1.$b2.$b3;
    
}

$body2 = <<<_END

    <form id="login_form" action="index.php" method="post">
        <input type="hidden" name="login" value="true">
        <div id="login_username"><label><input type="text" name="user" class="login_text_input" placeholder="Username"></label></div>
        <div id="login_password"><label><input type="password" name="passwd" class="login_text_input" placeholder="Password"></label></div>
        <div><input type="submit" value="Login" class="login_submit"></div>
    </form>
    <a id="new_user_link" href="new_user.php">New User</a>
    
_END;

function html_log($u) {
    $html_logout = <<<_END
    
    <form id="logout_form" action="index.php" method="post">
        <input type="hidden" name="logout" value="true">
        <input type="submit" value="Logout">
    </form>
    
_END;
    
    $l1 = "<div id='user_dashboard'>";
    $l2 = "   <div id='user_name'>$u</div>";
    $l3 = "</div>";
    
    return $l1.$l2.$html_logout.$l3;
}

$head = <<<_END
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Messager</title>
    
    <link rel="stylesheet" href="css/style.css">
    
    <script src="js/jquery-1.11.2.min.js"></script>
    
    <script type="text/javascript">
        $(document).ready (function () {
            var updater = setInterval (function () {
                $('div#messages').load ('messages.php', {'user' : '$user'});
            }, 1000);
            
        });
    </script>
  
</head>
<body>
   
_END;

$html_close = <<<_END

</body>
</html>

_END;
?>