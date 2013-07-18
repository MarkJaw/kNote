<?php

function kNote(){
	if(isset($_SESSION['login'])){
		show_notes();
	} else {
		show_login();
	}
}
	
function debug(){

	if($_GET['sesion'] == "reset_badlogin"){
		$_SESSION['failed_login'] = "0";
	}
	if($_GET['sesion'] == "clear"){
		session_destroy();
	}
	if($_GET['debug'] == "0"){
		$_SESSION['debug'] = "0";
	}
	if($_SESSION['debug'] == "1" | $_GET['debug'] == "1"){
		if (empty($_SESSION['debug'])) {
		   $_SESSION['debug'] = "1";
		}
		echo "<div id=\"note\"><div id=\"cloper_add_info\">";
		echo "session_id: <b>".session_id()."</b><br>";
		echo "session_save_path: <b>".session_save_path()."</b><br>";
		echo "session_cache_expire: <b>".session_cache_expire()."</b><br>";
		echo "<b>SESSION DATA</b><br>";
		Print_r ($_SESSION);
		echo "<br><b>POST DATA</b><br>";
		Print_r ($_POST);
		echo "<br><b>GET DATA</b><br>";
		Print_r ($_GET);
		echo "</div></div>";
	}
}

function password_hash($password){
	$salty = "Everyone needs a healthy does of Foalcon once in a while.";
	$password_hash = hash("md5", "$password$salty");
	return $password_hash;
}


function show_login(){
	if($_GET['what'] == ""){
		show_login_form();
	}
	else if($_GET['what'] == "register"){
		echo "<div id=\"note\">LOL. REJKESTRACJA XD</div>";
	}
	else if($_GET['what'] == "faq"){
		echo "<div id=\"note\">LOL. FAQ :P</div>";
	}
}

function show_login_form(){
	if($_POST['login'] && $_POST['haslo']) {
		$login = addslashes($_POST['login']);
		$haslo = addslashes($_POST['haslo']);
		$query = "SELECT * FROM users WHERE login = \"$login\" AND password = \"$haslo\"";
		$wynik = mysql_query($query);
		
			if ($row = mysql_fetch_array($wynik)) {
				if($row['status'] == '1'){
					$_SESSION['id'] =  $row['id'];
					$_SESSION['login'] = $login;
					$_SESSION['access'] = $row['access'];
					//$ip = $_SERVER['REMOTE_ADDR'];
					//$query = mysql_query ("INSERT INTO logs (action) VALUES ('<b>$login</b> loged in with access level <b>$level</b> from ip: <a href=\"http://www.infosniper.net/index.php?ip_address=$ip&map_source=1&overview_map=1&lang=1&map_type=1&zoom_level=7\"><b>$ip</b></a>.');");
					echo "<div id=\"note\"><meta http-equiv=\"Refresh\" content=\"0; url=?\" /><center>Zostałeś zalogowany.<br><a href=\"?\">Dalej</a></center></div>";
				} else {
					$_SESSION['failed_login'] = $_SESSION['failed_login'] + 1;
				//	$query = mysql_query ("INSERT INTO logs (action) VALUES ('<font color=\"red\">Failed attempt to login by <b>$login</b>. Account is disabled.</font>');");
					echo "<div id=\"note\"><meta http-equiv=\"Refresh\" content=\"5; url=?\" /><center>To konto jest nie aktywne.<br>Prosze sie skontaktować z administracją :)<br><a href=\"?\">Powrót</a></center></div>";
				}
			} 
			else {
				$_SESSION['failed_login'] = $_SESSION['failed_login'] + 1;
				$ip = $_SERVER['REMOTE_ADDR'];
			//	$query = mysql_query ("INSERT INTO logs (action) VALUES ('<font color=\"red\">Failed attempt to login using <b>$login</b> as user name from ip: <b>$ip</b>.</font>');");
				echo "<div id=\"note\"><meta http-equiv=\"Refresh\" content=\"5; url=?\" /><center>Błąd logowania! <a href=\"index.php\">Sprobuj ponownie!</a></center></div>";
			}
	} 
	else {
		if($_SESSION['failed_login'] < "3"){
			echo "<div id=\"note\"><center><table>
			<b>Proszę sie zalogować</b>
			<form method=\"post\">
			<tr><td>Login:</td><td><input type=\"text\" name=\"login\" autocomplete=\"off\"></td></tr>
			<tr><td>Hasło:</td><td><input type=\"password\" name=\"haslo\" autocomplete=\"off\"></td></tr>
			<tr><td colspan=\"2\"><input style=\"width: 100%\" type=\"submit\" value=\"Zastosuj\"></form></td></tr>
			</table></center></div>";
		} else {
			//echo"<div id=\"note\"><img width=600px src=\"http://dl.dropbox.com/u/31151021/mlp/reaction/191806__UNOPT__safe_fluttershy_image-macro_vulgar_pegasus_mare.png\"></center></div><br>";
			echo"<div id=\"note\"><center>Za dużo nieudanych prób logowania.<br>Panel logowania nie aktywny.<br>Życzymy miłego dnia :)</center></div>";
		}
	}
}


