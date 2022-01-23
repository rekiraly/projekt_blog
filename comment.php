
<!doctype html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

	<head>
		<meta charset="utf-8">
		<title>'MyVoyage' Blog</title>
        <link rel="icon" type="image/png" href="http://example.com/myicon.png">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/debug.css">


	</head>

	<body class="dashboard" onload="getComments();">

		<!--------------------------------- HEADER ----------------------------------------->

		<header >
            <h1 class="dashboard fleft">'MyVoyage' Blog - Commentary</h1>
            <div class="fright">

                <div style='text-align:right'>
                    <?php if (isset($_SESSION['usr_id'])): ?>
                        <a href="?action=logout">Logout</a><br>
                    <?php endif?>
                    <a href="index.php"><< zum Frontend</a>
                </div>
            </div>
            <div class="clearer"></div>
		</header>





		<!-------------------------------------------------------------------------------->


<?php

session_name("blogProject");
session_start();
require_once "include/config.inc.php";
require_once "include/db.inc.php";
require_once "include/form.inc.php";
include_once "include/dateTime.inc.php";

require_once "class/iBlog.class.php";
require_once "class/iCategory.class.php";
require_once "class/iUser.class.php";

require_once "class/Blog.class.php";
require_once "class/Category.class.php";
require_once "class/User.class.php";

if (isset($_GET['action'])) {

    $action = cleanString($_GET['action']);

    if ($_GET['action'] == "logout") {
        session_destroy();
        header("Location: index.php");
        exit();
    }

}

if (isset($_SESSION['usr_id'])): ?>

            <p id="name" value=<?="$_SESSION[usr_lastname]"?>>Aktiver Benutzer: <?="$_SESSION[usr_firstname] $_SESSION[usr_lastname]"?></p>

        <?php endif?>

			<!--------------------------- NEW BLOG ENTRY FORM -------------------------------->

		<h2 class="dashboard">Commentary for Thema:</h2>
        <h3 id=catName><?=$_GET['category']?></h3>
		<!-- Form Blog-Eintrag erstellen -->

        <main >
        </main>

        <?php if (isset($_SESSION['usr_id'])): ?>
			<div id="addCommentContainer">
                <p>Добавить комментарий: </p>

                <form action="" method="post">
                    <!--<input type="hidden" name="formsentRegistration">-->


                    <textarea id="comment" cols="30" rows="10"></textarea><br>
                    <button id="button" type="button">Отправить</button>
                    <h2>Комментарии:</h2>
                </form>
		    </div>

        <?php endif?>



		<!-------------------------------------------------------------------------------->





		<div class="clearer"></div>

        <script>
        <!--------GET COMMENT--------------------->

        function getComments(count = 0) {
            var catName = document.getElementById('catName').innerHTML.replace(/<[^>]+>/g, '');
            //console.log('test:'+ catName + ' count '+count);

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open('post', 'libs/get_comment.php', true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlhttp.send("catName=" + encodeURIComponent(catName) + "&count=" + count);
            //xmlhttp.send('count=' + count);
            xmlhttp.onreadystatechange = function() {
            if(xmlhttp.readyState == 4) {
            if(xmlhttp.status == 200) {
                var data = xmlhttp.responseText;
                console.log(data);
                if(data !='empty') {
                    data = JSON.parse(data);
                    console.log(data);
                    for(var i = 0; i<data.length; i++) {
                        var parent = document.getElementsByTagName('body')[0];
                        var elem = document.createElement('div');
                        elem.className = 'comments';
                        parent = parent.appendChild(elem);//присваиваем body дочерний элемент div и теперь parent это он

                        elem = document.createElement('hr');
                        parent.appendChild(elem);

                        elem = document.createElement('span');
                        parent.appendChild(elem);//в div вставляем элемент span

                        var text = data[i].usr_firstname + ' ' + data[i].usr_lastname;
                        var textNode = document.createTextNode(text);
                        elem.appendChild(textNode);
                        elem.className = 'nameComments';

                        elem = document.createElement('div');
                        elem.className = 'comment';
                        parent.appendChild(elem);
                        text = data[i].com_inhalt;
                        textNode = document.createTextNode(text);
                        elem.appendChild(textNode);
                        var max = data[i].com_id;
                    }
                    count = max;
                }
            }
            }
            };
            setTimeout(function() {
            getComments(count);
            }, 3000);
        }
    </script>
        <!---------ADD COMMENT------->
        <script>
        console.log(document.getElementById('name').innerHTML);
        console.log(document.getElementById('catName').innerHTML);
        var button = document.getElementById('button'),
        xmlhttp = new XMLHttpRequest();
        button.addEventListener('click', function () {
            var catName = document.getElementById('catName').innerHTML.replace(/<[^>]+>/g, ''),

                comment = document.getElementById('comment').value.replace(/<[^>]+>/g, '');
            if (comment === '') {
                alert('Напишите комментарий!');
                return false;
            } else {
                document.getElementById('comment').value = "";
                xmlhttp.open('post', 'libs/add_comment.php', true);
                xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xmlhttp.send("catName=" + encodeURIComponent(catName) + "&comment=" + encodeURIComponent(comment));

                /*
                xhttp.open('GET', 'libs/add_comment.php?name='+name+'&comment='+comment, true);
                //xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhttp.send();*/


            }

        });

       </script>
       <!--------END GET COMMENT--------------------->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>


	</body>
</html>






