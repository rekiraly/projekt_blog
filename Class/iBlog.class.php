<?php
/*******************************************************************************************/


				/*************************************/
				/********** INTERFACE iBLOG ******/
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
				interface iBlog {
					
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
				
				
					/********** BLOG_ID **********/
					public function getBlog_id();
					public function setBlog_id($value);
					
					/********** BLOG_HEADLINE **********/
					public function getBlog_headline();
					public function setBlog_headline($value);
					
					
					/********** BLOG_IMAGE **********/
					public function getBlog_image();
					public function setBlog_image($value);
					
					/********** BLOG_IMAGEALIGNMENT **********/
					public function getBlog_imageAlignment();
					public function setBlog_imageAlignment($value);
					
					/********** BLOG_CONTENT **********/
					public function getBlog_content();
					public function setBlog_content($value);
					
					/********** BLOG_DATE **********/
					public function getBlog_date();
					public function setBlog_date($value);
					
					/********** BLOG_CATEGORY **********/
					public function getCategory();
					public function setCategory($value);
					
					
					
					/********** BLOG_USER **********/
					public function getUser();
					public function setUser($value);
					/***********************************************************/
					

					/******************************/
					/********** METHODEN **********/
					/******************************/
					
					
					/********** STATISCHE METHODE ZUM AUSLESEN ALLER PRODUKTE AUS DER DB **********/
					public static function fetchAllBlogFromDb($pdo);
					
					public static function fetchBlogEntriesByCategoryFromDb($pdo, $cat_id);

					public static function fetchBlogEntriesByThemaFromDb($pdo, $thema_id );
					
					
					/***********************************************************/
					
				}
				
				
/*******************************************************************************************/
?>