<?php
/************************************************************************************/


				/**
				*
				*	Säubert und entschärft einen übergebenen String
				*
				*	@param String $value			Der zu bereinigende und zu entschärfende String
				*
				*	@return String					Der bereinigte und entschärfte String
				*
				*/
				function cleanString($value) {
if(DEBUG_F)		echo "<p class='debugCleanString'>Aufruf cleanString($value)</p>\r\n";	
					
					// trim() entfernt am Anfang und am Ende eines Strings alle 
					// sog. Whitespaces (Leerzeichen, Tabulatoren, Zeilenumbrüche)
					$value = trim($value);
					
					// htmlspecialchars() entschärft HTML-Steuerzeichen wie < > & '' ""
					// und ersetzt sie durch &lt;, &gt;, &amp;, &apos; &quot;
					// ENT_QUOTES | ENT_HTML5 ersetzt zusätzlich ' durch &#039;
					$value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
					
					
					// Damit cleanString() nicht NULL-Werte in Leerstings verändert, wird 
					// geprüft, ob $value überhaupt einen gültigen Wert besitzt
					if(!$value){
						$value = NULL;
						} 
					
					return $value;					
				}


/************************************************************************************/


				/**
				*
				*	Prüft einen String auf Leerstring, Mindest- und Maximallänge
				*
				*	@param	String	$value								Der zu prüfende String
				*	@param	[Integer	$minLength=MIN_INPUT_LENGTH]	Die erforderliche Mindestlänge
				*	@param	[Integer	$maxLength=MAX_INPUT_LENGTH]	Die erlaubte Maximallänge
				*
				*	@return	String/NULL										Fehlermeldung bei Fehler, ansonsten NULL
				*
				*/
				function checkInputString($value, $minLength=MIN_INPUT_LENGTH, $maxLength=MAX_INPUT_LENGTH) {
if(DEBUG_F)		echo "<p class='debugCheckInputString'>Aufruf checkInputString($value, $minLength, $maxLength)</p>\r\n";	

					$errorMessage = NULL;

					// Prüfen auf leeres Feld (Leerstring)
					if( !$value ) {
						$errorMessage = "Dies ist ein Pflichtfeld!";
						
					// Prüfen auf Mindestlänge	
					} elseif( mb_strlen($value) < $minLength ) {
						$errorMessage = "Muss mindestens $minLength Zeichen lang sein!";
					
					// Prüfen auf Maximallänge
					} elseif( mb_strlen($value) > $maxLength ) {
						$errorMessage = "Darf maximal $maxLength Zeichen lang sein!";					
					}
					
					return $errorMessage;					
				}


/************************************************************************************/


				/**
				*
				*	Prüft eine übergebene Email-Adresse auf Leerstring und Validität
				*
				*	@param	String $value		Die zu prüfende Email-Adresse
				*
				*	@return	String/NULL			Fehlermeldung bei Fehler, ansonsten NULL 
				*
				*/
				function checkEmail($value) {
if(DEBUG_F)		echo "<p class='debugCheckEmail'>Aufruf checkEmail($value)</p>\r\n";	

					$errorMessage = NULL;
	
					// Prüfen auf leeres Feld (Leerstring)
					if( !$value ) {
						$errorMessage = "Dies ist ein Pflichtfeld!";
						
					// Prüfen auf Validität	
					} elseif( !filter_var($value, FILTER_VALIDATE_EMAIL) ) {
						$errorMessage = "Dies ist keine gültige Email-Adresse!";

					}

					return $errorMessage;					
				}


