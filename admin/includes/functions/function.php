<?php

/*
--get all record
--
*/

function getAllFrom($field, $table, $where = NULL, $and = null, $orderfield, $ordering = "DESC")
{

    global $con;
    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

    $getAll->execute();

    $rows = $getAll->fetchAll();

    return $rows;
}



// Title function
function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo "default";
    }
}

// errors function

function redirectHome($theMsg, $url = null, $seconds = 3)
{

    if ($url === null) {
        $url = 'index.php';
    } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';
        } else {
            $url = 'index.php';
            $link = 'Home Page';
        }
    }
    echo $theMsg;
    echo "<div class='alert alert-info' >You will Redirect to $link after $seconds Seconds</div>";

    header("refresh:$seconds;url=$url");
    exit();
}

// check item in database
/*
$select => item that select from table in data base
$from => table name
$value =>value of select 
*/

function checkItem($select, $from, $value)
{
    global $con;
    $statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

    $statment->execute(array($value));

    $count = $statment->rowCount();

    return $count;
}

/*
--count of item function v1
--item => item to count
--table => table to choose from
*/
function countItem($item, $table)
{

    global $con;

    // $query='';

    // if (isset($col) && $col == 'pending') {
    //     $query ='AND RegStatus = 0';
    // }

    // $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table WHERE GroupID = 0 $query");
    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

    $stmt2->execute();

    return $stmt2->fetchColumn();
}

/*
--get latest record
--
*/

function getLatest($select, $table, $order, $limit = 5)
{

    global $con;

    $getStat = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

    $getStat->execute();

    $rows = $getStat->fetchAll();

    return $rows;
}
