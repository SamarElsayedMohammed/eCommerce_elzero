<?php
session_start();
if (isset($_SESSION['Username'])) {
    $pageTitle = 'Members';
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') { /* manage page*/

        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'pending') {
            $query = 'AND RegStatus = 0';
        }

        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID !=1 $query ORDER BY UserID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
?>

        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <a href="members.php?do=Add" class="btn btn-primary add-btn"> <i class="fa fa-plus"></i> New Member</a>
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered manage-members">
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full name</td>
                        <td>Registerd Date</td>
                        <td>Actions</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['UserID'] . '</td>';
                        echo '<td>';
                        if (empty($row['avatar'])) {
                            echo '<img src="layout/images/avatar.jpeg">';
                        } else {
                            echo '<img src="uploads/avatars/' . $row['avatar'] . '">';
                        }

                        echo '</td>';
                        echo '<td>' . $row['Username'] . '</td>';
                        echo '<td>' . $row['Email'] . '</td>';
                        echo '<td>' . $row['FullName'] . '</td>';
                        echo '<td>' . $row['Date'] .  '</td>';
                        echo '<td><a href="members.php?do=Edit&userid=' . $row['UserID'] . '" class="btn btn-success"><i class="fa fa-edit"></i>
                        Edit</a>
                        <a href="members.php?do=Delete&userid=' . $row['UserID'] . '"  class="btn btn-danger confirm"><i class="fa fa-close"></i>Delete</a>';
                        if ($row['RegStatus'] == 0) {
                            echo ' <a href="members.php?do=Activate&userid=' . $row['UserID'] . '"class="btn btn-info"><i class="fa fa-check"></i>activate</a></td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>

    <?php
    } elseif ($do == 'Add') {
    ?>
        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form action="?do=Insert" method="POST" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="username" id="" autocomplete="off" required='required' placeholder="Enter user name">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">password</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="password" class="password form-control" name="password" id="" autocomplete="new-password" placeholder="Enter Password" required='required'>
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">email</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" class="form-control" name="email" id="" required='required' placeholder="Enter Your Password">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">fullname</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="fullname" id="" required='required' placeholder="Enter your full name">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">user avatar</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="file" class="form-control" name="avatar" id="" required='required' placeholder="Enter your full name">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" class="btn btn-primary btn-lg" value="save" id="">
                    </div>
                </div>
            </form>
        </div>

        <?php

    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center">Update Member</h1>';
            echo '<div class="container">';

            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTemp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];

            $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
            $arr = explode(".", $avatarName);

            $avatarExtension = strtolower(end($arr));

            $username = $_POST['username'];
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $hashPassword = sha1($_POST['password']);

            // echo $email . '<br>' . $username . '<br>' . $fullname . '<br>' . $password;

            //validation
            $formErrors = array();
            if (empty($username)) {
                $formErrors[] = 'user name cant not be empty';
            }
            if (strlen($username) > 20) {
                $formErrors[] = 'user name cant not be more than 20 character';
            }
            if (strlen($username) < 4) {
                $formErrors[] = 'user name cant not be more less 4 character';
            }
            if (empty($fullname)) {

                $formErrors[] = 'full name cant not be empty';
            }
            if (empty($password)) {

                $formErrors[] = 'Password cant not be empty';
            }
            if (empty($email)) {
                $formErrors[] = 'email cant not be empty';
            }
            if (!empty($avatarNameva) && !in_array($avatarExtension, $avatarAllowedExtension)) {
                $formErrors[] = 'this type not allowed';
            }
            if (!empty($avatarNameva)) {
                $formErrors[] = 'avatar cant not be empty';
            }
            if ($avatarSize > 4191403) {
                $formErrors[] = 'avatar size cant not be larger than 4MB';
            }

            foreach ($formErrors as $eror) {

                echo '<div class="alert alert-danger">' . $eror . '</div>';
            }
            if (empty($formErrors)) {

                $check = checkItem("Username", "users", $username);

                if ($check >= 1) {

                    $errorMsg = '<div class="alert alert-danger">user is eXist</div>';
                    redirectHome($errorMsg, 3);
                } else {


                    $avatar = rand(0, 10000000000) . "_" . $avatarName;

                    move_uploaded_file($avatarTemp, "uploads/avatars/$avatar");
                    chmod("uploads/avatars/$avatar", 0777);

                    $stmt = $con->prepare("INSERT INTO users ( Username ,Email , FullName  ,Password ,Date ,RegStatus,avatar) VALUES(:xuser ,:xemail ,:xfullname ,:xpassword,now(),1 ,:xavatar)");
                    $stmt->execute(array(
                        'xuser'     =>  $username,
                        'xemail'    => $email,
                        'xfullname' => $fullname,
                        'xpassword' => $hashPassword,
                        'xavatar' => $avatar
                    ));
                    echo '<div class="container">';


                    $errorMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " record inserted </div>";
                    redirectHome($errorMsg, 3);
                    echo '</div>';
                }
            }
        } else {

            echo '<div class="container">';

            $errorMsg = "<div class='alert alert-danger'>you can't access Inser directly</div>";
            redirectHome($errorMsg, 3);
            echo '</div>';
        }
    } elseif ($do == 'Edit') {
        // if (isset($_GET['userid']) && is_numeric($_GET['userid']) ) {
        //     $userid = intval($_GET['userid']);
        // } else {
        //     $userid = 0;
        // }
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form action="?do=Update" method="POST" class="form-horizontal">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="username" id="" value="<?php echo $row['Username'] ?>" autocomplete="off" required='required'>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" class="form-control" name="oldpassword" value="<?php echo $row['Password'] ?>" id="" autocomplete="new-password">
                            <input type="password" class="form-control" name="newpassword" id="" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" class="form-control" value="<?php echo $row['Email'] ?>" name="email" id="" required='required'>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">fullname</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="fullname" id="" value="<?php echo $row['FullName'] ?>" required='required'>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary btn-lg" value="save" id="">
                        </div>
                    </div>
                </form>
            </div>

<?php
        } else {
            echo '<div class="container">';


            $errorMsg = "<div class='alert alert-danger'>This id not exist</div>";

            redirectHome($errorMsg, 3);
            echo '</div>';
        }
    } elseif ($do == 'Update') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<h1 class="text-center">Update Member</h1>';
            echo '<div class="container">';

            $id = $_POST['userid'];
            $username = $_POST['username'];
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            // echo $id . $username . $fullname . $email;  

            $pass = '';
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
            // if (empty($_POST['newpassword'])) {
            //    $pass = $_POST['oldpassword'];
            // } else {
            //     $pass = sha1($_POST['newpassword']);
            // }

            //validation
            $formErrors = array();
            if (empty($username)) {
                $formErrors[] = '<div class="alert alert-danger"></div>user name cant not be empty</div>';
            }
            if (strlen($username) > 20) {
                $formErrors[] = '<div class="alert alert-danger">user name cant not be more than 20 character</div>';
            }
            if (strlen($username) < 4) {
                $formErrors[] = '<div class="alert alert-danger">user name cant not be more less 4 character</div>';
            }
            if (empty($fullname)) {

                $formErrors[] = '<div class="alert alert-danger">full name cant not be empty</div>';
            }
            if (empty($email)) {
                $formErrors[] = '<div class="alert alert-danger">email cant not be empty</div>';
            }

            foreach ($formErrors as $eror) {

                echo $eror . '<br>';
            }
            if (empty($formErrors)) {

                $stmt = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ? LIMIT 1");
                $stmt->execute(array($username, $id));
                $row = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($count == 1) {
                    $errorMsg = "<div class='alert alert-danger'>Sorry this name exists</div>";

                    redirectHome($errorMsg, 3);
                } else {




                    $stmt = $con->prepare("UPDATE users SET Username =? ,Email = ?, FullName = ? ,Password = ? WHERE UserID = ?");
                    $stmt->execute(array($username, $email, $fullname, $pass, $id));

                    $errorMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " record updated </div>";
                    redirectHome($errorMsg, 3);
                }
            }
        } else {

            $errorMsg = "<div class='alert alert-danger'>you can't access update</div>";

            redirectHome($errorMsg, 3);
        }

        echo '</div>';
    } elseif ($do == 'Delete') {
        echo '<h1 class="text-center">Delete Member</h1>';
        echo '<div class="container">';
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        // echo var_dump($userid);
        // $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        // $stmt->execute(array($userid));
        // $count = $stmt->rowCount();
        // echo $count;

        $check = checkItem('UserID', 'users', $userid);
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM users WHERE UserID = :xuserid");
            $stmt->bindParam(":xuserid", $userid);
            $stmt->execute();

            echo '<div class="container">';

            $errorMsg = "<div class='alert alert-success'> one record deleted</div>";

            redirectHome($errorMsg, 3);
            echo "</div>";
        } else {
            echo '<div class="container">';

            $errorMsg = "<div class='container'><div class='alert alert-danger'> invalid id  </div></div>";
            redirectHome($errorMsg, 3);
            echo "</div>";
        }
        echo '</div>';
    } elseif ($do == 'Activate') {
        echo '<h1 class="text-center">Activate Member</h1>';
        echo '<div class="container">';
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $check = checkItem('UserID', 'users', $userid);
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
            $stmt->execute(array($userid));

            echo '<div class="container">';

            $errorMsg = "<div class='alert alert-success'> user has been activate</div>";

            redirectHome($errorMsg, 3);
            echo "</div>";
        } else {
            echo '<div class="container">';

            $errorMsg = "<div class='container'><div class='alert alert-danger'> invalid id  </div></div>";
            redirectHome($errorMsg, 3);
            echo "</div>";
        }
        echo '</div>';
    }

    include $tpl . "footer.php";
} else {

    header('Location: index.php');
    exit();
}
