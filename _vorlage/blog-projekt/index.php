<?php
/************************************************************************************/

	
				/***SESSION STARTEN UND USER-ID IN SESSION SCHREIBEN**/
				session_name("blog-projekt");
				session_start();
									
				if( !isset( $_SESSION['usr_id'] ) ) {
					session_destroy();
				}
								
									


				/***********************************/
				/********** CONFIGURATION **********/
				/***********************************/
				
				require_once("include/config.inc.php");
				require_once("include/form.inc.php");
				require_once("include/db.inc.php");
				require_once("include/dateTime.inc.php");
/************************************************************************************/

				/**********************************************/
				/********** VARIABLEN INITIALISIEREN **********/
				/**********************************************/

				$loginMessage 	= NULL;
				$loginname		= NULL;
				$password		= NULL;
				
	
				
					
if(DEBUG)							echo"<pre class='debug'>\r\n";
if(DEBUG)							print_r($_SESSION);
if(DEBUG)							echo "</pre>\r\n";		
				
			
/**********************************************************************************/
				
				// Schritt 1 DB: DB-Verbindung herstellen
				$pdo = dbConnect();
				
				
				/******************************************/
				/********** FORMULARVERARBEITUNG **********/
				/******************************************/

				// Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
				
				if( isset( $_POST['formsentLogin'])){
if(DEBUG)		echo "<p class='debug'>Formular 'Login' wurde abgeschickt.</p>";					
					// Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
					$loginname = cleanString($_POST['loginname']);
					$password  = cleanString($_POST['password']);
						
if(DEBUG)		echo "<p class='debug'>\$password: $password</p>\r\n";					
if(DEBUG)		echo "<p class='debug'>\$loginname: $loginname</p>\r\n";	
					
				

					// Schritt 3 FORM: ggf. Daten validieren
						
					$errorLoginname = checkEmail($loginname);	
					$errorPassword 	= checkInputString($password, 4);	
					
					/***************ABSCHLISSENDE FORMULARPRÜFUNG*******/
					
					if($errorLoginname OR $errorPassword){
						//Fehlerfall
						$loginMessage = "<p class='error'>Logindaten sind ungültig!</p>";
						
					}else{
						//Erfolgfall
if(DEBUG)			echo "<p class='debug ok'>Loginformular ist korrekt ausgefült. Daten werden geprüft..</p>\r\n";	

						// Schritt 4 FORM: Daten weiterverarbeiten
					
					
						/******************************************/
						/********** DATENBANKOPERATIONEN **********/
						/******************************************/

					
						// Schritt 1 DB: DB-Verbindung herstellen
						
						

						// Schritt 2 DB: SQL-Statement vorbereiten
						$statement = $pdo->prepare("SELECT usr_id, usr_password, usr_firstname, usr_lastname FROM users WHERE usr_email =:ph_usr_email");	


					
						// Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
						$statement->execute(array("ph_usr_email" => $loginname) );
if(DEBUG)			if($statement->errorInfo()[2]) echo "<p class='debug err'>" . $statement->errorInfo()[2] . "</p>";

						// Schritt 4 DB: Daten weiterverarbeiten
						$row = $statement->fetch(PDO::FETCH_ASSOC);
						
						/****************** 1b. LOGINNAMEN PRÜFEN ************/



						if( !$row ){
							//Fehlerfall
if(DEBUG)				echo "<p class='debug err'>FEHLER: '$loginname' exestieren nicht in DB!</p>\r\n";	
							$loginMessage = "<p class='error'>Logindaten sind ungültig!</p>";
							
						}else{
							//Erfolgsfall
if(DEBUG)				echo "<p class='debug ok'> Loginname: '$loginname' wurden in DB gefunden.</p>\r\n";

							/********************* 2. PASSWORD PRÜFEN ********************/
							if(!password_verify( $password, $row['usr_password'])){
								//Fehlerfall
if(DEBUG)					echo "<p class='debug err'>FEHLER: 'Password stimmen nicht!</p>\r\n";
								$loginMessage = "<p class='error'>Logindaten sind ungültig!</p>";
								
							}else{
								//Erfolgsfall
									
if(DEBUG)					echo "<p class='debug ok'> Password stimmen überein.</p>\r\n";	
								$loginMessage = "<p class='ok'> Password stimmen überein!Login wird durchgeführt...</p>";
								
							
								session_name("blog-projekt");
								session_start();

											
								$_SESSION['usr_id'] = $row['usr_id'];


if(DEBUG)					echo "<p class='debug ok'> Session anzeigen.</p>\r\n";											
if(DEBUG)					echo"<pre class='debug'>\r\n";
if(DEBUG)					print_r($_SESSION);
if(DEBUG)					echo "</pre>\r\n";

								/********** 5. WEITERLEITUNG AUF INTERNE SEITE **********/
									
								header("Location: dashboard.php");		


									
							}//END PASSWORD PRÜFEN

						}// END LOGIN UND PASSWORD PRÜFEN 		

					}// END DATENBANKOPERATIONEN
				}//*END FORMULARPRÜFUNG und BEARBEITEN IM DB*******/
				
				
