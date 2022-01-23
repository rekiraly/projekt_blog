<?php
session_name("blogProject");
session_start();

if (!isset($_SESSION['usr_id'])) {
    // Fehlerfall
    header("Location: index.php");
    exit();

}

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

$pdo = dbConnect();

$cat_id = null;
$blog_headline = null;
$blog_content = null;
$blog_imageAlignment = null;
$catName = null;
$catThema = null;
$thema_id = null;
$blog_imagePath = null;

$errorCatName = null;
$errorCatThema = null;
$errorHeadline = null;
$errorImageUpload = null;
$errorContent = null;

$catErrorMessage = null;
$catSuccessMessage = null;
$blogMessage = null;
$themesArray = [];

// Schritt 1 URL: Prüfen, ob Parameter übergeben wurde
if (isset($_GET['action'])) {

    $action = cleanString($_GET['action']);

    if ($_GET['action'] == "logout") {
        session_destroy();
        header("Location: index.php");
        exit();
    }

}

if (isset($_POST['formsentNewCategory'])) {

    $catName = cleanString($_POST['cat_name']); //id?

    if (DEBUG) {
        echo "<p class='debug'>\$catName: $catName</p>\r\n";
    }
    $errorCatName = checkInputString($catName, 3, 12);

    if ($errorCatName) {
        if (DEBUG) {
            echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: errorCatName- Formular enthält noch Fehler! <i>(" . basename(__FILE__) . ")</i></p>";
        }

    } else {
        if (DEBUG) {
            echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist fehlerfrei und wird nun verarbeitet... <i>(" . basename(__FILE__) . ")</i></p>";
        }

        //$catThema = $_POST['cat_thema'];
        $catThema = cleanString($_POST['cat_thema']);

        if (!$catThema) {
            $catThema = cleanString($_POST['cat_thema_name']);
        }
        if (DEBUG) {
            echo "<p class='debug'>\$catThema: $catThema</p>\r\n";
        }

        $thema = new Thema();

        $thema->setThema_name($catThema);

        if (!$thema->themaExists($pdo)) {
            // надо добавлять новую тему
            if (DEBUG) {
                echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Es gibt noch keine Thema " . $thema->getThema_name() . "in DB.. <i>(" . basename(__FILE__) . ")</i></p>";
            }
            if ($thema->saveToDb($pdo)) {
                if (DEBUG) {
                    echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Thema " . $thema->getThema_name() . "war erfolgreich in DB hinzufügen. <i>(" . basename(__FILE__) . ")</i></p>";
                }
            } else {
                $errorCatThema = "Thema war nicht in DB hinzufügen";
                if (DEBUG) {
                    echo "<p class='error'>Line <b>" . __LINE__ . "</b>:FEHLER: Das Thema " . $thema->getThema_name() . "war nicht in DB hinzufügen. <i>(" . basename(__FILE__) . ")</i></p>";
                }
            }

        }
        if (!$errorCatThema) {

            $category = new Category();
            //echo "catName: " . $_POST['cat_name'];
            $category->setCat_name($catName);
            $category->setThema($thema);

            $errorCatName = checkInputString($category->getCat_name());
            // $errorCatThema = checkInputString($category->getCat_thema());

            /********** ABSCHLIESSENDE FORMULARPRÜFUNG **********/

            if ($category->catExists($pdo)) {
                //Fehlerfall
                if (DEBUG) {
                    echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Kategorie <b>" . $category->getCat_name() . "</b> existiert bereits! <i>(" . basename(__FILE__) . ")</i></p>";
                }
                $catErrorMessage = "<p class='error'>Es existiert bereits eine Kategorie mit diesem Namen!</p>";

            } else {
                // Erfolgsfall
                if (DEBUG) {
                    echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Neue Kategorie " . $category->getCat_name() . "wird gespeichert... <i>(" . basename(__FILE__) . ")</i></p>";
                }

                /********** KATEGORIE IN DB SPEICHERN **********/
                $category->saveToDb($pdo);

                if ($category->getCat_id()) {
                    // Erfolgsfall
                    if (DEBUG) {
                        echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie " . $category->getCat_name() . " wurde erfolgreich unter der ID " . $category->getCat_id() . " in der DB gespeichert. <i>(" . basename(__FILE__) . ")</i></p>";
                    }

                    $catSuccessMessage = "<p class='success'>Die neue Kategorie mit dem Namen " . $category->getCat_name() . " wurde erfolgreich gespeichert.</p>";

                    // Felder aus Formular wieder leeren
                    $category = null;
                    $thema = null;
                    $catName = null;
                    $catThema = null;

                } else {
                    // Fehlerfall
                    if (DEBUG) {
                        echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER beim Speichern der neuen Kategorie! <i>(" . basename(__FILE__) . ")</i></p>";
                    }

                    $catErrorMessage = "<p class='error'>Fehler beim Speichern der neuen Kategorie!</p>";
                } // IN DB SPEICHERN ENDE

            } // KATEGORIENAMEN IN DB PRÜFEN ENDE
        } // THEMA PRÜF....

    } // FORMULARVALIDIERUNG ENDE

} // NEUE KATEGORIE ENDE

