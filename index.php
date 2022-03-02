<?php

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

/*********************************************/
/********** INCLUDE CONTROLLER FILE **********/
/*********************************************/

/********** INITIALIZE SESSION **********/

session_name("blogProject");
session_start();
if (!isset($_SESSION['usr_id'])) {
    session_destroy();
}

/***********************************/
/********** CONFIGURATION **********/
/***********************************/

require_once "include/config.inc.php";
require_once "include/db.inc.php";
require_once "include/form.inc.php";
include_once "include/dateTime.inc.php";

/********** INCLUDE CLASSES **********/

require_once "class/iBlog.class.php";
require_once "class/iCategory.class.php";
require_once "class/iUser.class.php";
require_once "class/iThema.class.php";

require_once "class/Blog.class.php";
require_once "class/Category.class.php";
require_once "class/User.class.php";
require_once "class/Thema.class.php";

/********** ESTABLISH DB CONNECTION **********/

$pdo = dbConnect();

/***************************************************************************************/

/******************************************/
/********** INITIALIZE VARIABLES **********/
/******************************************/

$loginMessage = null;
$expandBlogContent = false;
$category = null;
$user = null;
$blog = null;
$category_id = null;
$blogsArray=null;
$user = null;

/***************************************************************************************/

/************************************************/
/********** FETCH BLOG ENTRIES FORM DB **********/
/************************************************/

if (DEBUG) {
    echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Lade Blog-Einträge... <i>(" . basename(__FILE__) . ")</i></p>";
}

$blogsArray = Blog::fetchAllBlogFromDb($pdo);

if (DEBUG) {
    echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
}

if (DEBUG) {
    print_r($blogsArray);
}

if (DEBUG) {
    echo "</pre>";
}

/***************************************************************************************/

/***********************************************/
/********** URL-PARAMETERVERARBEITUNG **********/
/***********************************************/

// Schritt 1 URL: Prüfen, ob Parameter übergeben wurde
if (isset($_GET['action'])) {
    if (DEBUG) {
        echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL-Parameter 'action' wurde übergeben... <i>(" . basename(__FILE__) . ")</i></p>";
    }

    // Schritt 2 URL: Werte auslesen, entschärfen, DEBUG-Ausgabe
    $action = cleanString($_GET['action']);
    if (DEBUG) {
        echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$action = $action <i>(" . basename(__FILE__) . ")</i></p>";
    }

    // Schritt 3 URL: ggf. Verzweigung

    /********** LOGOUT **********/

    if ($_GET['action'] == "logout") {
        if (DEBUG) {
            echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: 'Logout' wird durchgeführt... <i>(" . basename(__FILE__) . ")</i></p>";
        }

        session_destroy();
        header("Location: index.php");
        exit();

        /********** KATEGORIENFILTER **********/

    } elseif ($action == "showCategory") {
        if (DEBUG) {
            echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Kategoriefilter aktiv... <i>(" . basename(__FILE__) . ")</i></p>";
        }

        $category_id = cleanString($_GET['id']);
       

        if(DEBUG) {
            echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Lade Blog-Einträge aus Kategorie $category_id... <i>(" . basename(__FILE__) . ")</i></p>";
            
        
        }

        $blogsArray = Blog::fetchBlogEntriesByCategoryFromDb($pdo, $category_id); 

        if (DEBUG) {
            echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
        }

        if (DEBUG) {
            print_r($blogsArray);
        }

        if (DEBUG) {
            echo "</pre>";
        }

    }elseif ($action == "showThemen") {
        if (DEBUG) {
            echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Kategoriefilter aktiv... <i>(" . basename(__FILE__) . ")</i></p>";
        }

        $thema_id = cleanString($_GET['id']);
       

        if(DEBUG) {
            echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Lade Blog-Einträge aus Kategorie $category_id... <i>(" . basename(__FILE__) . ")</i></p>";
            
        
        }

        $blogsArray = Blog::fetchBlogEntriesByThemaFromDb($pdo, $thema_id); //переделать

        if (DEBUG) {
            echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
        }

        if (DEBUG) {
            print_r($blogsArray);
        }

        if (DEBUG) {
            echo "</pre>";
        }

    }
}

/***************************************************************************************/

/******************************************/
/********** FORMULARVERARBEITUNG **********/
/******************************************/

