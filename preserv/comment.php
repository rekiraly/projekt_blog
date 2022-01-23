

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

		<header >
            <h1 class="dashboard fleft">PHP-Projekt Blog - Commentary</h1>
            <div class="fright">

                <div style='text-align:right'>
                    <a href="?action=logout">Logout</a><br>
                    <a href="index.php"><< zum Frontend</a>
                </div>
            </div>
            <div class="clearer"></div>
		</header>




		<!-------------------------------------------------------------------------------->
        <?php
session_name("blogProject");
session_start();

if (isset($_SESSION['usr_id'])): ?>
            <p id="name" value="<?='$_SESSION[usr_firstname] $_SESSION[usr_lastname]'?>">Aktiver Benutzer: <?="$_SESSION[usr_firstname] $_SESSION[usr_lastname]"?></p>

        <?php endif?>

			<!--------------------------- NEW BLOG ENTRY FORM -------------------------------->

		<h2 class="dashboard">Commentary for Thema:</h2>
        <h3 id=catName><?=$_GET['category']?></h3>
		<!-- Form Blog-Eintrag erstellen -->

        <?php if (isset($_SESSION['usr_id'])): ?>
			<div id="addCommentContainer">
                <p>Добавить комментарий: </p>

                <form id="addCommentForm" method="post" action="">
				    <div>
					     <!--<input class="dashboard" type="hidden" name="formsentNewComment">-->


					    <label for="body">Содержание комментария</label>
					    <br>
					    <textarea name="body" id="comment" cols="30" rows="10"></textarea>
					    <br>

					    <button id="button" type="button">Отправить</button>
				    </div>
			    </form>
		    </div>

        <?php endif?>



		<!-------------------------------------------------------------------------------->





		<div class="clearer"></div>
<script>
    //.log("name:"+ name);

 	var button = document.getElementById('button');
	xmlhttp = new XMLHttpRequest();
 	button.addEventListener('click', function() {

   		var name = document.getElementById('name').value.replace(/<[^>]+>/g,'');

		var category = document.getElementById('catName').value.replace(/<[^>]+>/g,'');
		var name = 1;
       	var comment = document.getElementById('comment').value.replace(/<[^>]+>/g,'');
           console.log("name:"+ comment);
		console.log("name:"+name);
   if(comment === '') {
    alert('Напишите комментарий!');
    return false;
   }
   xmlhttp.open('post', 'libs/add_comment.php', true);
   xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
   xmlhttp.send("name=" + encodeURIComponent(name) + "&comment=" + encodeURIComponent(comment) + "&category=" + encodeURIComponent(category));
  });
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>


	</body>
</html>