/***************************************************************************************/

/*********************************************************/
/********** FORMULARVERARBEITUNG NEW BLOG ENTRY **********/
/*********************************************************/

// Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
if (isset($_POST['formsentNewBlogEntry'])) {
    if (DEBUG) {
        echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Formular 'New Blog Entry' wurde abgeschickt... <i>(" . basename(__FILE__) . ")</i></p>";
    }

    $user = new User($_SESSION['usr_id']);
    $user->fetchFromDb($pdo);
/*------------------------------------------------------------------------*/
    $thema_id = cleanString($_POST['thema_name']);
    if (DEBUG) {
        echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$thema_id: $thema_id <i>(" . basename(__FILE__) . ")</i></p>";
    }
    $thema = new Thema($thema_id);
    $thema->fetchThemaFromDb($pdo);
/*------------------------------------------------------------------------*/
    $cat_id = cleanString($_POST['cat_id']);
    if (1) {
        echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$cat_id: $cat_id <i>(" . basename(__FILE__) . ")</i></p>";
    }
    $category = new Category($cat_id, null, $thema);
    $category->fetchCategoryFromDb($pdo, $thema_id);

/*------------------------------------------------------------------------*/

    // Schritt 2 FORM: Daten auslesen, entschärfen, DEBUG-Ausgabe

    $blog_headline = cleanString($_POST['blog_headline']);
    $blog_content = cleanString($_POST['blog_content']);
    $blog_imageAlignment = cleanString($_POST['blog_imageAlignment']);
    if (DEBUG) {
        echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$blog_headline: $blog_headline <i>(" . basename(__FILE__) . ")</i></p>";
        echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$blog_imageAlignment: $blog_imageAlignment <i>(" . basename(__FILE__) . ")</i></p>";
        echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$blog_content: $blog_content <i>(" . basename(__FILE__) . ")</i></p>";
    }
    // Schritt 3 FORM: ggf. Werte validieren
    $errorHeadline = checkInputString($blog_headline);
    $errorContent = checkInputString($blog_content, 15, 64000);

    /********** ABSCHLIESSENDE FORMULARPRÜFUNG TEIL 1 (FORMULARFELDER) **********/

    if ($errorHeadline or $errorContent) {
        // Fehlerfall
        if (DEBUG) {
            echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Formular enthält noch Fehler! <i>(" . basename(__FILE__) . ")</i></p>";
        }

    } else {
        if (DEBUG) {
            echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist fehlerfrei. Bildupload wird geprüft... <i>(" . basename(__FILE__) . ")</i></p>";
        }
        /********** FILE UPLOAD **********/
        $blog = new Blog(null, $blog_headline, null, $blog_imageAlignment, $blog_content, null, $category, $user/*,$thema*/); //new

        // Prüfen, ob eine Datei hochgeladen wurde
        if ($_FILES['blog_image']['tmp_name'] != "") {
            if (DEBUG) {
                echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Bild Upload aktiv... <i>(" . basename(__FILE__) . ")</i></p>";
            }

            // imageUpload() liefert ein Array zurück, das eine Fehlermeldung (String oder NULL) enthält
            // sowie den Pfad zum gespeicherten Bild
            $imageUploadResultArray = imageUpload($_FILES['blog_image']);

            // Wenn Fehler:
            if ($imageUploadResultArray['imageError']) {
                $errorImageUpload = $imageUploadResultArray['imageError'];

                // Wenn kein Fehler:
            } else {
                if (DEBUG) {
                    echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Bild wurde erfolgreich unter <i>" . $imageUploadResultArray['imagePath'] . "</i> gespeichert. <i>(" . basename(__FILE__) . ")</i></p>";
                }

                // Pfad zum Bild speichern
                $blog_imagePath = $imageUploadResultArray['imagePath'];

            }
        } else {
            if (DEBUG) {
                echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Es wurde kein Bild hochgeladen. <i>(" . basename(__FILE__) . ")</i></p>";
            }

        } // FILE UPLOAD ENDE

        /********** ABSCHLIESSENDE FORMULARPRÜFUNG TEIL 2 (BILDUPLOAD) **********/

        if ($errorImageUpload) {
            // Fehlerfall
            if (DEBUG) {
                echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Formular enthält noch Fehler (Bildupload)! <i>(" . basename(__FILE__) . ")</i></p>";
            }
        } else {
            if (DEBUG) {
                echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist fehlerfrei. Blogeintrag wird in DB gespeichert... <i>(" . basename(__FILE__) . ")</i></p>";
            }
            $blog->setBlog_image($blog_imagePath); //new
            if (DEBUG) {
                echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
                print_r($blog);
                echo "</pre>";
            }
            /********** BLOGEINTRAG IN DB SPEICHERN **********/

            if (!($blog->saveToDb($pdo))) {
                // Fehlerfall
                if (DEBUG) {
                    echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER beim Speichern des neuen Beitrags! <i>(" . basename(__FILE__) . ")</i></p>";
                }

                $blogmessage = "<p class='error'>Fehler beim Speichern des Beitrags!</p>";

            } else {
                // Erfolgsfall
                if (DEBUG) {
                    echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Neuer Beitrag erfolgreich mit der ID $blog->getBlog_id() gespeichert. <i>(" . basename(__FILE__) . ")</i></p>";
                }

                // Felder aus Formular wieder leeren
                $cat_id = null;
                $thema_id = null;
                $blog_headline = null;
                $blog_imageAlignment = null;
                $blog_content = null;

            } // BLOGEINTRAG IN DB SPEICHERN ENDE

        } // FORMULARPRÜFUNG TEIL 2 (BILDUPLOAD) ENDE

    } // FORMULARPRÜFUNG TEIL 1 (FELDPRÜFUNGEN) ENDE

} // NEUER BLOG-EINTRAG ENDE