/***********************************************************************************************************************/				
				
				/**********************************************/
				/********** URL-PARAMETERVERAREITUNG **********/
				/**********************************************/
				// Schritt 1 DB: DB-Verbindung herstellen
					// ist bereits geschehen
					
				// Schritt 1 URL: Prüfen, ob URL-Parameter übergeben wurde
				
				if( isset( $_GET['action'] ) ){
if(DEBUG)		echo "<p class='debug'>URL-Parameter 'action'  wurde ubergeben.</p>";	
					

					// Schritt 2 URL: Werte auslesen, entschärfen, DEBUG-Ausgabe
					$action = cleanString($_GET['action']);
				
if(DEBUG)		echo "<p class='debug '>\$action: $action.</p>\r\n";
				
					if( $action == "dashboard"){
						header("Location: dashboard.php");
											
					}elseif($action == "logout"){
if(DEBUG)			echo "<p class='debug '>Logout wird durchgeführt ....</p>\r\n";

						// Schritt 4 URL; Daten weiterverarbeiten
						//SESSION löschen
						session_destroy();
						//Umleiten auf index.php
						header("Location: index.php");
						exit;											
											
											
					}//LOGAUT ENDE
				}//Ende URL-Parameterbearbeitung
				
/********************************************************************************************************/
				
				/***********************************************/
				/********** BLOGKATEGORIE AUS DB AUSLESEN **********/
				/***********************************************/

if(DEBUG)		echo "<p class='debug '>Kategorien werden ausgelesen...</p>\r\n";
				
				/**********************************KategorieArray Ausfüllen*************************************************/

				/********** DATENBANK OPERATION **********/

				// Schritt 1 DB: DB-Verbindung herstellen
				// ist bereits geschehen
				
				// Schritt 2 DB: SQL-Statement vorbereiten
				$statement = $pdo->prepare("SELECT * FROM categories");
				
				// Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
				$statement->execute();								
				
				if(DEBUG)	if($statement->errorInfo()[2]) echo "<p class='debug err'>" . $statement->errorInfo()[2] . "</p>";				
				
				// Schritt 4 DB: Daten weiterverarbeiten
				$kategorieArray = $statement->fetchAll(PDO::FETCH_ASSOC);
				/*									
if(DEBUG)				echo "<pre class='debug'>\r\n";					
if(DEBUG)				print_r($kategorieArray);					
if(DEBUG)				echo "</pre>\r\n";	

				*/								
/***********************************************************************************************************/



				/********************************************************************************************************************/
				/********************************************************************************************************************/
				/**********************************Blog-list Sortierung und Ausfüllung*************************************************************************/

				if( isset( $_GET['subject'] ) ) {//Zeigen nur eine gewählte Blog-Kategorie
if(DEBUG)			echo "<p class='debug'>URL-Parameter 'links'  wurde ubergeben.</p>";	
if(DEBUG)		echo "<p class='debug '>Kategorienfilter ist aktiv...</p>\r\n";
					

					// Schritt 2 URL: Werte auslesen, entschärfen, DEBUG-Ausgabe
					$subject = cleanString($_GET['subject']);
					
					// Schritt 1 DB: DB-Verbindung herstellen
					// ist bereits geschehen
				
					// Schritt 2 DB: SQL-Statement vorbereiten                                                                          
					$statement = $pdo->prepare("SELECT * FROM blogs INNER JOIN users USING(usr_id) INNER JOIN categories USING(cat_id) WHERE cat_name = :ph_cat_name  ORDER BY blog_date DESC");
					// Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
					$statement->execute(   array( "ph_cat_name"	 =>$subject));
				
if(DEBUG) 			if($statement->errorInfo()[2]) echo "<p class='debug err'>" . $statement->errorInfo()[2] . "</p>";

				}else{//Alle Blog-Kategorien anzeigen
if(DEBUG)		echo "<p class='debug '>Alle Blogbeiträge werden ausgelsenen...</p>\r\n";
					
					// Schritt 1 DB: DB-Verbindung herstellen
					// ist bereits geschehen
				
					// Schritt 2 DB: SQL-Statement vorbereiten
					$statement = $pdo->prepare("SELECT * FROM blogs INNER JOIN users USING(usr_id) INNER JOIN categories USING(cat_id)  ORDER BY blog_date DESC");
					// Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
					$statement->execute();
				
if(DEBUG) 		if($statement->errorInfo()[2]) echo "<p class='debug err'>" . $statement->errorInfo()[2] . "</p>";
	
				}



				
				/**********************************Bloglist Ausfüllen*************************************************/				
				
				
				// Schritt 4 DB: Daten weiterveraebeiten
				$blogArray = $statement->fetchAll(PDO::FETCH_ASSOC);
				/*
if(DEBUG)	echo "<pre class='debug'>\r\n";					
if(DEBUG)	print_r($blogArray);					
if(DEBUG)	echo "</pre>\r\n";	

*/

				












