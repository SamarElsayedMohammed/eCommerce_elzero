<?php
session_start();
// print_r($_SESSION);
$pageTitle = 'Dashboard';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    // echo "welcome Admin ". $_SESSION['Username'];
    $numUsers = 5;
    $latestUsers = getLatest('*', 'users', 'UserID', $numUsers);
    $numItems = 5;
    $latestItems = getLatest('*', 'items', 'Item_ID', $numItems);
?>
    <div class="home-stats">
        <div class="container  text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total members
                            <span><a href="members.php"><?php echo countItem('UserID', 'users'); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending members
                            <span><a href="members.php?do=Manage&page=pending"><?php echo checkItem('RegStatus', 'users', 0); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span> <a href="items.php"><?php echo countItem('Item_ID', 'items'); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            Total Comments
                            <span><a href="comments.php"><?php echo countItem('c_id', 'comments'); ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="latest">
        <div class="container ">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i>
                            Latest <?php echo $numUsers; ?> Registered Users
                            <span class="pull-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                if (!empty($latestUsers)) {

                                    foreach ($latestUsers as $user) {
                                        echo '<li>' . $user['Username'] . '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '"><span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit ';
                                        if ($user['RegStatus'] == 0) {
                                            echo '<a href="members.php?do=Activate&userid=' . $user['UserID'] . '"class="btn btn-info pull-right"><i class="fa fa-close"></i>activate</a></td>';
                                        }
                                        echo '</span></a></li>';
                                    }
                                } else {
                                    echo 'There\'s No Items to show';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i>
                            Latest <?php echo $numItems; ?> Items
                            <span class="pull-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                if (!empty($latestItems)) {

                                    foreach ($latestItems as $item) {
                                        echo '<li>' . $item['Name'] . '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '"><span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit ';
                                        if ($item['Approve'] == 0) {
                                            echo '<a href="items.php?do=Approve&itemid=' . $item['Item_ID'] . '"class="btn btn-info pull-right"><i class="fa fa-check"></i>Approve</a></td>';
                                        }
                                        echo '</span></a></li>';
                                    }
                                } else {
                                    echo 'There\'s No Items to show';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- start latest comments  -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comment-o"></i>
                            Latest <?php echo $numItems; ?> Items
                            <span class="pull-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                            $stmt = $con->prepare("SELECT comments.*, users.Username as user_name FROM comments INNER JOIN users ON users.UserID = comments.user_id ORDER BY c_id DESC LIMIT $numItems");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            if (!empty($rows)) {
                                foreach ($rows as $row) {
                                    echo '<div class="comment-box">';
                                    echo '<a  href="members.php?do=Edit&userid=' . $row['user_id'] . '" class="member-n">' . $row['user_name'] . '</a>';

                                    echo '<div class="member-c">';
                                    echo '<p>' . $row['comments'] . '</p>';
                                    echo '<a href="comments.php?do=Edit&comid=' . $row['c_id'] . '" class="btn btn-xs btn-success"><i class="fa fa-edit"></i>
                                        Edit</a>
                                        <a href="comments.php?do=Delete&comid=' . $row['c_id'] . '"  class="btn btn-danger btn-xs confirm"><i class="fa fa-close"></i>Delete</a>';
                                    if ($row['status'] == 0) {
                                        echo ' <a href="comments.php?do=Approve&comid=' . $row['c_id'] . '"class="btn btn-xs btn-info"><i class="fa fa-check"></i>Approve</a></div>';
                                    }

                                    echo '</div>';
                                }
                            } else {
                                echo 'There\'s No Items to show';
                            }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end latest comments  -->

        </div>
    </div>
<?php
    include $tpl . "footer.php";
} else {
    // echo "you not authorized to show this page";
    header('Location: index.php');
    exit();
}
