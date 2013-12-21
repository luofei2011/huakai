<?php 
/*
 * $1 username
 * $2 password
 * $3 email
 * $4 auth
 *
 * */
$db = mysqli_connect('localhost', 'root', '', 'huakai_info');
$pwd = md5($argv[2]);
$query = "INSERT INTO account values('', '$argv[1]', '$pwd', '$argv[3]', '0')";
$result = mysqli_query($db, $query);
if ($result) {
    echo "Insert Success!\n";
    echo "Please remenber the account:\n";
    echo "username: " . $argv[1] . "\n";
    echo "password: " . $argv[2] . "\n";
    echo "email: " . $argv[3] . "\n";
    echo "You are Administrator!\n";
}
mysqli_close($db);
?>
