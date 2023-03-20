<?php
session_start();
$pageTitle = 'Create New Add';
include 'init.php';
if (isset($_SESSION['user'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $formErors = array();

        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $category = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT);
        $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

        if (strlen($name) < 4) {
            $formErors[] = 'Item Title Must Be At Least 4 characters';
        }
        if (strlen($desc) < 10) {
            $formErors[] = 'Item Description Must Be At Least 10 characters';
        }
        if (strlen($country) < 2) {
            $formErors[] = 'Item Country Must Be At Least 2 characters';
        }
        if (empty($price)) {
            $formErors[] = 'Item Price Must Be Not Empty';
        }
        if (empty($status)) {
            $formErors[] = 'Item Status Must Be Not Empty';
        }
        if (empty($category)) {
            $formErors[] = 'Item Category Must Be Not Empty';
        }

        if (empty($formErrors)) {

            $stmt = $con->prepare("INSERT INTO items ( Name ,Description ,Price  ,Country_Made ,Add_Date ,Status,Member_ID,Cat_ID,Tags) VALUES(:xname ,:xdescription,:xprice ,:xcountry ,now() ,:xstauts,:xmember,:xcategory,:xtags)");
            $stmt->execute(array(
                'xname'           => $name,
                'xdescription'    => $desc,
                'xprice'          => $price,
                'xcountry'        => $country,
                'xstauts'         => $status,
                'xmember'         => $_SESSION['uid'],
                'xcategory'       => $category,
                'xtags'           => $tags,
            ));
            if ($stmt) {
                $successMsg = 'Item Added successfuly';
            }
        }
    }


?>

    <h1 class="text-center"><?php echo $pageTitle ?></h1>

    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?php echo $pageTitle ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" class="form-horizontal main-form">
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control live" name="name" required='required' placeholder="Enter Item name" data-class=".live-name">
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control live" name="description" required='required' placeholder="Enter Item description" data-class=".live-desc">
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Price</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control live" name="price" required='required' placeholder="Enter Item Price" data-class=".live-price">
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Country of made</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control" name="country" required='required' placeholder="Country of made">
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Categories</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select class="form-control" name="category_id" required>
                                            <option value="">..</option>
                                            <?php
                                            $categories = getAllFrom('*', 'categories', '', '', 'ID');
                                            foreach ($categories as $category) {
                                                echo "<option value='" . $category['ID'] . "'>" . $category['Name'] . "</option>
                                                        ";
                                            }

                                            ?>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select class="form-control" name="status" required>
                                            <option value="">..</option>
                                            <option value="1">New</option>
                                            <option value="2">Like New</option>
                                            <option value="3">Used</option>
                                            <option value="4">Very Old</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control" name="tags" placeholder="Enter Item Tags">
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-sm-offset-3 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-sm" value="Add Item" id="">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail item-box live-preview">
                                <span class="price-tag">$<span class="live-price">0</span></span>
                                <img class="img-responsive" src="layout/images/img.png" alt="" />
                                <div class="caption">
                                    <h3 class="live-name">title</h3>
                                    <p class="live-desc">Description</p>
                                </div>
                            </div>
                        </div>



                    </div>
                    <!-- end signup form  -->

                    <?php
                    if (!empty($formErors)) {
                        foreach ($formErors as $error) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }
                    if (isset($successMsg)) {
                        echo '<div class="alert alert-success">' . $successMsg . '</div>';
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