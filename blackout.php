<!doctype html>
<html>
<head>
<title>Heute ist <?php echo get_option('wahlblackout_wahltyp'); ?>!</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
<!--
BODY {background-color: #000000; width: 800px; text-align: center; }
H1 {color: #00FF18; font-family: monospace; }
H2 {color: #00FF18; font-family: monospace; }
P {color: #FFFFFF; font-family: monospace; font-size: 14px; }
.fusszeile {font-size: 11px; font-family: sans-serif; color: #606060; }
.uhrzeit {color: #FF0000; font-weight: bold; }
A:link, A:visited {color: #D3D3D3; text-decoration: underline; }
A:hover {color: #FFA500; }
.fusszeile A:link, .fusszeile A:visited {color: #777373; text-decoration: underline; }
//-->
</style>
</head>
<body>
<h1>Lest Stimmzettel, keine Blogs!</h1>

<p>Ihr habt heute noch bis <span class="uhrzeit">18 Uhr</span> Zeit, <?php echo get_option('wahlblackout_regierung'); ?> wegen <?php echo get_option('wahlblackout_verfehlung'); ?> abzuwählen oder ihren politischen Kurs gegen die Anfeindungen zu verteidigen.</p>

<h2>Ihr wisst nicht, was ihr wählen sollt?</h2>

<p>Eine Liste der zur Wahl stehenden Parteien mit Verweisen auf die jeweiligen Inhalte gibt es <a href="https://www.bpb.de/politik/wahlen/wer-steht-zur-wahl/">im Web</a>, einen schnellen Test, falls es schon etwas später ist, auch <a href="https://www.voteswiper.org/de">auf eurem Smartphone</a>.</p>

<p>Hier geht es <span class="uhrzeit">nach 18 Uhr</span> weiter. Nutzt die Gelegenheit, etwas zu ändern!</p>

<hr />

<p class="fusszeile">Dies ist WordPress nebst WP-WahlBlackout-Plugin. Keine Sorge, ich bleib' nicht lange.</p>
</body>
</html>
