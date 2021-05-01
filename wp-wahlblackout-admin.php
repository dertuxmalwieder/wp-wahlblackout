<?php
/*
WP-WahlBlackout ACP, version 20210105
Made with love.
*/

if ($_POST['uninstall'] == '1') {
	// delete settings
	delete_option('wahlblackout_verfehlung');
	delete_option('wahlblackout_wahltyp');
	delete_option('wahlblackout_regierung');
	delete_option('wahlblackout_datum');
	delete_option('wahlblackout_datum_rein');
	delete_option('wahlblackout_datum_raus');
	delete_option('wahlblackout_cache_leer');
?>
    <div class="updated"><p><strong>WP-WahlBlackout wurde erfolgreich deinstalliert. Danke für Ihren Einsatz für die Demokratie!</strong></p></div>  
<?php
} elseif ($_POST['savesettings'] == '1') {
    // save settings
    $verfehlung = $_POST['wahlblackout_verfehlung'];
    $regierung = $_POST['wahlblackout_regierung'];
    $wahltyp = $_POST['wahlblackout_wahltyp'];

    update_option('wahlblackout_verfehlung', $verfehlung);
    update_option('wahlblackout_regierung', $regierung);
    update_option('wahlblackout_wahltyp', $wahltyp);

    $wahldatum = date('Y-m-d',strtotime($_POST['wahlblackout_datum'])); // kommt als beliebig formatiertes Datum an, also umwandeln...
    $wahldatum_rein = strtotime($wahldatum . " 08:00 AM");
    $wahldatum_raus = strtotime($wahldatum . " 06:00 PM");

    update_option('wahlblackout_datum', $wahldatum);
    update_option('wahlblackout_datum_rein', $wahldatum_rein);
    update_option('wahlblackout_datum_raus', $wahldatum_raus);
  
?>
    <div class="updated"><p><strong>Einstellungen gespeichert.</strong></p></div>  
<?php
} else {
    // read settings
    $verfehlung = get_option('wahlblackout_verfehlung');
    $wahldatum = get_option('wahlblackout_datum');
    $wahltyp = get_option('wahlblackout_wahltyp');
    $regierung = get_option('wahlblackout_regierung');
}
?>

<div class="wrap">
	<h2>WP-WahlBlackout-Einstellungen</h2>

	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<div class="stuffbox">
			<h3>Einstellungen</h3>
			<div class="inside">
				<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				
				<p>
				<strong>Datum:</strong> <input type="date" name="wahlblackout_datum" value="<?php echo $wahldatum; ?>" required pattern="[0-9]{2}.[0-9]{2}.[0-9]{4}" /><br />
				Tag der <input type="text" name="wahlblackout_wahltyp" value="<?php echo $wahltyp; ?>" size="20" /> im Format "dd.mm.yyyy" (zum Beispiel 24.09.2017).<br />
				</p>

				<p>
				<strong>Countdown-Text (wird im Widget oder per Shortcode <em>[wahlblackout]</em> angezeigt):</strong><br />
				<br />
				In <em>(Countdown)</em> Tagen habt ihr die Möglichkeit, <input type="text" name="wahlblackout_regierung" value="<?php echo $regierung; ?>" size="25" /> wegen <input type="text" name="wahlblackout_verfehlung" value="<?php echo $verfehlung; ?>" size="35" /> abzuwählen.<br />
				</p>

				<input class="button" type="submit" value="Einstellungen speichern &raquo;" name="submit" />
				<input type="hidden" name="savesettings" value="1">
				</form>
			</div>
		</div>

		<div class="stuffbox">
			<h3>Eigene blackout.php</h3>
			<div class="inside">
				<p>
				Wenn Ihnen die mitgelieferte <em>blackout.php</em> nicht gefällt, können Sie auch eine eigene verwenden. Diese legen Sie bitte im Ordner <em>/wp_content/</em> unter dem Namen <em>blackout.php</em> ab, das Plugin wird sie dann automatisch verwenden. Beliebiger HTML- und PHP-Code ist erlaubt.
				</p>
			</div>
		</div>

		<div class="stuffbox">
			<h3 id="uninstall">Deinstallation</h3>
			<div class="inside">
				<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
					<p>Diese Schaltfläche entfernt alle Datenbankeinträge für dieses Plugin. Bitte benutzen Sie sie <strong>vor</strong> Deaktivierung des Plugins.<br /><strong>Achtung:</strong> Dies kann nicht ungeschehen gemacht werden!</p>
					<input type="hidden" name="uninstall" value="1">
					<input class="button" type="submit" value="Optionen löschen &raquo;" />
				</form>
			</div>
		</div>
	</div>
</div>
