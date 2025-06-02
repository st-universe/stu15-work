<?php
function addlog($type, $category, $userid, $script, $additional)
{
    global $myDB;

    // Einfache Logging-Funktion
    $logdir = "/var/www/stuv15tracking/glogs/";
    $logfile = $logdir . date("d_m_y") . ".log";

    // Verzeichnis erstellen falls es nicht existiert
    if (!is_dir($logdir)) {
        mkdir($logdir, 0755, true);
    }

    $timestamp = date("Y-m-d H:i:s");
    $logentry = "[$timestamp] Type: $type, Cat: $category, User: $userid, Script: $script, Add: $additional\n";

    // In Datei schreiben
    if ($handle = fopen($logfile, "a")) {
        fwrite($handle, $logentry);
        fclose($handle);
        chmod($logfile, 0644);
    }

    // Optional: Auch in Datenbank loggen falls Tabelle existiert
    if (isset($myDB)) {
        try {
            $myDB->query("INSERT INTO stu_logs (type, category, user_id, script, additional, timestamp) 
                         VALUES ('$type', '$category', '$userid', '$script', '$additional', NOW())");
        } catch (Exception $e) {
            // Ignoriere DB-Fehler beim Logging
        }
    }
}
