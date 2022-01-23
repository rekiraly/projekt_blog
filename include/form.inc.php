<?php
/**********************************************************************************/
				
				
				/**
				*
				*	Entschärft und säubert einen String, falls er einen Wert besitzt
				*	Fall der String keinen Wert besitzt (NULL, "", 0, false) wird er 
				*	1:1 zurückgegeben
				*
				*	@param String $value - Der zu entschärfende und zu bereinigende String
				*
				*	@return String 				- Originalwert oder der entschärfte und bereinigte String
				*
				*/
				function cleanString($value) {
if(DEBUG_F)		echo "<p class='debugCleanString'><b>Line " . __LINE__ . "</b>: Aufruf cleanString('$value') <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					// trim() entfernt am Anfang und am Ende eines Strings alle 
					// sog. Whitespaces (Leerzeichen, Tabulatoren, Zeilenumbrüche)
					$value = trim($value);

					// Falls der String bereits kodierte HTML-Snippets enthält (bspw. beim Auslesen 
					// und erneuten Entschärfen eines bereits entschärften Strings aus der DB), 
					// werden diese erst einmal zurückgewandelt, um &amp;apos;-Konstrukte o.Ä. zu vermeiden
					$value = htmlspecialchars_decode($value, ENT_QUOTES | ENT_HTML5);
					
					// htmlspecialchars() entschärft HTML-Steuerzeichen wie < > & '' ""
					// und ersetzt sie durch &lt;, &gt;, &amp;, &apos; &quot;
					// ENT_QUOTES | ENT_HTML5 ersetzt zusätzlich ' durch &#039;
					$value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
					
					// Damit cleanString() nicht NULL-Werte in Leerstings verändert, wird 
					// geprüft, ob $value überhaupt einen gültigen Wert besitzt
					if( !$value ) {
						$value = NULL;
					}
					
					return $value;
					
				}
				
				
/**********************************************************************************/


				/**
				*
				*	Prüft einen String auf Leerstring, Mindest- und Maxmimallänge
				*
				*	@param String $value 									- Der zu prüfende String
				*	@param [Integer $minLength=MIN_INPUT_LENGTH] 	- Die erforderliche Mindestlänge
				*	@param [Integer $maxLength=MAX_INPUT_LENGTH] 	- Die erlaubte Maximallänge
				*
				*	@return String/NULL - Ein String bei Fehler, ansonsten NULL
				*	
				*/
				function checkInputString($value, $minLength=MIN_INPUT_LENGTH, $maxLength=MAX_INPUT_LENGTH) {
if(DEBUG_F)		echo "<p class='debugCheckInputString'><b>Line " . __LINE__ . "</b>: Aufruf checkInputString('$value' [$minLength | $maxLength])</p>\r\n";					
					
					$errorMessage = NULL;
					
					// Prüfen auf leeres Feld
					if( !$value ) {
						$errorMessage = "Dies ist ein Pflichtfeld!";
					
					// Prüfen auf Mindestlänge
					} elseif( mb_strlen($value) < $minLength ) {
						$errorMessage = "Muss mindestes $minLength Zeichen lang sein!";
						
					// Prüfen auf Maximallänge
					} elseif( mb_strlen($value) > $maxLength ) {
						$errorMessage = "Darf maximal $maxLength Zeichen lang sein!";
						
					}
					
					return $errorMessage;
					
				}


/**********************************************************************************/


				/**
				*
				*	Prüft eine Email-Adresse auf Leerstring und Validität
				*
				*	@param String $value - Die zu prüfende Email-Adresse
				*
				*	@return String/NULL - Ein String bei Fehler, ansonsten NULL
				*
				*/
				function checkEmail($value) {
if(DEBUG_F)		echo "<p class='debugCheckEmail'><b>Line " . __LINE__ . "</b>: Aufruf checkEmail('$value') <i>(" . basename(__FILE__) . ")</i></p>\r\n";	

					$errorMessage = NULL;
					
					// Prüfen auf Leerstring
					if( $value === "" ) {
						$errorMessage = "Dies ist ein Pflichtfeld!";

					// Email auf Validität prüfen
					} elseif( !filter_var($value, FILTER_VALIDATE_EMAIL) ) {
						$errorMessage = "Dies ist keine gültige Email-Adresse!";
					}
				
					return $errorMessage;
					
				}


