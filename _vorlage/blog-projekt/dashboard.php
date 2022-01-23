<?php
/***************************************************************************************/

			
				/*********************************/
				/********** SECURE PAGE **********/
				/*********************************/
				
				/********** INITIALIZE SESSION **********/
				session_name("blogProject");
				session_start();
				
				/********** CHECK FOR VALID LOGIN **********/
				if( !isset($_SESSION['usr_id']) ) {
					// Fehlerfall
					header("Location: index.php");
					exit();
					
				}
			
			
/***************************************************************************************/

			
				/***********************************/
				/********** CONFIGURATION **********/
				/***********************************/
				
				require_once("include/config.inc.php");
				require_once("include/db.inc.php");
				require_once("include/form.inc.php");
				include_once("include/dateTime.inc.php");

				
				/********** ESTABLISH DB CONNECTION **********/
				$pdo = dbConnect();
			
			
/***************************************************************************************/

			
				/******************************************/
				/********** INITIALIZE VARIABLES **********/
				/******************************************/
				
				$cat_id 					= NULL;
				$blog_headline 		= NULL;
				$blog_content 			= NULL;
				$blog_imageAlignment = NULL;
				$catName 				= NULL;
				$blog_imagePath 		= NULL;
				
				$errorCatName 			= NULL;
				$errorHeadline 		= NULL;
				$errorImageUpload 	= NULL;
				$errorContent 			= NULL;
				
				$catErrorMessage		= NULL;
				$catSuccessMessage	= NULL;
				$blogMessage 			= NULL;


/***************************************************************************************/

	
				/***********************************************/
				/********** URL-PARAMETERVERARBEITUNG **********/
				/***********************************************/
				
				// Schritt 1 URL: Prüfen, ob Parameter übergeben wurde
				if( isset($_GET['action']) ) {
if(DEBUG)		echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL-Parameter 'action' wurde übergeben... <i>(" . basename(__FILE__) . ")</i></p>";	
			
					// Schritt 2 URL: Werte auslesen, entschärfen, DEBUG-Ausgabe
					$action = cleanString($_GET['action']);
if(DEBUG)		echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$action = $action <i>(" . basename(__FILE__) . ")</i></p>";
		
					// Schritt 3 URL: ggf. Verzweigung
					
					/********** LOGOUT **********/
					if( $_GET['action'] == "logout" ) {
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: 'Logout' wird durchgeführt... <i>(" . basename(__FILE__) . ")</i></p>";	

						session_destroy();
						header("Location: index.php");
						exit();
					}
					
				} // URL-PARAMETERVERARBEITUNG ENDE

		
