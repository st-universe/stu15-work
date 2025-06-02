<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=main>STU</a> / <strong>Registrierung</strong></td>
	</tr>
</Table><br>
<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<?php
	include_once("class/user.class.php");
	$myUser = new user;
	include_once("class/game.class.php");
	$myGame = new game;

	// Variablen initialisieren
	$sent = isset($_POST['sent']) ? $_POST['sent'] : 0;
	$login = isset($_POST['login']) ? $_POST['login'] : '';
	$user = isset($_POST['user']) ? $_POST['user'] : '';
	$email = isset($_POST['email']) ? $_POST['email'] : '';
	$pw = isset($_POST['pw']) ? $_POST['pw'] : '';
	$pw2 = isset($_POST['pw2']) ? $_POST['pw2'] : '';
	$seite = isset($_POST['seite']) ? $_POST['seite'] : '';
	$actcode = isset($_GET['actcode']) ? $_GET['actcode'] : '';
	$error = array();
	$result = 0;

	if ($sent == 1) {
		if (!$login) $error['login'] = "Du hast keinen Loginnamen angegeben.";
		if (!$user) $error['user'] = "Du hast keinen Spielernamen angegeben.";
		if (!$email) $error['email'] = "Du hast keine Emailadresse angegeben.";
		if (!$pw) $error['pw'] = "Du hast kein Passwort angegeben.";
		if (!$pw2) $error['pw2'] = "Du hast das Passwort nicht bestätigt.";
		if ($pw && $pw2 && ($pw != $pw2)) $error['pw2'] = "Die Passwörter stimmen nicht überein.";
		if (strlen($pw) < 5) $error['pw3'] = "Das Passwort muss aus mindestens 5 Zeichen bestehen.";
		if (!$error) {
			$runde = $myGame->getcurrentround();
			$result = $myUser->addUser($login, $user, $email, $pw, $seite, $runde['runde']);
			if ($result == -1) $error['register'] = "Es sind derzeit keine weiteren Anmeldungen möglich";
			elseif ($result == 0) $error['register'] = "Es existiert schon ein User mit diesem Login oder mit dieser Emailadresse";
			else {
				include_once("inc/functions.inc.php");
				addlog("500", "6", $result, getenv("SCRIPT_NAME"), "-");
			}
		}
	}

	if ($actcode != "") {
		$result = $myUser->activateUser($actcode, $user);
		if ($result == 1) {
			echo "<tr>
			<td class=tdmain align=center>Aktivierung erfolgreich</td>
		</tr>
		<tr>
			<td class=tdmainobg width=400>Du kannst Dich nun mit Deinem Loginnamen und Deinem Passwort einloggen.<br>
			Viel Spaß!</td>
		</tr>";
		} else {
			echo "<tr>
			<td class=tdmain align=center>Aktivierung fehlgeschlagen</td>
		</tr>
		<tr>
			<td class=tdmainobg width=400>Sollte die Aktivierung trotz eines gültigen Aktivierungscodes fehlgeschlagen sein, setze Dich mit den Admins in Kontakt.</td>
		</tr>";
		}
		$s = 1;
	}

	if (!$result || $result == 0) {
		echo "<tr>
		<td class=tdmain align=center colspan=2><b>Registrierung</b></td>
	</tr>
	<form action=?page=register method=post>
	<input type=hidden name=sent value=1>";
		if (isset($error['register'])) echo "<tr><td colspan=2 align=center><font color=Red>" . $error['register'] . "</font></td></tr>";
		echo "<tr>
		<td class=tdmainobg>Loginname</td>
		<td class=tdmainobg><input type=text name=login size=15 value='" . $login . "'></td>
	</tr>";
		if (isset($error['login'])) echo "<tr><td colspan=2 align=center><font color=Red>" . $error['login'] . "</font></td></tr>";
		echo "<tr>
		<td class=tdmainobg>Spielername</td>
		<td class=tdmainobg><input type=text name=user size=15 value='" . $user . "'></td>
	</tr>";
		if (isset($error['user'])) echo "<tr><td colspan=2 align=center><font color=Red>" . $error['user'] . "</font></td></tr>";
		echo "<tr>
		<td class=tdmainobg>Emailadresse</td>
		<td class=tdmainobg><input type=text name=email size=15 value='" . $email . "'></td>
	</tr>";
		if (isset($error['email'])) echo "<tr><td colspan=2 align=center><font color=Red>" . $error['email'] . "</font></td></tr>";
		echo "<tr>
		<td class=tdmainobg>Passwort</td>
		<td class=tdmainobg><input type=password name=pw size=15></td>
	</tr>";
		if (isset($error['pw'])) echo "<tr><td colspan=2 align=center><font color=Red>" . $error['pw'] . "</font></td></tr>";
		if (isset($error['pw3'])) echo "<tr><td colspan=2 align=center><font color=Red>" . $error['pw3'] . "</font></td></tr>";
		echo "<tr>
		<td class=tdmainobg>Passwort wiederholen</td>
		<td class=tdmainobg><input type=password name=pw2 size=15></td>
	</tr>";
		if (isset($error['pw2'])) echo "<tr><td colspan=2 align=center><font color=Red>" . $error['pw2'] . "</font></td></tr>";
		echo "<tr>
		<td class=tdmainobg>Seite</td>
		<td class=tdmainobg><select name=seite>
		<option value=1 SELECTED>Federation
		<option value=2>Romulaner
		<option value=3>Klingonen
		<option value=4>Cardassianer
	</select></td>
	</tr>
	<tr>
		<td colspan=2 align=center class=tdmainobg><input type=submit value=Abschicken></form></td>
	</tr>";
	} elseif ($result > 0) {
		echo "<tr>
		<td class=tdmain align=center><b>Registrierung</b></td>
	</tr>
	<tr>
		<td class=tdmainobg>Die Anmeldung war erfolgreich. Du kannst Dich nun mit '" . $login . "' und Deinem Passwort einloggen.<br>
		Viel Spaß!</td>
	</tr>";
	}
	?>
</table>