/********** LOGIN **********/

// Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
if (isset($_POST['formsentLogin'])) {
    if (DEBUG) {
        echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Formular 'Login' wurde abgeschickt... <i>(" . basename(__FILE__) . ")</i></p>";
    }

    $user = new User();
    $user->setUsr_email($_POST['loginName']);
    $user->setUsr_password($_POST['loginPassword']);

    /*
    if(DEBUG)            echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
    if(DEBUG)            print_r($user);
    if(DEBUG)            echo "</pre>";
     */
    // Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
    $loginName = cleanString($_POST['loginName']);
    $loginPassword = cleanString($_POST['loginPassword']);

    if (DEBUG) {
        echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$loginName: $loginName <i>(" . basename(__FILE__) . ")</i></p>";
    }

    if (DEBUG) {
        echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$loginPassword: $loginPassword <i>(" . basename(__FILE__) . ")</i></p>";
    }

    // Schritt 3 FORM: ggf. Werte validieren
    $errorLoginName = checkEmail($user->getUsr_email());
    $errorLoginPassword = checkInputString($loginPassword);

    /********** ABSCHLIESSENDE FORMULARPRÜFUNG **********/

    if ($errorLoginName or $errorLoginPassword) {
        // Fehlerfall
        if (DEBUG) {
            echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Formular enthält noch Fehler! <i>(" . basename(__FILE__) . ")</i></p>";
        }

        $loginMessage = "<p class='error'>Benutzername oder Passwort falsch!</p>";

    } else {
        // Erfolgsfall
        if (DEBUG) {
            echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist fehlerfrei und wird nun verarbeitet... <i>(" . basename(__FILE__) . ")</i></p>";
        }

        // Schritt 4 FORM: Daten weiterverarbeiten

        /**********************************/
        /********** DB-OPERATION **********/
        /**********************************/

        /********** 1B. LOGINNAMEN PRÜFEN **********/

        // Prüfen, ob ein Datensatz geliefert wurde
        // Wenn ein Datensatz geliefert wurde, muss der Loginname korrekt sein

        if (!$user->fetchFromDb($pdo)) {
            // Fehlerfall
            if (DEBUG) {
                echo "<p class='debug err'>FEHLER: Loginname '" . $user->getUsr_email() . " existiert nicht in der DB!</p>\r\n";
            }

            $loginMessage = "<p class='error'>Logindaten sind ungültig!</p>";

        } else {
            // Erfolgsfall
            if (DEBUG) {
                echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Benutzername wurde in DB gefunden. <i>(" . basename(__FILE__) . ")</i></p>";
            }

            /********** PASSWORT PRÜFEN **********/

            if (!password_verify($loginPassword, $user->getUsr_password())) {
                // Fehlerfall
                if (DEBUG) {
                    echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Passwort stimmt nicht mit DB überein! <i>(" . basename(__FILE__) . ")</i></p>";
                }

                $loginMessage = "<p class='error'>Benutzername oder Passwort falsch!</p>";

            } else {
                // Erfolgsfall
                if (DEBUG) {
                    echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Passwort stimmt mit DB überein. LOGIN OK. <i>(" . basename(__FILE__) . ")</i></p>";
                }

                if (DEBUG) {
                    echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Userdaten werden in Session geschrieben... <i>(" . basename(__FILE__) . ")</i></p>";
                }

                /********** USERDATEN IN SESSION SCHREIBEN **********/
                session_start();

                $_SESSION['usr_id'] = $user->getUsr_id();
                $_SESSION['usr_firstname'] = $user->getUsr_firstname();
                $_SESSION['usr_lastname'] = $user->getUsr_lastname();

                /********** UMLEITUNG AUF DASHBOARD **********/

                header("Location: dashboard.php");
                exit();

            } // PASSWORT PRÜFEN ENDE

        } //LOGINNAMEN PRÜFEN ENDE

    } // FORMULARPRÜFUNG ENDE

} // FORMULARVERARBEITUNG ENDE

/***************************************************************************************/

/**********************************************/
/********** FETCH THEMES FROM DB **********/
/**********************************************/

if (DEBUG) {
    echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Lade Kategorien... <i>(" . basename(__FILE__) . ")</i></p>";
}

