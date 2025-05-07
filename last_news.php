<?php 

session_name("blogProject");
session_start();
require_once "include/config.inc.php";
require_once "include/db.inc.php";
require_once "include/form.inc.php";
include_once "include/dateTime.inc.php";

require_once "class/iBlog.class.php";
require_once "class/iCategory.class.php";
require_once "class/iUser.class.php";
require_once "class/iThema.class.php";

require_once "class/Blog.class.php";
require_once "class/Category.class.php";
require_once "class/User.class.php";
require_once "class/Thema.class.php";
$thema_id = 0;
$pdo = dbConnect();   
$blogsArray = Blog::fetchAllBlogFromDb($pdo);            
if ($blogsArray){
                    
    foreach ($blogsArray as $blog){
    $dateTime = ($blog->getBlog_date());
    echo "<article class='blogEntry'>";
    echo "<a name='entry" . $blog->getBlog_id() . "'></a>";
    echo "<p class='fright'><a href='?action=showCategory&id=" . $blog->getCategory()->getCat_id() . "'>Thema: " . $blog->getCategory()->getThema()->getThema_name() . " - "  . $blog->getCategory()->getCat_name() . "</a></p>";
    echo "<h2 class='clearer'>" . $blog->getBlog_headline() . "</h2>";
    echo "<a href='comment.php?category=" . $blog->getCategory()->getCat_name() . "'><i class='fa fa-comments-o' aria-hidden='true'></i></a>";
    echo "<p class='author'>" . $blog->getUser()->getFullname(). "(" . $blog->getUser()->getUsr_city() . ") schrieb am" . isoToEuDateTime($blog->getBlog_date())['date']. "um " . isoToEuDateTime($blog->getBlog_date())['time'] . "Uhr:</p>";         
    echo "<p class='blogContent'>";
    if ($blog->getBlog_image()){
        echo "<img class='" .$blog->getBlog_imageAlignment()."' src='" .$blog->getBlog_image() . "' alt='' title=''>";
        }
    echo nl2br( $blog->getBlog_content());
    echo "</p>";
    echo "<div class='clearer'></div>";
    echo "<br>";
    echo "<hr>";
    echo "</article>";
    }
} else{
        echo "<p class='info'> Noch keine Blogeinträge vorhanden.</p>";
       }
           
?>

