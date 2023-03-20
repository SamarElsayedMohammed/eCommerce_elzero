<?php

session_start();
$pageTitle = 'Categories';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        $sort_array = array("ASC", "DESC");

        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        } else {
            $sort = 'ASC';
        }
        $cats = getAllFrom("*", "categories", "WHERE Parent = 0", "", "Arrange", $sort);
?>

        <h1 class="text-center">Manage Category</h1>
        <div class="container categories">
            <a href="categories.php?do=Add" class="btn btn-primary add-btn"> <i class="fa fa-plus"></i> New Category</a>
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-edit"></i>Manage Categories
                    <div class="option pull-right">
                        <i class="fa fa-sort"></i> Ordering : [
                        <a class="<?php if ($sort == 'ASC') {
                                        echo 'active';
                                    } ?>" href="?sort=ASC">Asc</a> |
                        <a class="<?php if ($sort == 'DESC') {
                                        echo 'active';
                                    } ?>" href="?sort=DESC">Desc</a> ]
                        <i class="fa fa-eye"></i>View : [
                        <span class="active" data-view="full">Full</span> |
                        <span data-view="classic">Classic</span> ]
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    foreach ($cats as $cat) {
                        echo '<div class="cat">';
                        echo "<div class='hidden-buttons'>";
                        echo "<a href='?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>";
                        echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>";
                        echo "</div>";
                        echo '<h3 >' . $cat['Name'] . '</h3>';
                        echo '<div class="full-view">';
                        echo '<p>';
                        if ($cat['Description'] == '') {
                            echo 'This category has no description';
                        } else {
                            echo $cat['Description'];
                        }
                        echo '</p>';
                        if ($cat['visibility'] == 1) {
                            echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>';
                        } else {
                            echo '<span class="visibility visible"><i class="fa fa-eye-slash"></i> Visibile</span>';
                        }
                        if ($cat['Allow_Comment'] == 0) {
                            echo '<span class="comminting"><i class="fa fa-check"></i> Comminting Enable</span>';
                        } else {
                            echo '<span class="comminting com-disable"><i class="fa fa-close"></i> Comminting Disable</span>';
                        }
                        if ($cat['Allow_Ads'] == 0) {
                            echo '<span class="advertises"><i class="fa fa-check"></i> Ads Enable</span>';
                        } else {
                            echo '<span class="advertises ads-disable"><i class="fa fa-close"></i> Ads Disable</span>';
                        }


                        $subCats = getAllFrom("*", "categories", "where Parent = {$cat['ID']}", "", "Arrange");
                        if (!empty($subCats)) {
                            echo '<h3 class = "child-head">Sub Categories</h3>';
                            echo '<ul class="list-unstyled child-cats">';
                            foreach ($subCats as $subCat) {
                                echo "<li class='child-link'>
                                <a href='?do=Edit&catid=" . $subCat['ID'] . "'>" . $subCat['Name'] . "</a>
                                <a href='categories.php?do=Delete&catid=" . $subCat['ID'] . "' class='confirm show-delete'>Delete</a>
                            </li>";
                            }
                            echo '</ul>';
                        }
                        echo '</div>';
                        echo '</div>';
                        echo '<hr/>';
                    }

                    ?>
                </div>
            </div>
        </div>


    <?php


    } elseif ($do == 'Add') { ?>

        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form action="?do=Insert" method="POST" class="form-horizontal">
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="name" id="" autocomplete="off" required='required' placeholder="Enter category name">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="description" placeholder="Descripe category">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="ordering" placeholder="ordering categories">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Parent</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="parent" id="">
                            <option value="0">None</option>
                            <?php
                            $allCats = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID", "ASC");
                            foreach ($allCats as $cat) {
                                echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] . '</option>';
                            }
                            ?>

                        </select>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">visible</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <label for="yes">
                                <input type="radio" name="visibility" value="0" id="yes" checked> Yes
                            </label>
                        </div>
                        <div>
                            <label for="no">
                                <input type="radio" name="visibility" value="1" id="no"> No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <label for="com-yes">
                                <input type="radio" name="commenting" value="0" id="com-yes" checked> Yes
                            </label>
                        </div>
                        <div>
                            <label for="com-no">
                                <input type="radio" name="commenting" value="1" id="com-no"> No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <label for="ads-yes">
                                <input type="radio" name="ads" value="0" id="ads-yes" checked> Yes
                            </label>
                        </div>
                        <div>
                            <label for="ads-no">
                                <input type="radio" name="ads" value="1" id="ads-no"> No
                            </label>
                        </div>
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

            echo '<h1 class="text-center">Update Category</h1>';
            echo '<div class="container">';

            $name = $_POST['name'];
            $description = $_POST['description'];
            $arrang = $_POST['ordering'];
            $visibility = $_POST['visibility'];
            $Allow_Comment = $_POST['commenting'];
            $Allow_Ads = $_POST['ads'];
            $parent = $_POST['parent'];
            // echo $name . '<br>' . $description . '<br>' . $arrang . '<br>' . $visibility . '<br>' . $Allow_Comment . '<br>' . $Allow_Ads;
            // print($parent);
            //validation
            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = 'name cant not be empty';
            }
            if (strlen($name) > 20) {
                $formErrors[] = 'user name cant not be more than 20 character';
            }
            if (strlen($name) < 4) {
                $formErrors[] = 'user name cant not be more less 4 character';
            }
            foreach ($formErrors as $eror) {

                echo '<div class="alert alert-danger">' . $eror . '</div>';
            }
            if (empty($formErrors)) {

                $check = checkItem("Name", "categories", $name);

                if ($check >= 1) {

                    $errorMsg = '<div class="alert alert-danger">Name is eXist</div>';
                    redirectHome($errorMsg, 3);
                } else {


                    $stmt = $con->prepare("INSERT INTO categories ( Name ,Description , Arrange  ,visibility ,Allow_Comment ,Allow_Ads,Parent ) VALUES(:xname ,:xdescription ,:xarrange ,:xvisibility,:xallow_comment ,:xallow_ads,:xparent)");
                    $stmt->execute(array(
                        'xname'     =>  $name,
                        'xdescription'    => $description,
                        'xarrange' => $arrang,
                        'xvisibility' => $visibility,
                        'xallow_comment' => $Allow_Comment,
                        'xallow_ads' => $Allow_Ads,
                        'xparent' => $parent
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

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? LIMIT 1");
        $stmt->execute(array($catid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) {
        ?>
            <h1 class="text-center">Edit Category</h1>
            <div class="container">
                <form action="?do=Update" method="POST" class="form-horizontal">
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="name" value="<?php echo $row['Name'] ?>" autocomplete="off" required='required' placeholder="Enter category name">
                            <input type="hidden" name="catid" value="<?php echo $catid; ?>">

                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="description" value="<?php echo $row['Description'] ?>" placeholder="Descripe category">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="ordering" value="<?php echo $row['Arrange'] ?>" placeholder="ordering categories">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Parent <?php echo $row['Parent'] ?></label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent" id="">
                                <option value="0">None</option>
                                <?php
                                $allCats2 = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID", "ASC");
                                foreach ($allCats2 as $cat) {
                                    echo '<option value="' . $cat['ID'] . '"';
                                    if ($cat['ID']  == $row['Parent']) {
                                        echo ' selected';
                                    }
                                    echo '>' .$cat['Name'] . '</option>';
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">visible</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <label for="yes">
                                    <input type="radio" name="visibility" value="0" id="yes" <?php
                                                                                                if ($row['visibility'] == 0) {
                                                                                                    echo 'checked';
                                                                                                }
                                                                                                ?>> Yes
                                </label>
                            </div>
                            <div>
                                <label for="no">
                                    <input type="radio" name="visibility" value="1" id="no" <?php
                                                                                            if ($row['visibility'] == 1) {
                                                                                                echo 'checked';
                                                                                            }
                                                                                            ?>> No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <label for="com-yes">
                                    <input type="radio" name="commenting" value="0" id="com-yes" <?php
                                                                                                    if ($row['Allow_Comment'] == 0) {
                                                                                                        echo 'checked';
                                                                                                    }
                                                                                                    ?>> Yes
                                </label>
                            </div>
                            <div>
                                <label for="com-no">
                                    <input type="radio" name="commenting" value="1" id="com-no" <?php
                                                                                                if ($row['Allow_Comment'] == 1) {
                                                                                                    echo 'checked';
                                                                                                }
                                                                                                ?>> No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <label for="ads-yes">
                                    <input type="radio" name="ads" value="0" id="ads-yes" <?php
                                                                                            if ($row['Allow_Ads'] == 0) {
                                                                                                echo 'checked';
                                                                                            }
                                                                                            ?>> Yes
                                </label>
                            </div>
                            <div>
                                <label for="ads-no">
                                    <input type="radio" name="ads" value="1" id="ads-no" <?php
                                                                                            if ($row['Allow_Ads'] == 1) {
                                                                                                echo 'checked';
                                                                                            }
                                                                                            ?>> No
                                </label>
                            </div>
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

            $id = $_POST['catid'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $order = $_POST['ordering'];
            $visible = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];
            $parent= $_POST['parent'];

            $stmt = $con->prepare("UPDATE categories SET Name =? ,Description = ?, Arrange = ? ,Parent = ?,visibility  = ? ,Allow_Comment =? ,Allow_Ads =? WHERE ID = ?");
            $stmt->execute(array(
                $name, $description, $order, $parent ,$visible, $comment, $ads, $id
            ));

            $errorMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " record updated </div>";
            redirectHome($errorMsg, 3);
        } else {

            $errorMsg = "<div class='alert alert-danger'>you can't access update</div>";

            redirectHome($errorMsg, 6);
        }

        echo '</div>';
    } elseif ($do == 'Delete') {


        echo '<h1 class="text-center">Delete Category</h1>';
        echo '<div class="container">';
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        $check = checkItem('ID', 'categories', $catid);
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :xcatid");
            $stmt->bindParam(":xcatid", $catid);
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
        # code...
    }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
