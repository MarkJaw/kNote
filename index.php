<?php session_start(); ?>
<?php
	include('mysql.php');
	include('knote.php');
?>

<heda>
	<title>kNote</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>
		.input {
			width: 100%; 
			background: #ccc;
			border: 1px solid #111; 
			padding: 4px; 
		}
		
		body {
			background: #555;
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 10pt;
		}
		
		#menu {
			background: #888 url("knote.png");
			background-repeat:no-repeat;
			width:100%;
			height: 33px;
			top: 0px;
			left: 0px;
			position: fixed;
			background-position: right; 
			border-bottom: 1px solid #000;
		}

		#foot {
			background: #888;
			padding: 2px;
			text-align: center;
			width:100%;
			bottom: 0px;
			left: 0px;
			position: fixed;
			border-top: 1px solid #000;
		}		
		
		#main {
			margin-top: 45px;
		}
		
		ul, ul li {
			display: block;
			list-style: none;
			margin: 0;
			padding: 2;
		}

		ul li {
			float: left;
		}

		ul a:link, ul a:visited {
			text-decoration: none;
			display: block;
			width: 80px;
			text-align: center;
			background-color: #ccc;
			color: #000;
			border: 1px solid #000;
			padding: 3px;
		}

		ul a:hover {
			background-color: #aaa;
		}

		#note {
			background: #ccc;
			border: 1px solid #000;
			margin: 4;
			padding: 4;
		}
		
	</style>
</head>
<body>
<div id="menu">
	<ul>
		<?php menu(); ?>
	</ul>
</div>
<div id="main">
<?php

	kNote();
	debug();
?>
</div>
<div id="foot">kNote by MarkJaw</div>
</body>