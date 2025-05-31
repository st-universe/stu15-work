<?php

include_once("class/ship.class.php");

$shipRepository = new ship();

$ships = $shipRepository->getClasses();

echo "<table width=100% cellspacing=1 cellpadding=1 style='background-color: #262323'>
    <tr>
        <td class=tdmain>/ <a href=page=main>STU</a> / <strong>Database</strong></td>
    </tr>
</table>
<br>
<table width=100% cellspacing=1 cellpadding=1 style='background-color: #262323'>
    <tr>
        <td class=tdmainobg style='text-align: center;'><strong>ID</strong></td>
        <td class=tdmainobg style='text-align: center;'><strong>Image</strong></td>
        <td class=tdmainobg style='text-align: center;'><strong>Name</strong></td>
        <td class=tdmainobg style='text-align: center;'><img src=/gfx/goods/50.gif alt='Hüllenlevel Anzahl/min/max'></td>
        <td class=tdmainobg style='text-align: center;'><img src=/gfx/goods/58.gif alt='Schildlevel Anzahl/min/max'></td>
        <td class=tdmainobg style='text-align: center;'><img src=/gfx/goods/62.gif alt='Waffenlevel Anzahl/min/max'></td>
        <td class=tdmainobg style='text-align: center;'><img src=/gfx/goods/87.gif alt='Reaktorlevel min/max'></td>
        <td class=tdmainobg style='text-align: center;'><img src=/gfx/goods/55.gif alt='Computerlevel min/max'></td>
        <td class=tdmainobg style='text-align: center;'><img src=/gfx/goods/75.gif alt='Antriebslevel min/max'></td>
        <td class=tdmainobg style='text-align: center;'><img src=/gfx/goods/83.gif alt='Sensorlevel Anzahl/min/max'></td>
        <td class=tdmainobg style='text-align: center;'><img src=/gfx/goods/79.gif alt='EPS-Gitterlevel Anzahl/min/max'></td>
        <td class=tdmainobg style='text-align: center;'><strong>Fusion</strong></td>
		<td class=tdmainobg style='text-align: center;'><strong>Crew</strong></td>
		<td class=tdmainobg style='text-align: center;'><strong>Ladung</strong></td>
    </tr>";

foreach ($ships as $ship) {
    echo "<tr>
            <td class='tdmainobg' style='text-align: center;'>{$ship['id']}</td>
            <td class='tdmainobg' style='text-align: center;'><img src=/gfx/ships/{$ship['id']}.gif></td>
            <td class='tdmainobg' style='text-align: center;'>{$ship['name']}</td>
            <td class='tdmainobg' style='text-align: center;'>{$ship['huellmod']}/<span style='color: #008000'>{$ship['huellmod_min']}</span>/<span style='color: #00FF00'>{$ship['huellmod_max']}</span></td>
            <td class='tdmainobg' style='text-align: center;'>{$ship['schildmod']}/<span style='color: #008000'>{$ship['schildmod_min']}</span>/<span style='color: #00FF00'>{$ship['schildmod_max']}</span></td>
            <td class='tdmainobg' style='text-align: center;'>{$ship['waffenmod']}/<span style='color: #008000'>{$ship['waffenmod_min']}</span>/<span style='color: #00FF00'>{$ship['waffenmod_max']}</span></td>
            <td class='tdmainobg' style='text-align: center;'><span style='color: #008000'>{$ship['reaktormod_min']}</span>/<span style='color: #00FF00'>{$ship['reaktormod_max']}</span></td>
            <td class='tdmainobg' style='text-align: center;'><span style='color: #008000'>{$ship['computermod_min']}</span>/<span style='color: #00FF00'>{$ship['computermod_max']}</span></td>
            <td class='tdmainobg' style='text-align: center;'><span style='color: #008000'>{$ship['antriebsmod_min']}</span>/<span style='color: #00FF00'>{$ship['antriebsmod_max']}</span></td>
            <td class='tdmainobg' style='text-align: center;'>{$ship['sensormod']}/<span style='color: #008000'>{$ship['sensormod_min']}</span>/<span style='color: #00FF00'>{$ship['sensormod_max']}</span></td>
            <td class='tdmainobg' style='text-align: center;'>{$ship['epsmod']}/<span style='color: #008000'>{$ship['epsmod_min']}</span>/<span style='color: #00FF00'>{$ship['epsmod_max']}</span></td>
            <td class='tdmainobg' style='text-align: center;'>{$ship['fusion']}</td>
			<td class='tdmainobg' style='text-align: center;'>{$ship['crew_min']} / {$ship['crew']}</td>
			<td class='tdmainobg' style='text-align: center;'>{$ship['storage']}</td>
          </tr>";
}

echo "</table>";
