<div class="upper-bar">
    <div class="container">
        <?php
        if (isset($_SESSION['user'])) { ?>
            <img class="img-circle img-thumbnail my-image" src="layout/images/img.png" alt="" />
            <div class="btn-group my-info">
                <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <?php echo $_SESSION['user'] ?>
                    <span class="caret"></span>
                </span>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="newad.php">New Item</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>


            <?php
            $userStatus = checkUserStatus($sessionUser);
            if ($userStatus == 1) {
                echo "your membership need to activate by admin";
            }
        } else {
            ?>
            <a href="login.php">
                <span class="pull-right">Login/SignUp</span>
            </a>
        <?php } ?>
    </div>
</div>
<nav class="navbar navbar-inverse">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><?php echo lang('HOME') ?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="nav navbar-nav navbar-right">
                <?php
                $allCats = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID", "ASC");
                foreach ($allCats as $cat) {

                    echo '<li><a href="categories.php?pageid=' . $cat['ID'] . '">' . $cat['Name'] . '</a></li>';
                }
                ?>

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>