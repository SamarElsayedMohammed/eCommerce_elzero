<?php
session_start();
$pageTitle = 'Profile Page';
include 'init.php';
if (isset($_SESSION['user'])) {
    $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();
    $id = $info['UserID'];
    // print_r($info);
?>

    <h1 class="text-center">My Profile</h1>

    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    my information
                </div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                        <li><i class="fa fa-unlock-alt fa-fw"></i><span>Name</span>: <?php echo $info['Username'] ?></li>
                        <li><i class="fa fa-envelope-o fa-fw"></i><span>Email</span>: <?php echo $info['Email'] ?></li>
                        <li><i class="fa fa-unlock-alt fa-fw"></i><span>Full Name</span>: <?php echo $info['FullName'] ?></li>
                        <li><i class="fa fa-unlock-alt fa-fw"></i><span>Register Date</span>: <?php echo $info['Date'] ?></li>
                        <li><i class="fa fa-unlock-alt fa-fw"></i><span>Favourite category</span>: </li>
                    </ul>
                    <a href="#" class="btn btn-default">Edit Profile</a>
                </div>
            </div>

        </div>
    </div>
    <div class="my-ads block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    my ads
                </div>
                <div class="panel-body">
                    <?php

                    $items = getAllFrom("*", "items", "where Member_ID = {$id}", "", "Item_ID");
                    if (!empty($items)) {
                        echo '<div class="row">';
                        foreach ($items as $item) {
                            echo '<div class="col-sm-6 col-md-3">';
                            echo ' <div class="thumbnail item-box">';
                            if ($item['Approve'] == 0) {
                                echo '<span class="approve-status">not approved</span>';
                            }
                            echo '<span class="price-tag">$ ' . $item['Price'] . '</span>';
                            echo '<img class="img-responsive" src="layout/images/img.png" alt="" />';
                            echo '<div class="caption" >';
                            echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
                            echo '<p>' . $item['Description'] . '</p>';
                            echo '<div class="date">' . $item['Add_Date'] . '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo 'no ads to show';
                        echo ' <a href="newad.php">New Add</a>';
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
    <div class="my-comments block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    latest comments
                </div>
                <div class="panel-body">
                    <?php
                    $myCommets = getAllFrom("comments", "comments", "where user_id = $id", "", "c_id");
                    if (!empty($myCommets)) {
                        foreach ($myCommets as $comment) {
                            echo $comment['comments'];
                        }
                    } else {
                        echo 'There\'s no comments for now';
                    }

                    ?>

                </div>
            </div>

        </div>
    </div>
<?php
} else {
    header('Location:login.php');
    exit();
}
include $tpl . 'footer.php'; ?>