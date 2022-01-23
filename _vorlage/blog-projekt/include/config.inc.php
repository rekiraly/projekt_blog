<?php
/************************************************************************************/


				/******************************************/
				/********** GLOBAL CONFIGURATION **********/
				/******************************************/
				
				/********** DATABASE CONFIGURATION **********/
				define("DB_SYSTEM", 			"mysql");
				define("DB_HOST", 			"localhost");
				define("DB_NAME", 			"blog-projekt");
				define("DB_USER", 			"root");
				define("DB_PWD", 				"");
				
				/********** FORMULAR CONFIGURATION **********/
				define("MIN_INPUT_LENGTH", 2);
				define("MAX_INPUT_LENGTH", 256);
				
				/****************IMAGE UPLOAD CONFIGURATION*******************/
				define("IMAGE_INPUT_HEIGHT", 800);
				define("IMAGE_MAX_HEIGHT", 800);//Test
				define("IMAGE_INPUT_WIDTH", 800);
				define("IMAGE_MAX_WIDTH", 800);//Test
				define("IMAGE_MAX_SIZE", 228* 1024);
				define("IMAGE_UPLOAD_PATH", "uploads/userimages/");
				define("IMAGE_ALLOWED_MIMETYPE", array("image/jpeg", "image/jpg", "image/gif", "image/png" ));
			
				/********************STANDART PATH CONFIGURATION *********************************************/
				define("AVATAR_DOMMY_PATH", 				"css/images/avatar_dummy.png");
				
				/********** DEBUGGING **********/
				define("DEBUG", 				true);
				define("DEBUG_F", 			true);
				define("DEBUG_DB",			true);


/************************************************************************************/
?>