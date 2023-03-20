<?php
/*
--get all record
--
*/

function getAllFrom($field , $table ,$where=NULL , $and = null ,$orderfield , $ordering ="DESC")
{
    
    global $con;
    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
    
    $getAll->execute();
    
    $rows = $getAll->fetchAll();
    
    return $rows;
}

/*
--get categories record
--
*/

function getCat()
{
    
    global $con;
    
    $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID");
    
    $getCat->execute();
    
    $rows = $getCat->fetchAll();
    
    return $rows;
}


/*
--check item in database
--$select => item that select from table in data base
--$from => table name
--$value =>value of select 

*/

function checkItem($select, $from, $value)
{
    global $con;
    $statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

    $statment->execute(array($value));

    $count = $statment->rowCount();

    return $count;
}

function getItems($where , $value ,$approve=null)
{
    if ($approve == null) {
        $sql = 'AND Approve = 1';
    }else{
        $sql =null;
    }
    global $con;
    $getItems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC");

    $getItems->execute(array($value));

    return $getItems->fetchAll();
}

/*
--function to check if user is active or not
*/
function checkUserStatus($user ){
    global $con;
    $stmt = $con->prepare("SELECT 
                                Username ,RegStatus 
                            FROM 
                                users
                            WHERE
                                Username =? 
                            AND
                                RegStatus = 0");
    $stmt->execute(array($user));
    $status = $stmt->rowCount();
    return $status;
}

// ----------------------------------------------------
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

/*
--count of item function v1
--item => item to count
--table => table to choose from
*/
