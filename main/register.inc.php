<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
    <tr>
        <td class=tdmain>/ <a href="?page=main">STU</a> / <strong>Registrierung</strong></td>
    </tr>
</table><br>
<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
    <?php
	include_once("class/user.class.php");
	$myUser = new user;
	include_once("class/game.class.php");
	$myGame = new game;

	// Variablen initialisieren
	$sent = isset($_POST['sent']) ? $_POST['sent'] : (isset($_GET['sent']) ? $_GET['sent'] : 0);
	$login = isset($_POST['login']) ? trim($_POST['login']) : '';
	$user = isset($_POST['user']) ? trim($_POST['user']) : '';
	$email = isset($_POST['email']) ? trim($_POST['email']) : '';
	$pw = isset($_POST['pw']) ? $_POST['pw'] : '';
	$pw2 = isset($_POST['pw2']) ? $_POST['pw2'] : '';
	$rules = isset($_POST['rules']) ? $_POST['rules'] : 0;
	$seite = isset($_POST['seite']) ? $_POST['seite'] : '';
	$actcode = isset($_GET['actcode']) ? $_GET['actcode'] : '';
	$error = array();

	if ($sent == 1) {
		// Validierung
		if (!$login) $error['login'] = "Du hast keinen Loginnamen angegeben.";
		if (!$user) $error['user'] = "Du hast keinen Spielernamen angegeben.";
		if (strlen(strip_tags($user)) > 40) $error['user'] = "Der Username darf ohne HTML nur aus maximal 40 Zeichen bestehen.";
		if (!$email) $error['email'] = "Du hast keine Emailadresse angegeben.";
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error['email'] = "Hierbei handelt es sich um keine gültige Emailadresse";
		if (!$pw) $error['pw'] = "Du hast kein Passwort angegeben.";
		if (!$pw2) $error['pw2'] = "Du hast das Passwort nicht bestätigt.";
		if (!$rules) $error['rules'] = "Du hast die Regeln nicht bestätigt";
		if ($pw && $pw2 && ($pw != $pw2)) $error['pw2'] = "Die Passwörter stimmen nicht überein.";
		if (strlen($pw) < 5) $error['pw3'] = "Das Passwort muss aus mindestens 5 Zeichen bestehen.";

		if (!$error) {
			$runde = $myGame->getcurrentround();
			$result = $myUser->addUser($login, $user, $email, $pw, $seite, $runde['runde']);
			if ($result['code'] != 1) {
				$error['register'] = $result['msg'];
			} else {
				echo "<tr>
				<td class=tdmain align=center>Registrierung erfolgreich</td>
			</tr>
			<tr>
				<td class=tdmainobg width=400>Deine Registrierung war erfolgreich! Du erhältst in Kürze eine E-Mail mit dem Aktivierungslink.<br>
				Bitte prüfe auch deinen Spam-Ordner.</td>
			</tr>";
				$sent = 0; // Formular nicht mehr anzeigen
			}
		}
	}

	// Aktivierung
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

	// Registrierungsformular anzeigen
	if ($s != 1 && $sent != 2) {
		echo "<tr>
		<td class=tdmain align=center>Registrierung</td>
	</tr>";

		// Fehler anzeigen
		if (!empty($error)) {
			echo "<tr>
			<td class=tdmainobg>
				<div style='color: #ff6666; margin-bottom: 10px;'>
					<strong>Fehler bei der Registrierung:</strong><br>";
			foreach ($error as $err) {
				echo "• " . htmlspecialchars($err) . "<br>";
			}
			echo "</div>
			</td>
		</tr>";
		}

		echo "<tr>
		<td class=tdmainobg>
			<form method='POST' action='?page=register'>
				<input type='hidden' name='sent' value='1'>
				<table width='100%' cellpadding='3' cellspacing='0'>
					<tr>
						<td width='150'><strong>Loginname:</strong></td>
						<td><input type='text' name='login' value='" . htmlspecialchars($login) . "' size='20' maxlength='30' required>
						<br><small>Dein Loginname zum Einloggen (nur Buchstaben und Zahlen)</small></td>
					</tr>
					<tr>
						<td><strong>Spielername:</strong></td>
						<td><input type='text' name='user' value='" . htmlspecialchars($user) . "' size='20' maxlength='40' required>
						<br><small>Dein Name im Spiel (max. 40 Zeichen)</small></td>
					</tr>
					<tr>
						<td><strong>E-Mail:</strong></td>
						<td><input type='email' name='email' value='" . htmlspecialchars($email) . "' size='30' maxlength='100' required>
						<br><small>Deine E-Mail-Adresse für die Aktivierung</small></td>
					</tr>
					<tr>
						<td><strong>Passwort:</strong></td>
						<td><input type='password' name='pw' size='20' maxlength='50' required>
						<br><small>Mindestens 5 Zeichen</small></td>
					</tr>
					<tr>
						<td><strong>Passwort wiederholen:</strong></td>
						<td><input type='password' name='pw2' size='20' maxlength='50' required></td>
					</tr>
					<tr>
						<td><strong>Homepage (optional):</strong></td>
						<td><input type='url' name='seite' value='" . htmlspecialchars($seite) . "' size='40' maxlength='200'>
						<br><small>Deine Homepage (optional)</small></td>
					</tr>
					<tr>
						<td colspan='2'><hr style='border: 1px solid #444;'></td>
					</tr>
					<tr>
						<td colspan='2'>
							<input type='checkbox' name='rules' value='1' " . ($rules ? 'checked' : '') . " required>
							<strong>Ich habe die <a href='?page=rules' target='_blank'>Spielregeln</a> gelesen und akzeptiere sie.</strong>
						</td>
					</tr>
					<tr>
						<td colspan='2' align='center' style='padding-top: 15px;'>
							<input type='submit' value='Registrieren' style='padding: 5px 15px; font-size: 14px;'>
							<input type='reset' value='Zurücksetzen' style='padding: 5px 15px; font-size: 14px; margin-left: 10px;'>
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center style='padding-top: 10px;'>
			<small>Nach der Registrierung erhältst du eine E-Mail mit einem Aktivierungslink.<br>
			Erst nach der Aktivierung kannst du dich einloggen.</small>
		</td>
	</tr>";
	}
	?>
</table>