//$categoriesArray = Category::fetchAllCategoriesFromDb($pdo, $thema_id);
$themesArray = Thema::fetchAllThemesFromDb($pdo); //исправь переменную на $themesArray

if (DEBUG) {
    echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
}

if (DEBUG) {
    print_r($themesArray);
}

if (DEBUG) {
    echo "</pre>";
}

/***************************************************************************************/

/**********************************************/
/********** FETCH BLOG FROM DB **********/
/**********************************************/

/***************************************************************************************/
?>

<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>'MyVoyage' Blog</title>
		<link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/debug.css">
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
		<script>
			function openNav() {
				document.getElementById("mySidenav").style.height = "auto";//test: height gegen width
                document.getElementById("mySidenav").style.width = "auto";
                document.getElementById("mySidenav").style.overflowX = "visible";
                //document.getElementById("mySidenav").style.max-height = "500px";
				//document.getElementsByClassName("blogs")[0].style.width = "100%"; //test
				
               
				document.getElementById("mySidenav").style.border = "3px solid lightgray";

			}

			/* Установите высоту боковой навигации в 0 */
			function closeNav() {
				document.getElementById("mySidenav").style.height = "0%";//test hight gegen width
                document.getElementById("mySidenav").style.overflowX = "hidden";//test hight gegen width
                document.getElementById("mySidenav").style.border = "none";
				
				
				//document.getElementsByClassName("blogs")[0].style.width = "100%"; //test
                /*if(catSelect){ //TEST
                            catSelect.innerHTML = "";
                }*/
            }
            /***************************************************************************************************** */







            /** открывать и свернуть окно с логином */
            function closeLog(){
                var i = document.getElementById("adm");
                var j = document.getElementById("login");

                if(i.value == 'close'){
                    j.style.visibility = "hidden";
                    j.style.transform="translateY(-150%)";//new

                    i.value = 'open';
                }else{
                    j.style.visibility = "visible";
                    j.style.transform="translateY(150%)";//new

                    i.value = 'close';
                }
            }

           /* var category = document.getElementsByClassName("cat")[1];
            if(document.width < (721 +'px')){
                category.innerHTML = '|||'
            }*/
		
            //var optionsHtml ="";
            var catSelect = null;
            function catForThemen(sel){
                
                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function() {
                   // catSelect.innerHTML = "<li></li>";

                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        var myObj = JSON.parse(this.responseText);
                        var arreyCat = 0;
                        //console.log("testKiraNEW "+ catSelect.innerHTML);

                        /*if(catSelect){
                            catSelect.innerHTML = "";
                        }*/

                        catSelect = document.getElementById("catTheme" + myObj[0][2]);
                        var optionsHtml ="";

                        console.log("testKira "+myObj[0][2])
                        for(let j = 0; j < myObj.length; j++) {

                            console.log(myObj[j][0] + myObj[j][1]+myObj[j][2]);
                            
                            optionsHtml += "<li><a href = '?action=showCategory&id=" + myObj[j][0] + "'>" + myObj[j][1] + "</a></li>";
                            /**optionsHtml += "<li><a href = '#'>" + myObj[j][1] + "</a></li>"; */

                        }
                        catSelect.innerHTML = optionsHtml;

                    }
                };
                console.log(sel);
                xmlhttp.open('get', 'cat_for_them.php?thema_id='+sel, true);
                xmlhttp.send();
            }

            function catForThemenClose(){
                //catSelect.innerHTML = "<li></li>";
            }
        </script>
	</head>

	<body>
        <div id="container">
		    <!-- ---------------------------------- HEADER ---------------------------------- -->

            <header>
                <div class="fleft">
                    <h1>'MyVoyage' Blog</h1>
                    <p><a href='<?=$_SERVER['SCRIPT_NAME']?>'>Nach oben</a></p>
                </div>
                <div class="fright textRight">
                    <?php if (!isset($_SESSION['usr_id'])): ?>
                        <?=$loginMessage?>

                        <!-- -------- Login Form -------- -->
                        <span id = "adm" class="cat" value = "open" onclick="closeLog()">>>Login</span>
                        <div id="login"  style="visibility: hidden">
                            <form action="" method="POST">
                                <input type="hidden" name="formsentLogin">
                                <input type="text" name="loginName" placeholder="Email">
                                <input type="password" name="loginPassword" placeholder="Password">
                                <input type="submit" value="Login">
                            </form>
                        </div>
                    <?php else: ?>
                        <!-- -------- Links -------- -->
                        <a href="?action=logout">Logout</a><br>
                        <a href='dashboard.php'>zum Dashboard >></a><br>
                    <?php endif?>
                    <div class="fright">
                        <span class ="cat" onclick="openNav()">Themen</span>
                    </div>

                </div>




                <div class="clearer"></div>

               
            </header>



		    <!-- ------------------------------- HEADER ENDE --------------------------------- -->




		    <!-- ----------------------------- THEMEN ------------------------------------- -->

            <nav id="mySidenav" class="categories">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <?php if ($themesArray): ?>
                    <ul>
                        <?php foreach ($themesArray as $thema): ?>
                            <li>
                                <a href='?action=showThemen&id=<?=$thema->getThema_id()?>'><?=$thema->getThema_name()?></a>
                                <ul id = "<?='catTheme' . $thema->getThema_id()?>">
                                    <script> catForThemen(<?=$thema->getThema_id()?>);</script>
                                </ul>
                            </li> <!--href='?action=showCategory&id= -->
                        <?php endforeach?>
                    </ul>
                <?php else: ?>
                    <p class="info">Noch keine Thema vorhanden.</p>
                <?php endif?>
            </nav>

		    <!-- --------------------------- CATEGORIES ENDE ---------------------------------- -->

		    <!-- ------------------------------- BLOG ENTRIES --------------------------------- -->

            <main class='blogs'>

                <!-- -------------------------------TEST NEW NAVI --------------------------------- -->
                <div class = 'workTable'>
                    <div class='wrapNav'>
                        <?php if ($themesArray):?>
                            <ul>
                                
                                <?php 
                                $catArray = Category::fetchAllCategoriesFromDb($pdo, 1);//массив категорий для первой темы
                                
                    
                                foreach ($catArray as $categorie):
                                ?>
                                    <li>
                                    <a href='?action=showCategory&id=<?=$categorie->getCat_id()?>'>Thema: <?=$categorie->getThema()->getThema_name()?>  - <?=$categorie->getCat_name()?></a></p> <!--fehler-->
                                        
                                    </li>
                                <?php endforeach?>
                            </ul>                     
                        <?php endif?> 
                    </div><!--END WRAP NAV -->


                </div><!--END WorkTable -->
                <!-- ------------------------------- END TEST NEW NAVI --------------------------------- -->


                <?php if ($blogsArray): ?>

                    <?php foreach ($blogsArray as $blog): ?>
                        <?php $dateTime = ($blog->getBlog_date())?>

                        <article class='blogEntry'>

                            <a name='entry<?=$blog->getBlog_id()?>'></a>
                                                
                            <p class='fright'><a href='?action=showCategory&id=<?=$blog->getCategory()->getCat_id()?>'>Thema: <?=$blog->getCategory()->getThema()->getThema_name()?>  - <?=$blog->getCategory()->getCat_name()?></a></p>
                            <h2 class='clearer'><?=$blog->getBlog_headline()?></h2>
                            <a href="comment.php?category=<?=$blog->getCategory()->getCat_name()?>"><i class="fa fa-comments-o" aria-hidden="true"></i></a>

                            <p class='author'><?=$blog->getUser()->getFullname()?> (<?=$blog->getUser()->getUsr_city()?>) schrieb am <?=isoToEuDateTime($blog->getBlog_date())['date']?> um <?=isoToEuDateTime($blog->getBlog_date())['time']?> Uhr:</p>

                            <p class='blogContent'>

                                <?php if ($blog->getBlog_image()): ?>
                                    <img class='<?=$blog->getBlog_imageAlignment()?>' src='<?=$blog->getBlog_image()?>' alt='' title=''>
                                <?php endif?>

                                <?=nl2br($blog->getBlog_content())?>
                            </p>

                            <div class='clearer'></div>

                            <br>
                            <hr>

                        </article>

                    <?php endforeach?>

                <?php else: ?>
                    <p class="info">Noch keine Blogeinträge vorhanden.</p>
                <?php endif?>

            </main>

		    <!-- ---------------------------- BLOG ENTRIES ENDE ------------------------------- -->




		    <div class="clearer"></div>
        </div>
       
	</body>

</html>