/********************************************************************************************************************/
				
?>
<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>Blog-Projekt</title>
		
		<link rel="stylesheet" href="css/debug.css"  type="text/css">
		<link rel="stylesheet" href="css/my.css" type="text/css">

	</head>

	<body>
		
		<header>
			<div id = "hauptsatz">
				<h1>Blog-Projekt</h1>
				<p class="head"><a href="index.php">Alle Einträge anzeigen</a></p>
			</div>
			<div id="login">
				<?php if( !isset($_SESSION['usr_id']) ): ?>
				
				<form action="" method="POST">
					<input type="hidden" name="formsentLogin">
					<fieldset>
						<span class='error'><?= $loginMessage ?></span>
						<input class="short" type="text" name="loginname" placeholder="Email">
						<input class="short" type="password" name="password" placeholder="Passwort">
						<input class="short" type="submit" value="Login">
					</fieldset>
				</form>
				
				<?php else: ?>
				
					<br>
					<p class="fright"><a href="?action=logout">Logout</a></p>
					<br>
				
					<p class="fright"><a href="?action=dashboard">zum Dashboard >></a></p>
					<br>
				
				<?php endif ?>
			</div>
			
		</header>
		<div class="clearer"></div>
		<div id = "container">
			<main class="links">
			
				
				<fieldset name="blog">
					<legend>Blog</legend>
				
					<div class = "blog_anzeigen">
						<?php foreach( $blogArray AS $value ): 
						
							$registrationdate 		= $value['blog_date'];
							$registrationdateArray 	= isoToEuDateTime($registrationdate);
						?>
							<div>
								<p class = "kategory"><b>Kategory: <?php echo  $value['cat_name'] ?></b></p>
								<p><b> <?php echo  $value['blog_headline'] ?></b></p>
							
							</div>	
							
							
							<div class = "bloger_info">	
							
								<?php echo $value['usr_firstname'] ?> <?php echo $value['usr_lastname'] ?>
								(<?php echo  $value['usr_city'] ?>) schreibt am <?php echo $registrationdateArray['date'] ?> um <?php echo $registrationdateArray['time'] ?> 
								
							
							</div>
						
							<div class = "blog_text">
						
								<?php if($value['blog_image'] == NULL):?>
							
								
									<div class="wrapper">
										<label for="<?php echo  $value['blog_id'] ?>">&gt weiterlesen...</label>
										<input type="checkbox" id ="<?php echo  $value['blog_id'] ?>">
										<div class="xpandable-block">
											<p>
											<?php echo nl2br($value['blog_content']) ?>
											</p>
										</div>
									
									</div>
								
								
								<?php elseif($value['blog_imageAlignment'] == 'rechts'): ?>
									<div class = "ausfuellung">
										<div class="wrapper test">
											<label for="<?php echo  $value['blog_id'] ?>">&gt weiterlesen...</label>
											<input type="checkbox" id="<?php echo  $value['blog_id'] ?>">
											<div class="xpandable-block">
												<p>
													<?php echo nl2br($value['blog_content']) ?>
												</p>
											</div>
										
										</div>
									
								
										<div class = 'test2 right_schow'>
											<img class="bild" src="<?php echo $value['blog_image'] ?>" alt="Bild" title="Bild"><br>
										</div>
										<div class = "clearer"></div>
									</div>
								<?php elseif($value['blog_imageAlignment'] == 'links'): ?>
									<div class = "ausfuellung">
										<div class = "test">
											<img class="bild" src="<?php echo $value['blog_image'] ?>" alt="Bild" title="Bild"><br>
										</div>
										
										<div class="wrapper test2">
											<label for="<?php echo  $value['blog_id'] ?>">&gt weiterlesen...</label>
											<input type="checkbox" id="<?php echo  $value['blog_id'] ?>">
											<div class="xpandable-block">
												<p>
													<?php echo nl2br($value['blog_content']) ?>
												</p>
											</div>
											
										</div>
										<div class = "clearer"></div>
									</div>
								<?php endif ?>
							</div>					
						
						
						<?php endforeach ?>
					</div>
				
				
				</fieldset>
				
			
			</main>
			<aside>
				<fieldset name="bloglist ">
					<legend>Bloglist</legend>
					
					<div class = "blog_list">
						<?php foreach( $kategorieArray AS $value ): ?>
							<div>
								<a href="?subject=<?php echo $value['cat_name'] ?>"><?php echo $value['cat_name'] ?></a>
							</div>
						<?php endforeach ?>
					</div>
				
				</fieldset>
			</aside>
		
		</div>
	</body>
	
</html>