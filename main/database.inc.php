<?php

$entitySelected = isset($_GET['selected']) ? $_GET['selected'] : 'ship-type';

$entities = [
    'colony-type' => 'Colony Types',
    'commodity' => 'Commodities',
    'map-field' => 'Map Fields',
    'ship-type' => 'Ship Types',
];

renderBreadcrumb();
renderSelect($entities, $entitySelected);

switch ($entitySelected) {
    case 'colony-type':
        include_once("class/colony.class.php");
        $colonyRepository = new colony();
        $colonyTypes = $colonyRepository->getColonyTypes();
        $tableData = getColonyTypesData($colonyTypes);
        break;
    case 'commodity':
        include_once("class/colony.class.php");
        $colonyRepository = new colony();
        $commodities = $colonyRepository->goodlist(true);
        $tableData = getCommoditiesData($commodities);
        break;
    case 'map-field':
        include_once("class/map.class.php");
        $mapRepository = new map();
        $fields = $mapRepository->getFields();
        $tableData = getMapFieldsData($fields);
        var_dump(count($tableData['data']));
        $tableData = array_slice($tableData, 0, 50);
        break;
    case 'ship-type':
    default:
        include_once("class/ship.class.php");
        $shipRepository = new ship();
        $shipTypes = $shipRepository->getClasses();
        $tableData = getShipTypesData($shipTypes);
        break;
}

if (isset($tableData)) {
    renderTable($tableData);
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

function renderTable($data)
{
    echo "<table id=\"database\" width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" style=\"background-color: #262323;\">
            <tr>";

    foreach ($data['headers'] as $header) {
        echo "<td class=\"tdmainobg\" style=\"text-align: center;\">";

        if (is_array($header) && isset($header['image']) && $header['image']) {
            echo "<img src=\"{$header['src']}\" alt=\"{$header['alt']}\">";
        } else {
            echo "<strong>$header</strong>";
        }

        echo "</td>";
    }

    echo "</tr>";

    foreach ($data['data'] as $row) {
        echo "<tr>";

        foreach ($row as $cell) {
            echo "<td class=\"tdmainobg\" style=\"text-align: center;\">";

            if (is_array($cell) && isset($cell['image']) && $cell['image']) {
                echo "<img src=\"{$cell['src']}\" alt=\"{$cell['alt']}\">";
            } else {
                echo $cell;
            }

            echo "</td>";
        }

        echo "</tr>";
    }

    echo "</table>";
}

/*
 * Entities data configs
 */

function getColonyTypesData($colonyTypes)
{
    $headers = ['ID', 'Image', 'Name', 'Iridium-Erz', 'Dilithium', 'Kelbonit-Erz', 'Nitrium-Erz', 'Iridium-Erz (T)', 'Kelbonit-Erz (T)', 'Nitrium-Erz (T)', 'Atmosphäre'];

    $data = array_map(function ($type) {
        return [
            $type['id'],
            ['image' => true, 'src' => "/gfx/planets/{$type['id']}.gif", 'alt' => $type['name']],
            $type['name'],
            $type['mine7'],
            $type['mine17'],
            $type['mine33'],
            $type['mine34'],
            $type['mine74'],
            $type['mine75'],
            $type['mine76'],
            $type['atmos'],
        ];
    }, $colonyTypes);

    return [
        'headers' => $headers,
        'data' => $data,
    ];
}

function getCommoditiesData($commodities)
{
    $headers = ['ID', 'Image', 'Name', 'wfaktor', 'hide', 'sort', 'secretimage', 'maxoffer'];

    $data = array_map(function ($commodity) {
        $imagePath = $commodity['secretimage']
            ? "/gfx/secret/{$commodity['secretimage']}.gif"
            : "/gfx/goods/{$commodity['id']}.gif";

        return [
            $commodity['id'],
            ['image' => true, 'src' => $imagePath, 'alt' => $commodity['name']],
            $commodity['name'],
            $commodity['wfaktor'],
            $commodity['hide'],
            $commodity['sort'],
            $commodity['secretimage'],
            $commodity['maxoffer'],
        ];
    }, $commodities);

    return [
        'headers' => $headers,
        'data' => $data,
    ];
}

function getMapFieldsData($mapFields)
{
    $headers = ['ID', 'coords_x', 'coords_y', 'type', 'race', 'wese'];

    $data = array_map(function ($field) {
        return [
            $field['id'],
            $field['coords_x'],
            $field['coords_y'],
            $field['type'],
            $field['race'],
            $field['wese'],
        ];
    }, $mapFields);

    return [
        'headers' => $headers,
        'data' => $data,
    ];
}

function getShipTypesData($shipTypes)
{
    $headers = [
        'ID',
        'Image',
        'Name',
        ['image' => true, 'src' => "/gfx/goods/50.gif", 'alt' => 'Hüllenlevel Anzahl/min/max'],
        ['image' => true, 'src' => "/gfx/goods/58.gif", 'alt' => 'Schildlevel Anzahl/min/max'],
        ['image' => true, 'src' => "/gfx/goods/62.gif", 'alt' => 'Waffenlevel Anzahl/min/max'],
        ['image' => true, 'src' => "/gfx/goods/87.gif", 'alt' => 'Reaktorlevel min/max'],
        ['image' => true, 'src' => "/gfx/goods/55.gif", 'alt' => 'Computerlevel min/max'],
        ['image' => true, 'src' => "/gfx/goods/75.gif", 'alt' => 'Antriebslevel min/max'],
        ['image' => true, 'src' => "/gfx/goods/83.gif", 'alt' => 'Sensorlevel Anzahl/min/max'],
        ['image' => true, 'src' => "/gfx/goods/79.gif", 'alt' => 'EPS-Gitterlevel Anzahl/min/max'],
        'Fusion',
        'Crew',
        'Ladung',
    ];

    $data = array_map(function ($type) {
        return [
            $type['id'],
            ['image' => true, 'src' => "/gfx/ships/{$type['id']}.gif", 'alt' => $type['name']],
            $type['name'],
            "{$type['huellmod']}/<span style='color: #008000'>{$type['huellmod_min']}</span>/<span style='color: #00FF00'>{$type['huellmod_max']}</span>",
            "{$type['schildmod']}/<span style='color: #008000'>{$type['schildmod_min']}</span>/<span style='color: #00FF00'>{$type['schildmod_max']}</span>",
            "{$type['waffenmod']}/<span style='color: #008000'>{$type['waffenmod_min']}</span>/<span style='color: #00FF00'>{$type['waffenmod_max']}</span>",
            "<span style='color: #008000'>{$type['reaktormod_min']}</span>/<span style='color: #00FF00'>{$type['reaktormod_max']}</span>",
            "<span style='color: #008000'>{$type['computermod_min']}</span>/<span style='color: #00FF00'>{$type['computermod_max']}</span>",
            "<span style='color: #008000'>{$type['antriebsmod_min']}</span>/<span style='color: #00FF00'>{$type['antriebsmod_max']}</span>",
            "{$type['sensormod']}/<span style='color: #008000'>{$type['sensormod_min']}</span>/<span style='color: #00FF00'>{$type['sensormod_max']}</span>",
            "{$type['epsmod']}/<span style='color: #008000'>{$type['epsmod_min']}</span>/<span style='color: #00FF00'>{$type['epsmod_max']}</span>",
            $type['fusion'],
            "{$type['crew_min']} / {$type['crew']}",
            $type['storage'],
        ];
    }, $shipTypes);

    return [
        'headers' => $headers,
        'data' => $data,
    ];
}