/***************************************************************************************/			

	
				/*******************************************************/
				/********** FORMULARVERARBEITUNG NEW CATEGORY **********/
				/*******************************************************/
				
				// Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
				if( isset($_POST['formsentNewCategory']) ) {
if(DEBUG)		echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Formular 'New Category' wurde abgeschickt... <i>(" . basename(__FILE__) . ")</i></p>";	
		
					// Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
					$catName = cleanString($_POST['cat_name']);
if(DEBUG)		echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$catName: $catName <i>(" . basename(__FILE__) . ")</i></p>";
				
					// Schritt 3 FORM: Werte ggf. validieren
					$errorCatName = checkInputString($catName);
					
					
					/********** ABSCHLIESSENDE FORMULARPRÜFUNG **********/
					if( $errorCatName ) {
if(DEBUG)			echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Formular enthält noch Fehler! <i>(" . basename(__FILE__) . ")</i></p>";						
						
					} else {
if(DEBUG)			echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist fehlerfrei und wird nun verarbeitet... <i>(" . basename(__FILE__) . ")</i></p>";						
						
						// Schritt 4 FORM: Daten weiterverarbeiten

						
						/********** PRÜFEN, OB KATEGORIE BEREITS EXISTIERT **********/
						$sql = "SELECT COUNT(*) FROM categories WHERE cat_name = :ph_cat_name";
						$params = array( "ph_cat_name" => $catName );
						
						// Schritt 2 DB: SQL-Statement vorbereiten
						$statement = $pdo->prepare($sql);
						// Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
						$statement->execute($params);
if(DEBUG)			if($statement->errorInfo()[2]) echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";
						
						$categoryExists = $statement->fetchColumn();
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$categoryExists: $categoryExists <i>(" . basename(__FILE__) . ")</i></p>";
						
						if( $categoryExists ) {
							// Fehlerfall
							echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Kategorie <b>'$catName'</b> existiert bereits! <i>(" . basename(__FILE__) . ")</i></p>";
							$catErrorMessage = "<p class='error'>Es existiert bereits eine Kategorie mit diesem Namen!</p>"; 
						
						} else {
							// Erfolgsfall
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Neue Kategorie <b>$catName</b> wird gespeichert... <i>(" . basename(__FILE__) . ")</i></p>";	

							/********** KATEGORIE IN DB SPEICHERN **********/
							$sql = "INSERT INTO categories (cat_name) VALUES (:ph_cat_name)";
							$params = array("ph_cat_name" => $catName);
							
							// Schritt 2 DB: SQL-Statement vorbereiten
							$statement = $pdo->prepare($sql);
							// Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
							$statement->execute($params);
if(DEBUG)				if($statement->errorInfo()[2]) echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";
							
							// Schritt 4 DB: Schreiberfolg prüfen
							$newCatId = $pdo->lastInsertId();								
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$newCatId: $newCatId <i>(" . basename(__FILE__) . ")</i></p>";
							
							if( $newCatId ) {
								// Erfolgsfall
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie <b>'$catName'</b> wurde erfolgreich unter der ID $newCatId in der DB gespeichert. <i>(" . basename(__FILE__) . ")</i></p>";								
								$catSuccessMessage = "<p class='success'>Die neue Kategorie mit dem Namen <b>$catName</b> wurde erfolgreich gespeichert.</p>";
									
								// Felder aus Formular wieder leeren
								$catName = NULL;
									
							} else {
								// Fehlerfall
if(DEBUG)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER beim Speichern der neuen Kategorie! <i>(" . basename(__FILE__) . ")</i></p>";
								$catErrorMessage = "<p class='error'>Fehler beim Speichern der neuen Kategorie!</p>";
							} // IN DB SPEICHERN ENDE
							 
						} // KATEGORIENAMEN IN DB PRÜFEN ENDE
						
					} // FORMULARVALIDIERUNG ENDE

				} // NEUE KATEGORIE ENDE

			
