<?php
header("Content-Type: text/html; charset=UTF-8");
$count = $_POST['count'];
$catName = $_POST['catName'];
//echo "<p><b>Line " . __LINE__ . "</b>: \$catName: $catName <i>(" . basename(__FILE__) . ")</i></p>";

$mysqli = new Mysqli('localhost', 'root', '', 'blog_v1');
$mysqli->query("SET NAMES utf8");
$r = array();
$result = $mysqli->query("SELECT * FROM commentary  INNER JOIN user USING(usr_id) WHERE com_id > $count AND cat_name='$catName' /* ORDER BY com_id DESC */");
//$result = $mysqli->query("SELECT * FROM commentary JOIN user USING(usr_id) WHERE com_id > $count");

while ($row = $result->fetch_assoc()) {
    $r[] = $row;
}
if (empty($r)) {
    echo "empty";
} else {
    echo json_encode($r);
}