/**********************************************************************************/


				/**
				*
				*	Prüft ein hochgeladenes Bild auf MIME-Type, Datei- und Bildgröße
				*	Speichert das erfolgreich geprüfte Bild unter einem zufällig generierten Dateinamen
				*
				*	@param Array $uploadedImage											- Die Bildinformationen aus $_FILES
				*	@param [Int $maxWidth = IMAGE_MAX_WIDTH]							- Die maximal erlaubte Bildbreite in PX
				*	@param [Int $maxHeight = IMAGE_MAX_HEIGHT]						- Die maximal erlaubte Bildhöhe in PX
				*	@param [Int $maxSize = IMAGE_MAX_SIZE]								- Die maximal erlaubte Dateigröße in Bytes
				*	@param [Array $allowedMimeTypes = IMAGE_ALLOWED_MIMETYPES]	- Whitelist der erlaubten MIME-Types
				*	@param [String $uploadPath = IMAGE_UPLOADPATH]					- Das Speicherverzeichnis auf dem Server
				*
				*	@return Array { "imageError" => String/NULL 	- Fehlermeldung im Fehlerfall, 
				*						 "imagePath"  => String 		- Der Speicherpfad auf dem Server }
				*
				*/
				function imageUpload( 	$uploadedImage,
												$maxWidth 			= IMAGE_MAX_WIDTH,
												$maxHeight 			= IMAGE_MAX_HEIGHT,
												$maxSize 			= IMAGE_MAX_SIZE,
												$allowedMimeTypes = IMAGE_ALLOWED_MIMETYPES,
												$uploadPath 		= IMAGE_UPLOAD_PATH
											) {
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . "</b>: Aufruf imageUpload() <i>(" . basename(__FILE__) . ")</i></p>\r\n";	
					/*
if(DEBUG_F)		echo "<pre class='debugImageUpload'><b>Line " . __LINE__ . "</b>: \r\n";					
if(DEBUG_F)		print_r($uploadedImage);					
if(DEBUG_F)		echo "</pre>\r\n";
					*/	
					/*
						Das Array $_FILES['avatar'] bzw. $uploadedImage enthält:
						Den Dateinamen [name]
						Den generierten (also ungeprüften) MIME-Type [type]
						Den temporären Pfad auf dem Server [tmp_name]
						Die Dateigröße in Bytes [size]
					*/
					
					/********** BILDINFORMATIONEN SAMMELN **********/
					
					// Dateinamen
					$fileName = $uploadedImage['name'];
					// $fileName = "Äußerst blöder namü";
					// ggf. Leerzeichen durch "_" ersetzen
					$fileName = str_replace(" ", "_", $fileName);
					// Dateiname in Kleinbuchstaben umwandeln
					$fileName = mb_strtolower($fileName);				
					// Umlaute ersetzen
					$fileName = str_replace( array("ä","ö","ü","ß"), array("ae", "oe", "ue", "ss"), $fileName );
					
					// zufälligen Dateinamen generieren
					$randomPrefix = rand(1,999999) . str_shuffle("abcdefghijklmnopqrstuvwxyz") . time();
					$fileTarget = $uploadPath . $randomPrefix . "_" . $fileName;
					
					// Dateigröße
					$fileSize = $uploadedImage['size'];
					
					// Temporärer Pfad auf dem Server
					$fileTemp = $uploadedImage['tmp_name'];
					
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . "</b>: \$fileName: $fileName <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . "</b>: \$fileSize: " . round($fileSize/1024, 2) . " kB <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . "</b>: \$fileTemp: $fileTemp <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . "</b>: \$fileTarget: $fileTarget <i>(" . basename(__FILE__) . ")</i></p>\r\n";
					
					// Genauere Informationen zum Bild holen
					$imageData = @getimagesize($fileTemp);

					/*
if(DEBUG_F)		echo "<pre class='debugImageUpload'><b>Line " . __LINE__ . "</b>: \r\n";					
if(DEBUG_F)		print_r($imageData);					
if(DEBUG_F)		echo "</pre>\r\n";
					*/
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
					$imageWidth 	= $imageData[0];
					$imageHeight 	= $imageData[1];
					$imageMimeType = $imageData['mime'];
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . "</b>: \$imageWidth: $imageWidth px <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . "</b>: \$imageHeight: $imageHeight px <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . "</b>: \$imageMimeType: $imageMimeType <i>(" . basename(__FILE__) . ")</i></p>\r\n";
					

					/********** BILD PRÜFEN **********/
					
					// MIME-Type prüfen
					// Whitelist mit erlaubten Bildtypen
					// $allowedMimeTypes = array("image/jpg", "image/jpeg", "image/gif", "image/png");
					
					if( !in_array($imageMimeType, $allowedMimeTypes) ) {
						$errorMessage = "Dies ist kein gültiger Bildtyp!";
						
					// Maximal erlaubte Bildhöhe	
					} elseif( $imageHeight > $maxHeight ) {
						$errorMessage = "Die Bildhöhe darf maximal $maxHeight Pixel betragen!";
						
					// Maximal erlaubte Bildbreite	
					} elseif( $imageWidth > $maxWidth ) {
						$errorMessage = "Die Bildbreite darf maximal $maxWidth Pixel betragen!";
						
					// Maximal erlaubte Dateigröße	
					} elseif( $fileSize > $maxSize ) {
						$errorMessage = "Die Dateigröße darf maximal " . round($maxSize/1024, 2) . " kB betragen!";
					
					// Wenn es keinen Fehler gab
					} else {
						$errorMessage = NULL;
					}
					
					
					/********** ABSCHLIESSENDE BILDPRÜFUNG **********/
					if( !$errorMessage ) {
						// Erfolgsfall
if(DEBUG_F)			echo "<p class='debugImageUpload ok'><b>Line " . __LINE__ . "</b>: Die Bildprüfung ergab keinen Fehler. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						
						/********** BILD SPEICHERN **********/
						if( !@move_uploaded_file($fileTemp, $fileTarget) ) {
							// Fehlerfall
if(DEBUG_F)				echo "<p class='debugImageUpload err'><b>Line " . __LINE__ . "</b>: Fehler beim Speichern des Bilds auf dem Server! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							$errorMessage = "Fehler beim Speichern des Bildes auf dem Server!";
							
						} else {
							// Erfolgsfall
if(DEBUG_F)				echo "<p class='debugImageUpload ok'><b>Line " . __LINE__ . "</b>: Das Bild wurde erfolgreich auf dem Server gespeichert. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
														
						}

					}
					
					/********** FEHLERMELDUNG UND BILDPFAD ZURÜCKGEBEN **********/
					
					return array("imageError" => $errorMessage, "imagePath" => $fileTarget);
					
				}


/**********************************************************************************/
?>
