/***************************************************************************************/


				/*********************************************************/
				/********** FORMULARVERARBEITUNG NEW BLOG ENTRY **********/
				/*********************************************************/
				
				// Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
				if( isset($_POST['formsentNewBlogEntry']) ) {			
if(DEBUG)		echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Formular 'New Blog Entry' wurde abgeschickt... <i>(" . basename(__FILE__) . ")</i></p>";	

					// Schritt 2 FORM: Daten auslesen, entschärfen, DEBUG-Ausgabe
					$cat_id 					= cleanString($_POST['cat_id']);
					$blog_headline 		= cleanString($_POST['blog_headline']);
					$blog_content 			= cleanString($_POST['blog_content']);
					$blog_imageAlignment = cleanString($_POST['blog_imageAlignment']);
if(DEBUG)  		echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$cat_id: $cat_id <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)  		echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$blog_headline: $blog_headline <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)  		echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$blog_imageAlignment: $blog_imageAlignment <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)  		echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$blog_content: $blog_content <i>(" . basename(__FILE__) . ")</i></p>";

					// Schritt 3 FORM: ggf. Werte validieren
					$errorHeadline = checkInputString($blog_headline);
					$errorContent 	= checkInputString($blog_content, 5, 64000);


					/********** ABSCHLIESSENDE FORMULARPRÜFUNG TEIL 1 (FORMULARFELDER) **********/
					
					if( $errorHeadline OR $errorContent) {
						// Fehlerfall
if(DEBUG)			echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Formular enthält noch Fehler! <i>(" . basename(__FILE__) . ")</i></p>";
						
					} else {
if(DEBUG)			echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist fehlerfrei. Bildupload wird geprüft... <i>(" . basename(__FILE__) . ")</i></p>";


						/********** FILE UPLOAD **********/
						
						// Prüfen, ob eine Datei hochgeladen wurde
						if( $_FILES['blog_image']['tmp_name'] !=  "") {
if(DEBUG)				echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Bild Upload aktiv... <i>(" . basename(__FILE__) . ")</i></p>";

							// imageUpload() liefert ein Array zurück, das eine Fehlermeldung (String oder NULL) enthält
							// sowie den Pfad zum gespeicherten Bild
							$imageUploadResultArray = imageUpload($_FILES['blog_image']);
					
							// Wenn Fehler:
							if( $imageUploadResultArray['imageError'] ) {
								$errorImageUpload = $imageUploadResultArray['imageError'];
								
							// Wenn kein Fehler:
							} else {
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Bild wurde erfolgreich unter <i>" . $imageUploadResultArray['imagePath'] . "</i> gespeichert. <i>(" . basename(__FILE__) . ")</i></p>";
								// Pfad zum Bild speichern
								$blog_imagePath = $imageUploadResultArray['imagePath'];
							}
						} else {
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Es wurde kein Bild hochgeladen. <i>(" . basename(__FILE__) . ")</i></p>";
							
						} // FILE UPLOAD ENDE

						
						/********** ABSCHLIESSENDE FORMULARPRÜFUNG TEIL 2 (BILDUPLOAD) **********/
					
						if( $errorImageUpload) {
							// Fehlerfall
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Formular enthält noch Fehler (Bildupload)! <i>(" . basename(__FILE__) . ")</i></p>";
							
						} else {
if(DEBUG)				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist fehlerfrei. Blogeintrag wird in DB gespeichert... <i>(" . basename(__FILE__) . ")</i></p>";


							/********** BLOGEINTRAG IN DB SPEICHERN **********/
							$sql 		= 	"INSERT INTO blogs (blog_headline, blog_image, blog_imageAlignment, blog_content, cat_id, usr_id)
											VALUES (:ph_headline, :ph_image, :ph_alignment, :ph_content, :ph_cat_id, :ph_usr_id) ";
							
							$params 	= array("ph_headline"	=>$blog_headline,
													"ph_image"		=>$blog_imagePath,
													"ph_alignment"	=>$blog_imageAlignment,
													"ph_content"	=>$blog_content,
													"ph_cat_id"		=>$cat_id,
													"ph_usr_id"		=>$_SESSION['usr_id']);
							
							// Schritt 2 DB: SQL-Statement vorbereiten
							$statement = $pdo->prepare($sql);
							// Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
							$statement->execute($params);
if(DEBUG)				if($statement->errorInfo()[2]) echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";
							
							// Schritt 4 DB: Schreiberfolg prüfen
							$newBlogId = $pdo->lastInsertId();
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$newBlogId: $newBlogId <i>(" . basename(__FILE__) . ")</i></p>";						
							
							if( !$newBlogId ) {
								// Fehlerfall
if(DEBUG)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER beim Speichern des neuen Beitrags! <i>(" . basename(__FILE__) . ")</i></p>";
								$blogmessage = "<p class='error'>Fehler beim Speichern des Beitrags!</p>";
							
							} else {
								// Erfolgsfall
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Neuer Beitrag erfolgreich mit der ID $newBlogId gespeichert. <i>(" . basename(__FILE__) . ")</i></p>";
								$blogMessage = "<p class='success'>Der Beitrag wurde erfolgreich gespeichert.</p>";
								
								// Felder aus Formular wieder leeren
								$cat_id 					= NULL;
								$blog_headline 		= NULL;
								$blog_imageAlignment = NULL;
								$blog_content 			= NULL;
								
							} // BLOGEINTRAG IN DB SPEICHERN ENDE
							
						} // FORMULARPRÜFUNG TEIL 2 (BILDUPLOAD) ENDE
							
					} // FORMULARPRÜFUNG TEIL 1 (FELDPRÜFUNGEN) ENDE
					
				} // NEUER BLOG-EINTRAG ENDE
			

/***************************************************************************************/
			
			
				/**********************************************/
				/********** FETCH CATEGORIES FROM DB **********/
				/**********************************************/

