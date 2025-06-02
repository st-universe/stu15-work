<?php
function addlog($type, $category, $userid, $script, $additional)
{
    // Parameter zu Strings konvertieren um Array-to-string Fehler zu vermeiden
    $type = is_array($type) ? serialize($type) : (string)$type;
    $category = is_array($category) ? serialize($category) : (string)$category;
    $userid = is_array($userid) ? serialize($userid) : (string)$userid;
    $script = is_array($script) ? serialize($script) : (string)$script;
    $additional = is_array($additional) ? serialize($additional) : (string)$additional;

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
}