function menu(){
	if(isset($_SESSION['login'])){
		if($_GET['what'] == ""){
			echo "<li><a href=\"?what=new\">New note</a></li>";
			echo "<li><a href=\"?what=new\">Settings</a></li>";
			echo "<li><a href=\"?what=logout\">Logout</a></li>";
		}
		else if ($_GET['what'] == "note"){
			$id = $_GET['id'];
			echo "<li><a href=\"?\">Notes</a></li>";
			echo "<li><a href=\"?what=edit&id=$id\">Edit</a></li>";
			echo "<li><a href=\"?what=new\">Remove</a></li>";
			echo "<li><a href=\"?what=settings\">Settings</a></li>";
			echo "<li><a href=\"?what=logout\">Logout</a></li>";
		}
		else if ($_GET['what'] == "edit"){
			$id = $_GET['id'];
			echo "<li><a href=\"?\">Notes</a></li>";
			echo "<li><a href=\"?what=new\">Remove</a></li>";
			echo "<li><a href=\"?what=settings\">Settings</a></li>";
			echo "<li><a href=\"?what=logout\">Logout</a></li>";
		} 
		else {
			echo "<li><a href=\"?\">Notes</a></li>";
			echo "<li><a href=\"?what=settings\">Settings</a></li>";
			echo "<li><a href=\"?what=logout\">Logout</a></li>";
		}
	}  else {
			echo "<li><a href=\"?\">LogIn</a></li>";
			echo "<li><a href=\"?what=register\">Register</a></li>";
			echo "<li><a href=\"?what=faq\">FAQ</a></li>";
	}
}

function br2nl($text)
{
    return  preg_replace('/<br\\s*?\/??>/i', '', $text);
}

function show_notes(){

	if($_GET['what'] == ""){
		//$sql = "SELECT id, date, LEFT(note, 60) AS note FROM notes ORDER BY id;";
		$sql = "SELECT id, user_id, date, name FROM notes WHERE  user_id='".$_SESSION['id']."' ORDER BY date DESC;";
		$wynik = mysql_query($sql) or die('zapytanie :'.$sql.' blad:'.mysql_error());
		while ($rekord = mysql_fetch_assoc ($wynik)) {
			$id = $rekord['id'];
			$date = $rekord['date'];
			$name = $rekord['name'];
			echo "<div id=\"note\"><a href=\"?what=note&id=$id\"><img style=\"float:left;margin-right: 5px;\"src=\"note.png\"><b>$name</b></a> - <a href=\"?what=edit&id=$id\">Edit</a> <a href=\"?what=note&id=$id\">Remove</a><br>$date</div>";
		}
	}
	else if ($_GET['what'] == "settings"){
			echo "<div id=\"note\">LOL NOPE! THERE IS NO SETTINGS :D</div>";
	}
	else if ($_GET['what'] == "note"){
		$id = $_GET['id'];
		$sql = "SELECT * FROM notes WHERE id=$id;";
		$wynik = mysql_query($sql) or die('zapytanie :'.$sql.' blad:'.mysql_error());
		while ($rekord = mysql_fetch_assoc ($wynik)) {
			$id = $rekord['id'];
			$date = $rekord['date'];
			$note = $rekord['note'];
			echo "<div id=\"note\">$note</div>";
		}
	}
	else if ($_GET['what'] == "new"){
		if($_POST['note'] == ""){
			echo "<form method=\"post\" action=\"?what=new\">";
			echo "<input class=\"input\" type=\"text\" size=\"50\" name=\"name\">";
			echo"<textarea class=\"input\" rows=\"30\" cols=\"40\" name=\"note\" wrap=\"physical\"></textarea><br>";
			echo "<input class=\"input\" width=\"100%\" type=\"submit\" value=\"Save note\" name=\"submit\">";
			echo "</form>";
		} else {
			$name = $_POST['name'];
			$note = nl2br($_POST['note']);
			$query = "INSERT INTO notes (user_id, name, note) VALUES ('1', '$name', '$note');";
			$wynik = mysql_query ($query);
			echo "<a href=\"?\"><div id=\"note\">New note created. Return to index.</div></a>";
		}
	}
	else if ($_GET['what'] == "edit"){
		if($_POST['note'] == ""){
			$id = $_GET['id'];
			$sql = "SELECT * FROM notes WHERE id=$id;";
			$wynik = mysql_query($sql) or die('zapytanie :'.$sql.' blad:'.mysql_error());
			while ($rekord = mysql_fetch_assoc ($wynik)) {
				$id = $rekord['id'];
				$name = $rekord['name'];
				$note = br2nl($rekord['note']);
				echo "<form method=\"post\" action=\"?what=edit&id=$id\">";
				echo "<input class=\"input\" type=\"text\" size=\"50\" name=\"name\" value=\"$name\">";
				echo"<textarea class=\"input\" rows=\"30\" cols=\"40\" name=\"note\" wrap=\"physical\">$note</textarea><br>";
				echo "<input class=\"input\" width=\"100%\" type=\"submit\" value=\"Save note\" name=\"submit\">";
				echo "</form>";
			}
		} else {
			$id = $_GET['id'];
			$name = $_POST['name'];
			$note = nl2br($_POST['note']);
			$query="UPDATE notes SET name='$name', note='$note' WHERE id='$id'";
		//	$query = "INSERT INTO notes (id, user_id, name, note) VALUES ('$id', '1', '$name', '$note');";
			$wynik = mysql_query ($query);
			echo "<a href=\"?\"><div id=\"note\">Note edited. Return to index.</div></a>";
		}
	}
	else if ($_GET['what'] == "logout"){
		session_destroy();
			echo "<div id=\"note\"><meta http-equiv=\"Refresh\" content=\"0; url=?\" /><center>Zostałeś wylogowany.<br><a href=\"?\">Dalej</a></center></div>";
	}
	else {
		echo "WUT???";
	}
}

?>