/***************************************************************************************/

/**********************************************/
/********** FETCH THEMEN FROM DB **********/
/**********************************************/

if (DEBUG) {
    echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Lade Themen... <i>(" . basename(__FILE__) . ")</i></p>";
}

$themesArray = Thema::fetchAllThemesFromDb($pdo);

// Kategorien aus DB zur späteren Verwendung in Array speichern

echo "<pre class='debug'>\r\n";
print_r($themesArray);
echo "</pre>\r\n";
/***************************************************************************************/
?>

<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>'MyVoyage' Blog</title>
		<!--<link rel="stylesheet" href="css/main.css"> -->
        <link rel="stylesheet" href="css/dashboard.css">
		 <link rel="stylesheet" href="css/debug.css">
        <script>

        </script>
	</head>

	<body class="dashboard">

		<!--------------------------------- HEADER ----------------------------------------->

		<header >
        <h1 class="dashboard fleft">PHP-Projekt Blog - Dashboard</h1>
        <div class="fright">

            <div style='text-align:right'>
                <a href="?action=logout">Logout</a><br>
                <a href="index.php"><< zum Frontend</a>
            </div>
        </div>
        <div class="clearer"></div>
		</header>




		<!-------------------------------------------------------------------------------->


        <p class="name">Aktiver Benutzer: <?="$_SESSION[usr_firstname] $_SESSION[usr_lastname]"?></p>

        <div class="flexbox"><!---------------BEGINNT FLEXBOX----------------------------------->

		<?php if ($blogMessage or $catSuccessMessage): ?>
		<popupBox>
			<?=$blogMessage?>
			<?=$catSuccessMessage?>
			<a class="button" onclick="document.getElementsByTagName('popupBox')[0].style.display = 'none'">Schließen</a>
		</popupBox>
		<?php endif?>
        <form action="" method="POST" enctype="multipart/form-data">
		<div class="wrap-box">


			<!--------------------------- NEW BLOG ENTRY FORM -------------------------------->



			<!-- Form Blog-Eintrag erstellen -->

            <h2 class="dashboard">Neuen Blog-Eintrag verfassen</h2>
                <input class="dashboard" type="hidden" name="formsentNewBlogEntry">
                <!-------------------------thema wählen neu eintrag---------------------------------->

                <label>Thema wahlen:</label><br>

                <select class="dashboard bold" name="thema_name"  onchange = "wishFunction(this);">
                <option disabled selected> Wahlen Sie passendes Thema </option>
				<?php foreach ($themesArray as $thema): ?>
					<?php if ($thema_id == $thema->getThema_id()): ?>
						<option value='<?=$thema->getThema_id()?>' selected><?=$thema->getThema_name()?></option>

					<?php else: ?>
						<option value='<?=$thema->getThema_id()?>'><?=$thema->getThema_name()?></option>
					<?php endif?>
				<?php endforeach?>
				</select>

                <!-------------------------neu----------------------------------->

                <label>Categories wahlen:</label><br>

				<select     id="catSelect" class="dashboard bold" name="cat_id">
                    <option disabled selected> Wahlen Sie passende Categorie </option>
                </select>

				<br>

				<span class="error"><?=$errorHeadline?></span><br>
				<input class="dashboard" type="text" name="blog_headline" placeholder="Überschrift" value="<?=$blog_headline?>"><br>

				<label>Bild hochladen:</label><br>
				<span class="error"><?=$errorImageUpload?></span><br>
				<input type="file" name="blog_image">
				<select class="alignment" name="blog_imageAlignment">
					<option value="fleft" <?php if ($blog_imageAlignment == "fleft") {
    echo "selected";
}
?>>align left</option>
					<option value="fright" <?php if ($blog_imageAlignment == "fright") {
    echo "selected";
}
?>>align right</option>
				</select>

				<br>
                <br>

        </div>
