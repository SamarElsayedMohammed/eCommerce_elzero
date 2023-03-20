<?php
session_start();
$noNavbar  = "";
$pageTitle = 'Login';
if(isset($_SESSION['Username'])){
    header('Location: dashboard.php');
}
include 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user'];
    $password = $_POST['pass'];
    //    echo $user_name . " " .$password;

    $hashedPass = sha1($password);
    //    echo $hashedPass ;
    //    check if user in db

    $stmt = $con->prepare("SELECT UserID,Username ,Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1");
    $stmt->execute(array($user_name, $hashedPass));
    $row = $stmt->fetch(); 
    $count = $stmt->rowCount();
    if ($count > 0) {
        echo "welcome $user_name";
        $_SESSION['Username'] = $user_name;
        $_SESSION['ID']=$row['UserID'];
        header('Location: dashboard.php');
        exit();
    }
}

?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="login">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control" type="text" name="user" placeholder="UserName" autocomplete="off">
    <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" id="">
    <input class="btn btn-primary btn-block" type="submit" value="Login">
</form>
<?php include $tpl . "footer.php"; ?>