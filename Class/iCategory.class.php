<?php
/*******************************************************************************************/


				/*************************************/
				/********** INTERFACE iCATEGORY ******/
				/*************************************/

				/*
					So wie eine Klasse quasi eine Blaupause für alle später aus ihr zu erstellenden Objekte/Instanzen
					darstellt, kann man ein Interface quasi als eine Blaupause für eine später zu erstellende Klasse
					ansehen.	Hierzu wird ein Interface definiert, das später in die entsprechene Klasse implementiert 
					wird. Der Sinn des Interfaces besteht darin, dass innerhalb des Interfaces sämtliche später 
					innerhalb der Klasse zu erstellende Methoden bereits vordeklariert werden.
					Die Klasse muss dann zwingend sämtliche im Interface deklarierten Methoden enthalten.
					
					Ein Interface darf keinerlei Attribute beinhalten.
					Die im Interface definierten Methoden müssen public sein und dürfen über keinen 
					Methodenrumpf {...} verfügen.
					An die Methode zu übergebende Parameter müssen im Interface vordefiniert sein ($value).
				*/

				
/*******************************************************************************************/


				// Das Schlüsselwort 'abstract' bedeutet, dass aus dieser Klasse keine Instanz 
				// bzw. kein Objekt gebildet werden kann. Die Klasse kann sich aber nach wie vor 
				// vererben, so dass stattdessen aus ihren Kinderklassen Instanzen bzw. Objekte gebildet
				// werden können.
				interface iCategory {
					
					/*
						Ein Interface darf keinerlei Attribute beinhalten.
					*/	
					
					
					/***********************************************************/
					
					
					/*********************************/
					/********** KONSTRUKTOR **********/
					/*********************************/
					
					
					
					
					/***********************************************************/

					
					/*************************************/
					/********** GETTER & SETTER **********/
					/*************************************/
				
				
					/********** USR_ID **********/
					public function getCat_id();
					public function setCat_id($value);
					
					/********** USR_FIRSTNAME **********/
					public function getCat_name();
					public function setCat_name($value);
					
					
					
					
					
					/***********************************************************/
					

					/******************************/
					/********** METHODEN **********/
					/******************************/
					
					
					/********** CHECK IF CATEGORY EXISTS IN DB **********/
					public function catExists($pdo);
										
					/********** SAVE NEW CATEGORY TO DB **********/
					public function saveToDb($pdo);
										
					/********** FETCH SINGLE CATEGORY FROM DB  BY ID **********/
					//public function fetchFromDb($pdo);
					
					
					
					
					/***********************************************************/
					
				}
				
				
/*******************************************************************************************/
?>