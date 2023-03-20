<?php

session_start();
$pageTitle = 'Items';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {
        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'pending') {
            $query = 'AND Approve  = 0';
        }

        $stmt = $con->prepare("SELECT items.* , categories.Name AS Category_name , users.Username AS member_name FROM items INNER JOIN categories ON categories.ID = items.Cat_ID INNER JOIN users ON users.UserID = Member_ID ORDER BY Item_ID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
?>

        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <a href="items.php?do=Add" class="btn btn-primary add-btn"> <i class="fa fa-plus"></i> New Iteme</a>
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Country</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>User Name</td>
                        <td>Actions</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['Item_ID'] . '</td>';
                        echo '<td>' . $row['Name'] . '</td>';
                        echo '<td>' . $row['Description'] . '</td>';
                        echo '<td>' . $row['Price'] . '</td>';
                        echo '<td>' . $row['Country_Made'] . '</td>';
                        echo '<td>' . $row['Add_Date'] .  '</td>';
                        echo '<td>' . $row['Category_name'] .  '</td>';
                        echo '<td>' . $row['member_name'] .  '</td>';
                        echo '<td><a href="items.php?do=Edit&itemid=' . $row['Item_ID'] . '" class="btn btn-success"><i class="fa fa-edit"></i>
                        Edit</a>
                        <a href="items.php?do=Delete&itemid=' . $row['Item_ID'] . '"  class="btn btn-danger confirm"><i class="fa fa-close"></i>Delete</a>';
                        if ($row['Approve'] == 0) {
                            echo ' <a href="items.php?do=Approve&itemid=' . $row['Item_ID'] . '"class="btn btn-info"><i class="fa fa-check"></i>Approve</a></td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>

    <?php
    } elseif ($do == 'Add') {  ?>
        <h1 class="text-center">Add New Item</h1>
        <div class="container">
            <form action="?do=Insert" method="POST" class="form-horizontal">
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="name" required='required' placeholder="Enter Item name">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="description" required='required' placeholder="Enter Item description">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="price" required='required' placeholder="Enter Item Price">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Country of made</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="country" required='required' placeholder="Country of made">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="member_id">
                            <option value="0">..</option>
                            <?php
                            $users = getAllFrom("*", "users", "", "", "UserID");

                            foreach ($users as $user) {
                                echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>
                                    ";
                            }

                            ?>

                        </select>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Categories</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="category_id">
                            <option value="0">..</option>
                            <?php
                            $categories = getAllFrom("*", "categories", "where Parent = 0", "", "ID");
                            foreach ($categories as $category) {
                                echo "<option value='" . $category['ID'] . "'>" . $category['Name'] . "</option>
                                    ";
                                $subCats = getAllFrom("*", "categories", "where Parent = {$category['ID']}", "", "ID");
                                foreach ($subCats as $subCat) {
                                    echo "<option value='" . $subCat['ID'] . "'> -----" . $subCat['Name'] . "</option>";
                                }
                            }

                            ?>

                        </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="status">
                            <option value="0">..</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Old</option>
                        </select>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="tags" required='required' placeholder="Enter Item Tags">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" class="btn btn-primary btn-sm" value="Add Item" id="">
                    </div>
                </div>
            </form>
        </div>
        <?php
    } elseif ($do == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center">Update Member</h1>';
            echo '<div class="container">';

            $name = $_POST['name'];
            $desc = $_POST['description'];
            $pric = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $category = $_POST['category_id'];
            $member = $_POST['member_id'];
            $tags = $_POST['tags'];


            // echo $email . '<br>' . $username . '<br>' . $fullname . '<br>' . $password;

            //validation
            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = 'Name can\'t not be <strong>empty</strong>';
            }
            if (strlen($name) > 20) {
                $formErrors[] = 'name can\'t not be more than 20 character';
            }
            if (strlen($name) <= 2) {
                $formErrors[] = 'name can\'t not be less than or equal  2 character';
            }
            if (empty($desc)) {

                $formErrors[] = 'description can\'t not be <strong>empty</strong>';
            }
            if (empty($pric)) {

                $formErrors[] = 'price can\'t not be <strong>empty</strong>';
            }
            if (empty($country)) {
                $formErrors[] = 'country can\'t not be <strong>empty</strong>';
            }
            if (($status == 0)) {
                $formErrors[] = 'status can\'t not be <strong>empty</strong>';
            }
            if ($category == 0) {
                $formErrors[] = 'category can\'t not be <strong>empty</strong>';
            }
            if ($member == 0) {
                $formErrors[] = 'member can\'t not be <strong>empty</strong>';
            }


            foreach ($formErrors as $eror) {

                echo '<div class="alert alert-danger">' . $eror . '</div>';
            }
            if (empty($formErrors)) {

                $stmt = $con->prepare("INSERT INTO items ( Name ,Description ,Price  ,Country_Made ,Add_Date ,Status,Member_ID,Cat_ID,Tags) VALUES(:xname ,:xdescription,:xprice ,:xcountry ,now() ,:xstauts,:xmember,:xcategory,:xtags)");
                $stmt->execute(array(
                    'xname'           =>  $name,
                    'xdescription'    => $desc,
                    'xprice'          => $pric,
                    'xcountry'        => $country,
                    'xstauts'         => $status,
                    'xmember'         => $member,
                    'xcategory'         => $category,
                    'xtags'         => $tags,
                ));
                echo '<div class="container">';


                $errorMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " record inserted </div>";
                redirectHome($errorMsg, 3);
                echo '</div>';
            }
        } else {

            echo '<div class="container">';

            $errorMsg = "<div class='alert alert-danger'>you can't access Inser directly</div>";
            redirectHome($errorMsg, 3);
            echo '</div>';
        }
    } elseif ($do == 'Edit') {

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ? LIMIT 1");
        $stmt->execute(array($itemid));
        $row = $stmt->fetch();
        $item = $stmt->rowCount();

        if ($item > 0) { ?>

            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form action="?do=Update" method="POST" class="form-horizontal">
                    <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="name" required='required' placeholder="Enter Item name" value="<?php echo $row['Name']; ?>">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="description" required='required' placeholder="Enter Item description" value="<?php echo $row['Description']; ?>">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="price" required='required' placeholder="Enter Item Price" value="<?php echo $row['Price']; ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Country of made</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="country" required='required' placeholder="Country of made" value="<?php echo $row['Country_Made']; ?>">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="member_id">
                                <?php
                                $users = getAllFrom("*", "users", "", "", "UserID");
                                foreach ($users as $user) {
                                    echo "<option value='" . $user['UserID'] . "'";
                                    if ($row['Member_ID'] == $user['UserID']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $user['Username'] . "</option>
                                        ";
                                }

                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Categories</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="category_id">
                                <?php
                                $categories = getAllFrom("*", "categories", "where Parent = 0", "", "ID");
                                foreach ($categories as $category) {
                                    echo "<option value='" . $category['ID'] . "'";
                                    if ($row['Cat_ID'] == $category['ID']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $category['Name'] . "</option>
                                        ";
                                }

                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="status">
                                <option value="1" <?php if ($row['Status'] == 1) {
                                                        echo 'selected';
                                                    } ?>> New</option>
                                <option value="2" <?php if ($row['Status'] == 2) {
                                                        echo 'selected';
                                                    } ?>>Like New</option>
                                <option value="3" <?php if ($row['Status'] == 3) {
                                                        echo 'selected';
                                                    } ?>>Used</option>
                                <option value="4" <?php if ($row['Status'] == 4) {
                                                        echo 'selected';
                                                    } ?>>Very Old</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="tags" required='required' placeholder="Enter Item Tags"
                            value="<?php echo $row['Tags']; ?>">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary btn-sm" value="Save Item" id="">
                        </div>
                    </div>
                </form>

                <?php
                $stmt = $con->prepare("SELECT comments.*, users.Username as user_name FROM comments INNER JOIN users ON users.UserID = comments.user_id WHERE item_id = ?");
                $stmt->execute(array($itemid));
                $rows = $stmt->fetchAll();
                if (!empty($rows)) {

                ?>
                    <hr>
                    <h1 class="text-center">Manage [<?php echo $row['Name']; ?>] Comments</h1>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Comment</td>
                                <td>User Name</td>
                                <td>Added Date</td>
                                <td>Actions</td>
                            </tr>
                            <?php
                            foreach ($rows as $row) {
                                echo '<tr>';
                                echo '<td>' . $row['comments'] . '</td>';
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
                <?php } ?>
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
            echo '<h1 class="text-center">Update Item</h1>';
            echo '<div class="container">';

            $id = $_POST['itemid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $pric = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $category = $_POST['category_id'];
            $member = $_POST['member_id'];
            $tags = $_POST['tags'];


            // echo $id . $username . $fullname . $email;  

            //validation
            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = 'Name can\'t not be <strong>empty</strong>';
            }
            if (strlen($name) > 20) {
                $formErrors[] = 'name can\'t not be more than 20 character';
            }
            if (strlen($name) <= 2) {
                $formErrors[] = 'name can\'t not be less than or equal  2 character';
            }
            if (empty($desc)) {

                $formErrors[] = 'description can\'t not be <strong>empty</strong>';
            }
            if (empty($pric)) {

                $formErrors[] = 'price can\'t not be <strong>empty</strong>';
            }
            if (empty($country)) {
                $formErrors[] = 'country can\'t not be <strong>empty</strong>';
            }
            if (($status == 0)) {
                $formErrors[] = 'status can\'t not be <strong>empty</strong>';
            }
            if ($category == 0) {
                $formErrors[] = 'category can\'t not be <strong>empty</strong>';
            }
            if ($member == 0) {
                $formErrors[] = 'member can\'t not be <strong>empty</strong>';
            }


            foreach ($formErrors as $eror) {

                echo '<div class="alert alert-danger">' . $eror . '</div>';
            }
            if (empty($formErrors)) {

                $stmt = $con->prepare("UPDATE items SET Name =? ,Description = ?, Price = ? ,Country_Made = ? , Status =? ,Cat_ID = ? ,Member_ID = ? ,Tags = ? WHERE Item_ID = ?");
                $stmt->execute(array($name, $desc, $pric, $country, $status, $category, $member,$tags , $id));

                $errorMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " record updated </div>";
                redirectHome($errorMsg, 3);
            }
        } else {

            $errorMsg = "<div class='alert alert-danger'>you can't access update</div>";

            redirectHome($errorMsg, 3);
        }

        echo '</div>';
    } elseif ($do == 'Delete') {


        echo '<h1 class="text-center">Delete Item</h1>';
        echo '<div class="container">';
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $check = checkItem('Item_ID', 'items', $itemid);
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :xitemid");
            $stmt->bindParam(":xitemid", $itemid);
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

        echo '<h1 class="text-center">Approve Item</h1>';
        echo '<div class="container">';
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $check = checkItem('Item_ID', 'items', $itemid);
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
            $stmt->execute(array($itemid));

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
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
