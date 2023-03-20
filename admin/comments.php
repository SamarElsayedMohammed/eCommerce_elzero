<?php
session_start();
if (isset($_SESSION['Username'])) {
    $pageTitle = 'Comments';
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') { /* manage page*/

        $stmt = $con->prepare("SELECT comments.* ,items.Name as item_name , users.Username as user_name FROM comments INNER JOIN items ON items.Item_ID = comments.item_id INNER JOIN users ON users.UserID = comments.user_id ORDER BY c_id DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
?>

        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Item</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Actions</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['c_id'] . '</td>';
                        echo '<td>' . $row['comments'] . '</td>';
                        echo '<td>' . $row['item_name'] . '</td>';
                        echo '<td>' . $row['user_name'] . '</td>';
                        echo '<td>' . $row['comment_date'] .  '</td>';
                        echo '<td><a href="comments.php?do=Edit&comid=' . $row['c_id'] . '" class="btn btn-success"><i class="fa fa-edit"></i>
                        Edit</a>
                        <a href="comments.php?do=Delete&comid=' . $row['c_id'] . '"  class="btn btn-danger confirm"><i class="fa fa-close"></i>Delete</a>';
                        if ($row['status'] == 0) {
                            echo ' <a href="comments.php?do=Approve&comid=' . $row['c_id'] . '"class="btn btn-info"><i class="fa fa-check"></i>Approve</a></td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>

        <?php

    } elseif ($do == 'Edit') {

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
        $stmt->execute(array($comid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form action="?do=Update" method="POST" class="form-horizontal">
                    <input type="hidden" name="comid" value="<?php echo $comid; ?>">
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-6">
                            <textarea class="form-control" name="comment"><?php echo $row['comments'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary btn-lg" value="save">
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
            echo '<h1 class="text-center">Update Comment</h1>';
            echo '<div class="container">';

            $comid = $_POST['comid'];
            $comment = $_POST['comment'];

            //validation
            $formErrors = array();
            if (empty($comment)) {
                $formErrors[] = '<div class="alert alert-danger"></div>comment cant not be empty</div>';
            }

            foreach ($formErrors as $eror) {

                echo $eror . '<br>';
            }
            if (empty($formErrors)) {

                $stmt = $con->prepare("UPDATE comments SET comments =? WHERE c_id = ?");
                $stmt->execute(array($comment, $comid));

                $errorMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " record updated </div>";
                redirectHome($errorMsg, 3);
            }
        } else {

            $errorMsg = "<div class='alert alert-danger'>you can't access update</div>";

            redirectHome($errorMsg, 3);
        }

        echo '</div>';
    } elseif ($do == 'Delete') {
        echo '<h1 class="text-center">Delete Comment</h1>';
        echo '<div class="container">';
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
      

        $check = checkItem('c_id', 'comments', $comid);
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :xcomid");
            $stmt->bindParam(":xcomid", $comid);
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
    } elseif ($do == 'Approve') {
        echo '<h1 class="text-center">Approve Comment</h1>';
        echo '<div class="container">';
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        $check = checkItem('c_id', 'comments', $comid);
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
            $stmt->execute(array($comid));

            echo '<div class="container">';

            $errorMsg = "<div class='alert alert-success'> comment has been approve</div>";

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
