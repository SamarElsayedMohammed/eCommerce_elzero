<?php
session_start();
$pageTitle = 'Login';
if (isset($_SESSION['user'])) {
    header('Location: index.php');
}
include 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {


        $user = $_POST['username'];
        $password = $_POST['password'];
        //    echo $user_name . " " .$password;

        $hashedPass = sha1($password);
        //    echo $hashedPass ;
        //    check if user in db

        $stmt = $con->prepare("SELECT UserID,Username ,Password FROM users WHERE Username = ? AND Password = ?  LIMIT 1");
        $stmt->execute(array($user, $hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {
            echo "welcome $user";
            $_SESSION['user'] = $user;
            $_SESSION['uid'] = $row['UserID'];
            // print_r($_SESSION);
            header('Location: index.php');
            exit();
        }
    } else {
        $formErors = array();

        $username = $_POST['username'];
        $password1 = $_POST['password'];
        $password2 = $_POST['password2'];
        $email = $_POST['email'];


        if (isset($username)) {
            $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);
            if (strlen($filterdUser) < 4) {
                $formErors[] = 'Username Must Be Larger THan 4 Characters';
            }
        }
        if (isset($password1) && isset($password2)) {
            if (empty($password1)) {
                $formErors[] = 'password can not be empty';
            }
            if (sha1($password1) !== sha1($password2)) {
                $formErors[] = 'password not mached';
            }
        }
        if (isset($email)) {
            $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {
                $formErors[] = 'This email not valid';
            }
        }


        if (empty($formErors)) {

            $check = checkItem("Username", "users", $username);

            if ($check >= 1) {
                $formErors[] = 'This user is eXist';
            } else {


                $stmt = $con->prepare("INSERT INTO users (Username ,Email   ,Password ,Date ,RegStatus ) VALUES(:xuser ,:xemail ,:xpassword,now() ,0)");
                $stmt->execute(array(
                    'xuser'     =>  $username,
                    'xemail'    => $email,
                    'xpassword' => sha1($password1)
                ));

                $successMsg = 'congratulation yor account created successfuly';
            }
        }
    }
}

?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Login</span> |
        <span data-class="signup">SignUp</span>
    </h1>
    <!-- start login form  -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input type="text" class="form-control" name="username" autocomplete="off" placeholder="type your user name" required>
        </div>
        <div class="input-container">
            <input type="password" name="password" autocomplete="new-password" class="form-control password" placeholder="enter your password" required>
        </div>
        <input type="submit" value="Login" name="login" class="btn btn-primary btn-block">

    </form>
    <!-- end login form  -->
    <!-- start signup form  -->

    <form class="signup" action="" method="post">
        <div class="input-container">
            <input pattern=".{4,20}" title="Username must between 4 and 20 chars" type="text" class="form-control" name="username" autocomplete="off" placeholder="type your user name" required>
        </div>
        <div class="input-container">
            <input minlength="4 " type="password" name="password" autocomplete="new-password" class="form-control password" placeholder="enter your password" required>
        </div>
        <div class="input-container">
            <input type="password" name="password2" autocomplete="new-password" class="form-control" placeholder="enter your password again" required>
        </div>
        <div class="input-container">
            <input type="email" name="email" class="form-control" placeholder=" type a valid email" required>
        </div>
        <input type="submit" name="signup" value="SignUp" class="btn btn-success btn-block">

    </form>
    <!-- end signup form  -->
    <div class="the-errors text-center">
        <?php
        if (!empty($formErors)) {
            foreach ($formErors as $error) {
                echo '<div class="error msg">' . $error . '</div>';
            }
        }
        if (isset($successMsg)) {
            echo '<div class="success msg">' . $successMsg . '</div>';
        }
        ?>
    </div>

</div>
<?php include $tpl . 'footer.php'; ?>