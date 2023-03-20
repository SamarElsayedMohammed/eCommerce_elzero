<?php
session_start();
$pageTitle = 'Item Page';
include 'init.php';
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
$stmt = $con->prepare("SELECT items.* , categories.Name AS Category_name , users.Username AS member_name FROM items INNER JOIN categories ON categories.ID = items.Cat_ID INNER JOIN users ON users.UserID = Member_ID WHERE ITem_ID =? AND Approve = 1");
$stmt->execute(array($itemid));
$row = $stmt->fetch();
$item = $stmt->rowCount();

if ($item > 0) { ?>

    <h1 class="text-center"><?php echo $row['Name'] ?> Details</h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-responsive img-thumbnail center-block" src="layout/images/img.png" alt="" />
            </div>
            <div class="col-md-9 item-info">
                <h2><?php echo $row['Name'] ?></h2>
                <p><?php echo $row['Description'] ?></p>
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Added Date</span>: <?php echo $row['Add_Date'] ?>
                    </li>
                    <li>
                        <i class="fa fa-money fa-fw"></i>
                        <span>Price</span>: <?php echo $row['Price'] ?>
                    </li>
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span>Country</span>: <?php echo $row['Country_Made'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Category</span>: <a href="categories.php?pageid=<?php echo $row['Cat_ID'] ?>"><?php echo $row['Category_name'] ?></a>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Added By</span>: <a href="#"><?php echo $row['member_name'] ?></a>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Tags</span>:
                        <?php
                        $allTags = explode(',', $row['Tags']);
                        foreach ($allTags as $tag) {
                            $tag = str_replace(" ", "", $tag);
                            $lowerTage = strtolower($tag);
                            if (!empty($tag)) {
                                
                                echo "<a class='tags' href='tags.php?name=" . $lowerTage . "'>" . $tag . "</a>";
                            }
                        }
                        ?>
                    </li>
                </ul>
            </div>

        </div>
        <hr class="custom-hr">
        <?php if (isset($_SESSION['user'])) { ?>
            <div class="row">
                <div class="col-md-offset-3">
                    <div class="add-comment">
                        <h3>Add Your comment</h3>
                        <form action="<?php $_SERVER['PHP_SELF'] . '?itemid=' . $row['Item_ID'] ?>" method="POST">
                            <textarea name="comment" required></textarea>
                            <input type="submit" class="btn btn-primary" value="Add Comment">
                        </form>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                            $userid = $_SESSION['uid'];
                            $itemid = $row['Item_ID'];

                            if (!empty($comment)) {
                                $stmt = $con->prepare("INSERT INTO comments(comments,status,comment_date,item_id,user_id) VALUES(:xcomments , 0 ,Now() ,:xitemid ,:xuserid)");

                                $stmt->execute(array(
                                    'xcomments' => $comment,
                                    'xitemid' => $itemid,
                                    'xuserid' => $userid
                                ));
                                if ($stmt) {
                                    echo '<div class="alert alert-success"> comment added</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger"> comment can not be empty</div>';
                            }
                        }

                        ?>
                    </div>
                </div>
            </div>
        <?php } else {
            echo '<a href="login.php">Login</a> Or <a href="login.php">Register</a> To Add Comment';
        } ?>
        <hr class="custom-hr">
        <?php
        $stmt = $con->prepare("SELECT comments.*, users.Username as user_name FROM comments INNER JOIN users ON users.UserID = comments.user_id WHERE item_id = ? AND status = 1 ORDER BY c_id DESC");
        $stmt->execute(array($row['Item_ID']));
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
        ?>
            <div class="comment-box">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <img class="img-responsive img-circle img-thumbnail center-block" src="layout/images/img.png" alt="" />
                        <?php echo $row['user_name'] . '<br>'; ?>
                    </div>
                    <div class="col-sm-10">
                        <div class="lead">
                            <p><?php echo $row['comments'] . '<br>'; ?></p>
                            <span><?php echo $row['comment_date'] . '<br>'; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="custom-hr">
        <?php } ?>
    </div>
<?php
} else {
    echo '<div class="container">';


    $errorMsg = "<div class='alert alert-danger'>This id not exist or item watting for approve </div>";

    redirectHome($errorMsg, 3);
    echo '</div>';
}
?>
<?php include $tpl . 'footer.php'; ?>