<?php
    require_once('login.php');
    $message_connect = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    if($message_connect->connect_error) die($connection->connect_error);

    $user = $_POST['user'];

    $query = "SELECT * FROM messages WHERE sentto = '$user' UNION SELECT * FROM messages WHERE sentfrom = '$user' ORDER BY num DESC LIMIT 10";
    $result = $message_connect->query($query);
    if(!$result) die("Database access failed: " . $message_connect->error);
    while($row = mysqli_fetch_array($result)) {
?>
    
    <div class="mess_box <?php ?>">
        <div class="from">From: <?php echo $row['sentfrom'] ?></div>
        <div class="to">To: <?php echo $row['sentto'] ?></div>
        <div class="mess <?php if($row['sentfrom'] == $user) echo 'my_mess' ?>"><?php echo $row['m'] ?></div>
        <form class="delete_mess_form" action="index.php" method="post">
            <input type="hidden" name="delete" value="true">
            <input type="hidden" name="m_num" value="<?php echo $row['num'] ?>">
            <input type="submit" value="X">
        </form>
    </div>
    
<?php 
    }
?>