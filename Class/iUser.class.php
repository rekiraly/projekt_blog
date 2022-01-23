<?php
/*******************************************************************************************/


				/*************************************/
				/********** INTERFACE iUSER **********/
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
				interface iUser {
					
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
					public function getUsr_id();
					public function setUsr_id($value);
					
					/********** USR_FIRSTNAME **********/
					public function getUsr_firstname();
					public function setUsr_firstname($value);
					
					/********** USR_LASTNAME **********/
					public function getUsr_lastname();
					public function setUsr_lastname($value);
					
					/********** USR_EMAIL **********/
					public function getUsr_email();
					public function setUsr_email($value);
					
					/********** USR_PASSWORD **********/
					public function getUsr_password();
					public function setUsr_password($value);
					
					/********** USR_CITY **********/
					public function getUsr_city();
					public function setUsr_city($value);
					
					
					
					
					
					/********** VIRTUELLE ATTRIBUTE **********/					
					public function getFullName();
					
					
					/***********************************************************/
					

					/******************************/
					/********** METHODEN **********/
					/******************************/
					
					
					/********** CHECK IF EMAIL EXISTS IN DB **********/
					public function emailExists($pdo);
										
					/********** SAVE NEW USER TO DB **********/
					public function saveToDb($pdo);
										
				
					
					
					/***********************************************************/
					
				}
				
				
/*******************************************************************************************/
?>