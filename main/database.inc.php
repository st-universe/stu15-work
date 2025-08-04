<?php

$entitySelected = isset($_GET['selected']) ? $_GET['selected'] : 'ship-type';

$entities = [
    'colony-type' => 'Colony Types',
    'commodity' => 'Commodities',
    'ship-type' => 'Ship Types',
];

renderBreadcrumb();
renderSelect($entities, $entitySelected);

switch ($entitySelected) {
    case 'colony-type':
        include_once("class/colony.class.php");
        $colonyRepository = new colony();
        $colonyTypes = $colonyRepository->getColonyTypes();
        renderColonyTypes($colonyTypes);
        break;
    case 'commodity':
        include_once("class/colony.class.php");
        $colonyRepository = new colony();
        $commodities = $colonyRepository->goodlist(true);
        $data = getCommoditiesData($commodities);
        renderTable($data);
        break;
    case 'ship-type':
    default:
        include_once("class/ship.class.php");
        $shipRepository = new ship();
        $shipTypes = $shipRepository->getClasses();
        renderShipTypes($shipTypes);
        break;
}

function renderBreadcrumb()
{
    echo "<table width=100% cellspacing=1 cellpadding=1 style='background-color: #262323'>
            <tr>
                <td class='tdmain'>/ <a href='page=main'>STU</a> / <strong>Database</strong></td>
            </tr>
        </table>
        <br>";
}

function renderSelect($entities, $entitySelected)
{
    echo "<form action='/' method='get'>
            <input type='hidden' name='page' value='database' />
             <select name='selected'>";

    foreach ($entities as $key => $value) {
        $selected = $key === $entitySelected ? 'selected' : '';

        echo "<option value='$key' $selected>$value</option>";
    }

    echo "</select>
          <input type='submit' value='Refresh' />
        </form>
        <br>";
}

/*
 * Render entities
 */

function renderColonyTypes($colonyTypes)
{
    echo "<table width=100% cellspacing=1 cellpadding=1 style='background-color: #262323'>
            <tr>
                <td class='tdmainobg' style='text-align: center;'><strong>ID</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Image</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Name</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Iridium-Erz</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Dilithium</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Kelbonit-Erz</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Nitrium-Erz</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Iridium-Erz (T)</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Kelbonit-Erz (T)</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Nitrium-Erz (T)</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Atmosphäre</strong></td>
            </tr>";

    foreach ($colonyTypes as $type) {
        echo "<tr>
                <td class='tdmainobg' style='text-align: center;'>{$type['id']}</td>
                <td class='tdmainobg' style='text-align: center;'><img src=/gfx/planets/{$type['id']}.gif alt={$type['name']}></td>
                <td class='tdmainobg' style='text-align: center;'>{$type['name']}</td>
                <td class='tdmainobg' style='text-align: center;'>{$type['mine7']}</td>
                <td class='tdmainobg' style='text-align: center;'>{$type['mine17']}</td>
                <td class='tdmainobg' style='text-align: center;'>{$type['mine33']}</td>
                <td class='tdmainobg' style='text-align: center;'>{$type['mine34']}</td>
                <td class='tdmainobg' style='text-align: center;'>{$type['mine74']}</td>
                <td class='tdmainobg' style='text-align: center;'>{$type['mine75']}</td>
                <td class='tdmainobg' style='text-align: center;'>{$type['mine76']}</td>
                <td class='tdmainobg' style='text-align: center;'>{$type['atmos']}</td>
              </tr>";
    }

    echo "</table>";
}

function getCommoditiesData($commodities)
{
    $headers = ['ID', 'Image', 'Name', 'wfaktor', 'hide', 'sort', 'secretimage', 'maxoffer'];

    $data = array_map(function ($commodity) {
        $imagePath = $commodity['secretimage']
            ? "/gfx/secret/{$commodity['secretimage']}.gif"
            : "/gfx/goods/{$commodity['id']}.gif";

        return [
            ['value' => $commodity['id']],
            ['value' => $imagePath, 'image' => true],
            ['value' => $commodity['name']],
            ['value' => $commodity['wfaktor']],
            ['value' => $commodity['hide']],
            ['value' => $commodity['sort']],
            ['value' => $commodity['secretimage']],
            ['value' => $commodity['maxoffer']],
        ];
    }, $commodities);

    return [
        'headers' => $headers,
        'data' => $data,
    ];
}

function renderShipTypes($shipTypes)
{
    echo "<table width=100% cellspacing=1 cellpadding=1 style='background-color: #262323'>
            <tr>
                <td class='tdmainobg' style='text-align: center;'><strong>ID</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Image</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Name</strong></td>
                <td class='tdmainobg' style='text-align: center;'><img src='/gfx/goods/50.gif' alt='Hüllenlevel Anzahl/min/max'></td>
                <td class='tdmainobg' style='text-align: center;'><img src='/gfx/goods/58.gif' alt='Schildlevel Anzahl/min/max'></td>
                <td class='tdmainobg' style='text-align: center;'><img src='/gfx/goods/62.gif' alt='Waffenlevel Anzahl/min/max'></td>
                <td class='tdmainobg' style='text-align: center;'><img src='/gfx/goods/87.gif' alt='Reaktorlevel min/max'></td>
                <td class='tdmainobg' style='text-align: center;'><img src='/gfx/goods/55.gif' alt='Computerlevel min/max'></td>
                <td class='tdmainobg' style='text-align: center;'><img src='/gfx/goods/75.gif' alt='Antriebslevel min/max'></td>
                <td class='tdmainobg' style='text-align: center;'><img src='/gfx/goods/83.gif' alt='Sensorlevel Anzahl/min/max'></td>
                <td class='tdmainobg' style='text-align: center;'><img src='/gfx/goods/79.gif' alt='EPS-Gitterlevel Anzahl/min/max'></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Fusion</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Crew</strong></td>
                <td class='tdmainobg' style='text-align: center;'><strong>Ladung</strong></td>
            </tr>";

    foreach ($shipTypes as $ship) {
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
}

function renderTable($data)
{
    echo "<table id='database' width=100% cellspacing=1 cellpadding=1 style='background-color: #262323'>
            <tr>";

    foreach ($data['headers'] as $header) {
        echo "<td class='tdmainobg' style='text-align: center;'><strong>$header</strong></td>";
    }

    echo "</tr>";

    foreach ($data['data'] as $row) {
        echo "<tr>";

        foreach ($row as $cell) {
            echo "<td class='tdmainobg' style='text-align: center;'>";

            if (isset($cell['image']) && $cell['image']) {
                echo "<img src={$cell['value']}>";
            } else {
                echo $cell['value'];
            }

            echo "</td>";
        }

        echo "</tr>";
    }

    echo "</table>";
}