if(DEBUG)	echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Lade Kategorien... <i>(" . basename(__FILE__) . ")</i></p>";
			
				$sql = "SELECT * FROM categories";
				$params = NULL;
				
				// Schritt 2 DB: SQL-Statement vorbereiten
				$statement = $pdo->prepare($sql);
				// Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
				$statement->execute($params);
if(DEBUG)	if($statement->errorInfo()[2]) echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";
				
				// Kategorien aus DB zur späteren Verwendung in Array speichern
				$categoriesArray = $statement->fetchAll();


/***************************************************************************************/			
?>

<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>PHP-Projekt Blog</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/debug.css">
	</head>

	<body class="dashboard">

		<!--------------------------------- HEADER ----------------------------------------->
	
		<header class="fright">
			<a href="?action=logout">Logout</a><br>
			<a href="index.php"><< zum Frontend</a>
		</header>
		<div class="clearer"></div>

		<br>
		<hr>
		<br>
		
		<!-------------------------------------------------------------------------------->
		
		<h1 class="dashboard">PHP-Projekt Blog - Dashboard</h1>
		<p class="name">Aktiver Benutzer: <?= "$_SESSION[usr_firstname] $_SESSION[usr_lastname]" ?></p>
		
		<?php if( $blogMessage OR $catSuccessMessage ): ?>
		<popupBox>
			<?= $blogMessage ?>
			<?= $catSuccessMessage ?>
			<a class="button" onclick="document.getElementsByTagName('popupBox')[0].style.display = 'none'">Schließen</a>
		</popupBox>		
		<?php endif ?>
		
		<div class="fleft">
			
			
			<!--------------------------- NEW BLOG ENTRY FORM -------------------------------->
			
			<h2 class="dashboard">Neuen Blog-Eintrag verfassen</h2>
			
			<!-- Form Blog-Eintrag erstellen -->
			<form action="" method="POST" enctype="multipart/form-data">
				<input class="dashboard" type="hidden" name="formsentNewBlogEntry">
				
				<br>
				<select class="dashboard bold" name="cat_id">
				<?php foreach($categoriesArray AS $category): ?>
					<?php if($cat_id == $category['cat_id']): ?> 
						<option value='<?= $category['cat_id'] ?>' selected><?= $category['cat_name'] ?></option>
					<?php else: ?>
						<option value='<?= $category['cat_id'] ?>'><?= $category['cat_name'] ?></option>
					<?php endif ?>
				<?php endforeach ?>
				</select>
				
				<br>
				
				<span class="error"><?= $errorHeadline ?></span><br>
				<input class="dashboard" type="text" name="blog_headline" placeholder="Überschrift" value="<?= $blog_headline ?>"><br>
				
				<label>Bild hochladen:</label><br>
				<span class="error"><?= $errorImageUpload ?></span><br>
				<input type="file" name="blog_image">
				<select class="alignment" name="blog_imageAlignment">
					<option value="fleft" <?php if($blog_imageAlignment == "fleft") echo "selected"?>>align left</option>
					<option value="fright" <?php if($blog_imageAlignment == "fright") echo "selected"?>>align right</option>
				</select>
				
				<br>
				<br>
				
				<span class="error"><?= $errorContent ?></span><br>
				<textarea class="dashboard" name="blog_content" placeholder="Text..."><?= $blog_content ?></textarea><br>
				
				<div class="clearer"></div>
				
				<input class="dashboard" type="submit" value="Veröffentlichen">
			</form>
			
			<!-------------------------------------------------------------------------------->
			
		</div>
		
		<div class="fright">
		
			<h2 class="dashboard">Neue Kategorie anlegen</h2>
			<?= $catErrorMessage ?>
			
			<!------------------------------ NEW CATEGORY FORM --------------------------------->
			
			<form class="dashboard" action="" method="POST">
				<input class="dashboard" type="hidden" name="formsentNewCategory">
				<span class="error"><?= $errorCatName ?></span><br>
				<input class="dashboard" type="text" name="cat_name" placeholder="Name der Kategorie" value="<?= $catName ?>"><br>

				<input class="dashboard" type="submit" value="Neue Kategorie anlegen">
			</form>
		
			<!-------------------------------------------------------------------------------->
		
		</div>

		<div class="clearer"></div>
		
	</body>
</html>






