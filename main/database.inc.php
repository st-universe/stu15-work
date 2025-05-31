<?php

echo "<table width=100% cellspacing=1 cellpadding=1 style='background-color: #262323'>
    <tr>
        <td class=tdmain>/ <a href=page=main>STU</a> / <strong>Database</strong></td>
    </tr>
</table>
<br>";

include_once("class/ship.class.php");

$shipRepository = new ship();

$ships = $shipRepository->getclasses();

echo "<table width=100% cellspacing=1 cellpadding=1 style='background-color: #262323'>
        <tr>
            <td class=tdmainobg style='text-align: center;'><strong>ID</strong></td>
            <td class=tdmainobg style='text-align: center;'><strong>Image</strong></td>
            <td class=tdmainobg style='text-align: center;'><strong>Name</strong></td>
            <td class=tdmainobg style='text-align: center;'><img src=/gfx/goods/50.gif alt='Hüllenlevel Anzahl/Min/Max'></td>
        </tr>";

foreach ($ships as $ship) {
    echo "<tr>
            <td class=tdmainobg style='text-align: center;'>{$ship['id']}</td>
            <td class=tdmainobg style='text-align: center;'><img src=/gfx/ships/{$ship['id']}.gif></td>
            <td class=tdmainobg style='text-align: center;'>{$ship['name']}</td>
            <td class=tdmainobg style='text-align: center;'>{$ship['huellmod']}/<span style='color: #008000'>{$ship['huellmod_min']}</span>/<span style='color: #00FF00'>{$ship['huellmod_max']}</span></td>
          </tr>";
}

echo "</table>";
