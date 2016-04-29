<?php
	header("Content-type: text/css");
	$color = "rgb(255, 128, 0)";
	$colorRGBA = "rgba(255, 128, 0, 1)";
	if(isset($_GET['r']) && isset($_GET['g']) && isset($_GET['b']) && isset($_GET['a'])){
		$color = "rgb(" . $_GET['r'] . "," . $_GET['g'] . "," . $_GET['b'] . ")";
		$colorRGBA = "rgba(" . $_GET['r'] . "," . $_GET['g'] . "," . $_GET['b'] . "," . $_GET['a'] . ")";
	}
?>

@CHARSET "UTF-8";

body{
	background-color: rgb(250, 250, 250);
	background-attachment: fixed;
}

body#resultBody{
	background-image: inherit;
}


input[type=text]:focus, textarea:focus, input[type=email]:focus, input[type=tel]:focus {
    outline-color: <?= $color ?>;
    -webkit-box-shadow: 0px 0px 2px 2px <?= $colorRGBA ?>;
	-moz-box-shadow: 0px 0px 2px 2px <?= $colorRGBA ?>;
	box-shadow: 0px 0px 2px 2px <?= $colorRGBA ?>;
    border: 1px solid <?= $colorRGBA ?>;
}
#mglogo > a {
	background-image: linear-gradient(<?= $color ?> 0%, <?= $color ?> 250%);
    background-color: transparent;
    color: white;
}

.logo h1{
	color: <?= $color ?>;
}

.pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus{
	background-color: <?= $color ?>;
	border-color: <?= $color ?>;
}

.pagination > li > a, .pagination > li > span{
	color: <?= $color ?>;
}

.pagination > li > a:hover, .pagination > li > span:hover, .pagination > li > a:focus, .pagination > li > span:focus{
	color: <?= $color ?>;
}

.navbar {
	border-bottom: 3px solid <?= $color ?>;
	box-shadow: 1px 1px 5px 1px rgba(0, 0, 0, 0.2);
}