<div class="wrap-box">


				<span class="error"><?=$errorContent?></span><br>
				<textarea class="dashboard" name="blog_content" placeholder="Text..."><?=$blog_content?></textarea><br>

				<div class="clearer"></div>

				<input class="dashboard" type="submit" value="Veröffentlichen">


			<!-------------------------------------------------------------------------------->

</div>
</form>
<form class="dashboard" action="" method="POST">
	<div class="wrap-box">

		<h2 class="dashboard">Neue Kategorie anlegen</h2>
			<?=$catErrorMessage?>

			<!------------------------------ NEW CATEGORY FORM --------------------------------->


			<input class="dashboard" type="hidden" name="formsentNewCategory">
			<span class="error"><?=$errorCatName?></span><br>
			<input class="dashboard" type="text" name="cat_name" placeholder="Name der Kategorie" value="<?=$catName?>"><br>
            <!------------------------------ THEMA WAHLEN --------------------------------->
                <p>Wollen Sie neue Thema hinzufügen?</p>

                <div class="wrap">
                    <div>
                        <input type="checkbox"  id="s4" name = "themaV" value="yes" />
                        <label class="slider-v2" for="s4"></label>
                    </div>

                </div><!--/wrap-->
                <!--$themesArray array kann sehen-->
                <div>


                        <!-------------------------------------------------------------------------------->
                    <select class="dashboard bold js-thema" name="cat_thema">
                        <option disabled selected> Wahlen Sie passendes Thema </option>
				<?php foreach ($themesArray as $thema): ?>

					<?php if ($thema_id == $thema->getThema_name()): ?>
						<option value='<?=$thema->getThema_name()?>' selected><?=$thema->getThema_name()?></option>

					<?php else: ?>

						<option value='<?=$thema->getThema_name()?>'><?=$thema->getThema_name()?></option>
					<?php endif?>
				    <?php endforeach?>
				    </select>
                </div>
                <!-------------------------------------------------------------------------------->


                <!--------------------------------NEW THEMA addieren------------------------------------------------->
                <div class="js-container hidden">
				    <input class="dashboard" type="text" name="cat_thema_name" placeholder="Name der neue Thema" ><br>
                </div>


				<input class="dashboard" type="submit" value="Neue Kategorie anlegen">




		</div>
    </div><!------------------End FLEXBOX-------------------------------------------------------------->
</form>
<script>
//переключение между созданием новой темы и выбором старой
            var button = document.querySelector('.slider-v2');
            var container = document.querySelector('.js-container');
            var thema = document.querySelector('.js-thema');

            button.addEventListener("click", function (e){

                isVisible = container.style.display == 'block';
                container.style.display = isVisible ? 'none' : 'block';
                container.style.display = isVisible ? thema.disabled = false : thema.disabled = true ;
                if(thema.disabled == true ){
                    thema.value = 'Wahlen Sie passendes Thema';
                    //thema.selected =true;

                }


            });
//для передачи id von Thema
            function wishFunction(sel){
                //var thema = document.getElementById("demo");
                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        var myObj = JSON.parse(this.responseText);

                        var arreyCat = 0;
                        var catSelect = document.getElementById("catSelect");
                        var optionsHtml = "<option disabled selected> Wahlen Sie passende Categorie </option>";
                        for(let j = 0; j < myObj.length; j++) {

                            console.log(myObj[j][0] + myObj[j][1]+myObj[j][2]);

                            optionsHtml += "<option value=\"" + myObj[j][0] + "\">" + myObj[j][1] + "</option>";


                        }
                        catSelect.innerHTML = optionsHtml

                    }
                };
                xmlhttp.open('get', 'cat_for_them.php?thema_id='+sel.value, true);
                xmlhttp.send();
            }

</script>e
	</body>
</html>






