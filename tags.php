<?php session_start();
include 'init.php'; ?>
<div class="container">


    <?php
    if (isset($_GET['name']) && !empty($_GET['name'])) {
        $tag = $_GET['name'];
        $items = getAllFrom("*", "items", "where Tags like '%$tag%'", "AND Approve = 1", "Item_ID");
        echo '<h1 class="text-center">"' . $tag . '" Tags Items</h1>';
        echo '<div class="row">';
        foreach ($items as $item) {
            echo '<div class="col-sm-6 col-md-3">';
            echo ' <div class="thumbnail item-box">';
            echo '<span class="price-tag">$' . $item['Price'] . '</span>';
            echo '<img class="img-responsive" src="layout/images/img.png" alt="" />';
            echo '<div class="caption" >';
            echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
            echo '<p>' . $item['Description'] . '</p>';
            echo '<div class="date">' . $item['Add_Date'] . '</div>';
            echo '</div>';

            echo '</div>';
            echo '</div>';
        }
    } else {
        echo 'you don\'t specify pageid';
    }
    ?>

</div>
</div>

<?php include $tpl . 'footer.php'; ?>