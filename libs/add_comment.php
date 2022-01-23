<?php

session_name("blogProject");
session_start();

if (!isset($_SESSION['usr_id'])) {
    // Fehlerfall
    header("Location: index.php");
    exit();

}

/***************************************************************************************/
/**
 *
 * @file             view for index-page
 *
 *@author             Lysova <rekiraly@gmail.com>
 *@version             item eg. 1.1.1
 *@lastmodifydate    18-07-2019
 *@todo
 *
 */
require_once "../include/config.inc.php";
require_once "../include/db.inc.php";
require_once "../include/form.inc.php";
include_once "../include/dateTime.inc.php";

require_once "../class/iBlog.class.php";
require_once "../class/iCategory.class.php";
require_once "../class/iUser.class.php";

require_once "../class/Blog.class.php";
require_once "../class/Category.class.php";
require_once "../class/User.class.php";
/*********************************************/
/********** INCLUDE CONTROLLER FILE **********/
/*********************************************/

//if (isset($_POST['formsentNewComment'])) {
$name = $_SESSION['usr_id'];
$catName = $_POST['catName'];
$comment = $_POST['comment'];
//$cat = 1;

echo "<p><b>Line " . __LINE__ . "</b>: \$name: $name <i>(" . basename(__FILE__) . ")</i></p>";
echo "<p><b>Line " . __LINE__ . "</b>: \$comment: $comment <i>(" . basename(__FILE__) . ")</i></p>";
echo "<p><b>Line " . __LINE__ . "</b>: \$catName: $catName <i>(" . basename(__FILE__) . ")</i></p>";

//echo "<p><b>Line " . __LINE__ . "</b>: \$comment: $Uname <i>(" . basename(__FILE__) . ")</i></p>";

$pdo = dbConnect();
$mysqli = new Mysqli('localhost', 'root', '', 'blog_v1');
$mysqli->query("SET NAMES utf8");
$mysqli->query("INSERT INTO commentary(`com_inhalt`, `usr_id`, `cat_name`) VALUES('$comment','$name','$catName')");