/************************************************************************************/
					/**
					*
					* Speichert und prüft ein hochgeladenes Bild auf MIME-Type, Datei- und Bildgröße
					*
					* @param Array $uploadedImage - Das hochzuladende Bild aus $_FILES
					* @param [Int $maxWidth] - Die maximal erlaubte Bildbreite in Px
					* @param [Int $maxHeigth] - Die maximal erlaubte Bildhöhe in Px
					* @param [Int $maxSize] - Die maximal erlaubte Dateigröße in Bytes
					* @param [String $uploadPath] - Das Speicher-Verzeichnis auf dem Server
					* @param [Array $allowedMimeTypes] - Whitelist der erlaubten MIME-Types
					*
					* @return Array {String/NULL - Fehlermeldung im Fehlerfall, String - Der Speicherpfad auf dem Server}
					*
					*/

					function imageUpload( $uploadedImage,	
													$maxWidth			=IMAGE_INPUT_WIDTH,
													$maxHeight			=IMAGE_INPUT_HEIGHT,
													$maxSize				=IMAGE_MAX_SIZE,
													$uploadPath			 =IMAGE_UPLOAD_PATH,
													$allowedMimeTypes  = IMAGE_ALLOWED_MIMETYPE
												){
if(DEBUG_F)		echo "<p class='debugImageUpload'>Aufruf imageUpload()</p>\r\n";							
											
						/*
						Das Array $_FILES['avatar'] bzw. $uploadedImage enthält:
						Den Dateinamen [name]
						Den generierten (also ungeprüften) MIME-Type [type]
						Den temporären Pfad auf dem Server [tmp_name]
						Die Dateigröße in Bytes [size]
						*/
if(DEBUG_F)			echo"<pre class='debugImageUpload'>\r\n";
if(DEBUG_F)			print_r($uploadedImage);
if(DEBUG_F)			echo "</pre>\r\n";	
					/************************** BILDINFORMATIONEN SAMMELN****************************************/
					//Dateiname
					$fileName = $uploadedImage['name'];
					
					//ggf. Leerzeichen im Dateinamen durch "_" ersetzen
					$fileName = str_replace(" ", "_", $fileName);
					
					//Dateinamen in Kleinbuchstaben umwandeln
					$fileName = strtolower($fileName);
					
					//Dateigrösse
					$fileSize = $uploadedImage['size'];
					
					//Temporär Pfad auf dem Server
					
					$fileTemp = $uploadedImage['tmp_name'];
					
					
					//Späterer PFad zum Bild
					//uploads/userimages/ZUFALLSNAME+ORIGINALNAME.jpg
					
					//zufälligen Dateinamen generieren
					$randomPrefix = rand(1,999999) . str_shuffle("abcdefghijklmnopqrstuvwxyz") . time();
					$fileTarget = "uploaded_images/userimages/" . $randomPrefix . " " . $fileName;
					
					
if(DEBUG_F)		echo "<p class='debugImageUpload'>\$fileName: $fileName</p>";					
if(DEBUG_F)		echo "<p class='debugImageUpload'>\$fileSize: $fileSize</p>";					
if(DEBUG_F)		echo "<p class='debugImageUpload'>\$fileTemp: $fileTemp</p>";					
if(DEBUG_F)		echo "<p class='debugImageUpload'>\$fileTemp: $fileTarget</p>";					
					
					//Genauere Information zum Bild auslesen
					
						$imageData = getimagesize($fileTemp);
						
						/*
						Die Funktion getimagesize() liefert bei gültigen Bildern ein Array zurück:
						Die Bildbreite in PX [0]
						Die Bildhöhe in PX [1]
						Einen für die HTML-Ausgabe vorbereiteten String für das IMG-Tag
						(width="480" height="532") [3]
						Die Anzahl der Bits pro Kanal ['bits']
						Die Anzahl der Farbkanäle (somit auch das Farbmodell: RGB=3, CMYK=4) ['channels']
						Den echten(!) MIME-Type ['mime']
						*/
					
if(DEBUG_F)			echo"<pre class='debugImageUpload'>\r\n";
if(DEBUG_F)			print_r($imageData );
if(DEBUG_F)			echo "</pre>\r\n";		

						$imageWidth = $imageData[0];
						$imageHeight = $imageData[1];
						$imageMimeType  = $imageData['mime'];
						
if(DEBUG_F)		echo "<p class='debugImageUpload'>\$imageWidth: $imageWidth</p>";					
if(DEBUG_F)		echo "<p class='debugImageUpload'>\$imageHeight: $imageHeight</p>";					
if(DEBUG_F)		echo "<p class='debugImageUpload'>\$imageMimeType: $imageMimeType</p>";		

					/*************** BILD PRÜFEN ********************/			
					//MIME-TYPE prüfen
			
					//Whitelist mit erlaubten Bildtypen
					$allowedMimeTypes = array("image/jpeg", "image/jpg", "image/gif", "image/png");
					if( !in_array($imageMimeType, $allowedMimeTypes)){
						$errorMessage = "Dies ist kein gültiger Bildtyp!";
						
						
						
						//Maximal erlaubte Bildhöhe
					}elseif($imageHeight > $maxHeight){
						$errorMessage = "Die Bildhöhe darf maximal $maxHeight Pixel betragen!";
						
					//Maximal erlaubte Bildbreite	
					}elseif($imageWidth > $maxWidth){
						$errorMessage = "Die Bildwidth darf maximal $maxWidth Pixel betagen!";
						
					//Maximal erlaubte Deteigrösse	
					}elseif($fileSize >$maxSize){
					
						$errorMessage = "Die Dateigrösse darf maximal" . $maxSize/1024 . " kB Pixel betagen!";
						
					
					//Wenn kein Fehler gab
					}else{
						$errorMessage = NULL;
					}

					

					/**************** BILD SPEICHERN *************************/
					
						if($errorMessage){
							//Fehlerfall
	if(DEBUG)			echo "<p class='debugImageUpload err'>Die Bildprüfung egab Fehler: '$errorMessage'</p>";
													
						}else{
							
							//Erfolgsfall
	if(DEBUG)			echo "<p class='debugImageUpload ok'>Die Bildprüfung egab keine Fehler</p>";						
						
						
						
						
							//Bild an seinen endgültigen Speicherjrt verschieben
							if(!move_uploaded_file($fileTemp, $fileTarget)){
								//Fehlerfall
if(DEBUG)					echo "<p class='debugImageUpload err'>Fehler beim Speichern der Datei auf dem Server</p>";	
								$errorMessage = "Fehler beim Speichern der Datei auf dem Server!";
							}else{
								
								//Erfolgsfall
if(DEBUG)					echo "<p class='debugImageUpload ok'>Datei wurde erfolgreich unter '$fileTarget'  gespeichert! </p>";
							}
						}
						
						/***********************Fehlermeldung und Bildpfad zurückgeben***********************************/
						
						return array( "imageError" =>$errorMessage, "imagePath" =>$fileTarget );
					}




/************************************************************************************/




?>