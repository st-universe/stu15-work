<?php

$entitySelected = isset($_GET['selected']) ? $_GET['selected'] : 'ship-type';

$entities = [
    'building-type' => 'Building Types',
    'colony-type' => 'Colony Types',
    'commodity' => 'Commodities',
    'map-field' => 'Map Fields',
    'ship-type' => 'Ship Types',
];

renderBreadcrumb();
renderSelect($entities, $entitySelected);

try {
    switch ($entitySelected) {
        case 'building-type':
            include_once("class/colony.class.php");
            $result = (new colony())->getBuildings();
            for ($types = []; $row = mysql_fetch_assoc($result); $types[] = $row);
            renderTable('building-type', getBuildingTypeFields(), $types);
            break;
        case 'colony-type':
            include_once("class/colony.class.php");
            renderTable('colony-type', getColonyTypeFields(), (new colony())->getColonyTypes());
            break;
        case 'commodity':
            include_once("class/colony.class.php");
            renderTable('commodity', getCommodityFields(), (new colony())->goodlist(true));
            break;
        case 'map-field':
            include_once("class/map.class.php");
            $mapRepository = new map();
            $fieldCount = $mapRepository->getFieldsCount();
            echo "<span style='color: lightgrey'>Total fields: $fieldCount</span>";
            renderTable('map-field', getMapFieldFields(), $mapRepository->getFields());
            break;
        case 'ship-type':
        default:
            include_once("class/ship.class.php");
            renderTable('ship-type', getShipTypeFields(), (new ship())->getClasses());
            break;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

function renderBreadcrumb()
{
    echo "<table cellspacing='1' cellpadding='1' style='width: 100%; background-color: #262323'>
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

function renderTable($entity, $fields, $data)
{
    echo "<table id='database' data-entity='$entity' cellspacing='1' cellpadding='1' style='width: 100%; background-color: #262323;'>
            <thead><tr>";

    foreach ($fields as $header) {
        echo "<td class='tdmainobg' data-field='{$header['field']}' style='text-align: center;'>";

        if (array_key_exists('image', $header)) {
            if (array_key_exists('text', $header)) {
                echo "<strong>{$header['text']}</strong>";
            } else {
                echo "<img src='{$header['src']}' alt='{$header['alt']}'>";
            }
        } elseif (array_key_exists('headerIsImage', $header)) {
            echo "<img src='{$header['src']}' alt='{$header['alt']}'>";
        } else {
            echo "<strong>{$header['text']}</strong>";
        }

        echo "</td>";
    }

    echo "</tr></thead><tbody>";

    foreach ($data as $row) {
        echo "<tr>";

        foreach ($fields as $header) {
            echo "<td class='tdmainobg' style='text-align: center;'>";

            if (array_key_exists('image', $header)) {
                echo "<img src='{$header['src']($row)}' alt='{$header['alt']($row)}'>";
            } elseif (array_key_exists('color', $header)) {
                echo "<span style='color: {$header['color']}'>{$row[$header['field']]}</span>";
            } else {
                echo $row[$header['field']];
            }

            echo "</td>";
        }

        echo "</tr>";
    }

    echo "</tbody></table>";
}

/*
 * Entity field configs
 */

function getBuildingTypeFields()
{
    return [
        ['field' => 'id', 'text' => 'ID'],
        [
            'field' => 'image',
            'text' => 'Image',
            'image' => true,
            'src' => function ($type) { return "/gfx/buildings/{$type['id']}_{$type['type']}.gif"; },
            'alt' => function ($type) { return $type['name']; },
        ],
        ['field' => 'name', 'text' => 'Name'],
        ['field' => 'lager', 'text' => 'Storage'],
        ['field' => 'eps_cost', 'text' => 'EPS Cost'],
        ['field' => 'eps', 'text' => 'EPS'],
        ['field' => 'eps_min', 'text' => 'EPS Min'],
        ['field' => 'eps_pro', 'text' => 'EPS Pro'],
        ['field' => 'bev_pro', 'text' => 'Bev Pro'],
        ['field' => 'bev_use', 'text' => 'Bev Use'],
        ['field' => 'level', 'text' => 'Level'],
        ['field' => 'integrity', 'text' => 'Integrity'],
        ['field' => 'research_id', 'text' => 'Research ID'],
        ['field' => 'points', 'text' => 'Points'],
        ['field' => 'view', 'text' => 'View'],
        ['field' => 'schilde', 'text' => 'Shields'],
        ['field' => 'buildtime', 'text' => 'Construction Time'],
        ['field' => 'blimit', 'text' => 'Construction Limit'],
        ['field' => 'secretimage', 'text' => 'Secret Image'],
    ];
}

function getColonyTypeFields()
{
    return [
        ['field' => 'id', 'text' => 'ID'],
        [
            'field' => 'image',
            'text' => 'Image',
            'image' => true,
            'src' => function ($type) { return "/gfx/planets/{$type['id']}.gif"; },
            'alt' => function ($type) { return $type['name']; },
        ],
        ['field' => 'name', 'text' => 'Name'],
        ['field' => 'mine7', 'text' => 'Iridium-Erz'],
        ['field' => 'mine17', 'text' => 'Dilithium'],
        ['field' => 'mine33', 'text' => 'Kelbonit-Erz'],
        ['field' => 'mine34', 'text' => 'Nitrium-Erz'],
        ['field' => 'mine74', 'text' => 'Iridium-Erz (T)'],
        ['field' => 'mine75', 'text' => 'Kelbonit-Erz (T)'],
        ['field' => 'mine76', 'text' => 'Nitrium-Erz (T)'],
        ['field' => 'atmos', 'text' => 'Atmosphere'],
    ];
}

function getCommodityFields()
{
    return [
        ['field' => 'id', 'text' => 'ID'],
        [
            'field' => 'image',
            'text' => 'Image',
            'image' => true,
            'src' => function ($type) {
                return $type['secretimage']
                    ? "/gfx/secret/{$type['secretimage']}.gif"
                    : "/gfx/goods/{$type['id']}.gif";
            },
            'alt' => function ($type) { return $type['name']; },
        ],
        ['field' => 'name', 'text' => 'Name'],
        ['field' => 'wfaktor', 'text' => 'wfaktor'],
        ['field' => 'hide', 'text' => 'hide'],
        ['field' => 'sort', 'text' => 'sort'],
        ['field' => 'secretimage', 'text' => 'Secret Image'],
        ['field' => 'maxoffer', 'text' => 'Max Offer'],
    ];
}

function getMapFieldFields()
{
    return [
        ['field' => 'id', 'text' => 'ID'],
        ['field' => 'coords_x', 'text' => 'X'],
        ['field' => 'coords_y', 'text' => 'Y'],
        ['field' => 'type', 'text' => 'Type'],
        ['field' => 'race', 'text' => 'race'],
        ['field' => 'wese', 'text' => 'wese'],
    ];
}

function getShipTypeFields()
{
    return [
        ['field' => 'id', 'text' => 'ID'],
        [
            'field' => 'image',
            'text' => 'Image',
            'image' => true,
            'src' => function ($type) { return "/gfx/ships/{$type['id']}.gif"; },
            'alt' => function ($type) { return $type['name']; },
        ],
        ['field' => 'name', 'text' => 'Name'],
        [
            'field' => 'huellmod',
            'headerIsImage' => true,
            'src' => "/gfx/goods/50.gif",
            'alt' => 'Hüllenlevel Anzahl',
        ],
        [
            'field' => 'huellmod_min',
            'color' => '#008000',
            'headerIsImage' => true,
            'src' => "/gfx/goods/50.gif",
            'alt' => 'Hüllenlevel min',
        ],
        [
            'field' => 'huellmod_max',
            'color' => '#00FF00',
            'headerIsImage' => true,
            'src' => "/gfx/goods/50.gif",
            'alt' => 'Hüllenlevel max',
        ],
        [
            'field' => 'schildmod',
            'headerIsImage' => true,
            'src' => "/gfx/goods/58.gif",
            'alt' => 'Schildlevel Anzahl',
        ],
        [
            'field' => 'schildmod_min',
            'color' => '#008000',
            'headerIsImage' => true,
            'src' => "/gfx/goods/58.gif",
            'alt' => 'Schildlevel min',
        ],
        [
            'field' => 'schildmod_max',
            'color' => '#00FF00',
            'headerIsImage' => true,
            'src' => "/gfx/goods/58.gif",
            'alt' => 'Schildlevel max',
        ],
        [
            'field' => 'waffenmod',
            'headerIsImage' => true,
            'src' => "/gfx/goods/62.gif",
            'alt' => 'Waffenlevel Anzahl',
        ],
        [
            'field' => 'waffenmod_min',
            'color' => '#008000',
            'headerIsImage' => true,
            'src' => "/gfx/goods/62.gif",
            'alt' => 'Waffenlevel min',
        ],
        [
            'field' => 'waffenmod_max',
            'color' => '#00FF00',
            'headerIsImage' => true,
            'src' => "/gfx/goods/62.gif",
            'alt' => 'Waffenlevel max',
        ],
        [
            'field' => 'reaktormod_min',
            'color' => '#008000',
            'headerIsImage' => true,
            'src' => "/gfx/goods/87.gif",
            'alt' => 'Reaktorlevel min',
        ],
        [
            'field' => 'reaktormod_max',
            'color' => '#00FF00',
            'headerIsImage' => true,
            'src' => "/gfx/goods/87.gif",
            'alt' => 'Reaktorlevel max',
        ],
        [
            'field' => 'computermod_min',
            'color' => '#008000',
            'headerIsImage' => true,
            'src' => "/gfx/goods/55.gif",
            'alt' => 'Computerlevel min',
        ],
        [
            'field' => 'computermod_max',
            'color' => '#00FF00',
            'headerIsImage' => true,
            'src' => "/gfx/goods/55.gif",
            'alt' => 'Computerlevel max',
        ],
        [
            'field' => 'antriebsmod_min',
            'color' => '#008000',
            'headerIsImage' => true,
            'src' => "/gfx/goods/75.gif",
            'alt' => 'Antriebslevel min',
        ],
        [
            'field' => 'antriebsmod_max',
            'color' => '#00FF00',
            'headerIsImage' => true,
            'src' => "/gfx/goods/75.gif",
            'alt' => 'Antriebslevel max',
        ],
        [
            'field' => 'sensormod',
            'headerIsImage' => true,
            'src' => "/gfx/goods/83.gif",
            'alt' => 'Sensorlevel Anzahl',
        ],
        [
            'field' => 'sensormod_min',
            'color' => '#008000',
            'headerIsImage' => true,
            'src' => "/gfx/goods/83.gif",
            'alt' => 'Sensorlevel min',
        ],
        [
            'field' => 'sensormod_max',
            'color' => '#00FF00',
            'headerIsImage' => true,
            'src' => "/gfx/goods/83.gif",
            'alt' => 'Sensorlevel max',
        ],
        [
            'field' => 'epsmod',
            'headerIsImage' => true,
            'src' => "/gfx/goods/79.gif",
            'alt' => 'EPS-Gitterlevel Anzahl',
        ],
        [
            'field' => 'epsmod_min',
            'color' => '#008000',
            'headerIsImage' => true,
            'src' => "/gfx/goods/79.gif",
            'alt' => 'EPS-Gitterlevel min',
        ],
        [
            'field' => 'epsmod_max',
            'color' => '#00FF00',
            'headerIsImage' => true,
            'src' => "/gfx/goods/79.gif",
            'alt' => 'EPS-Gitterlevel max',
        ],
        ['field' => 'fusion', 'text' => 'Fusion'],
        ['field' => 'crew_min', 'text' => 'Crew Min'],
        ['field' => 'crew', 'text' => 'Crew'],
        ['field' => 'storage', 'text' => 'Storage'],
        ['field' => 'max_batt', 'text' => 'Battery max'],
        ['field' => 'bussard', 'text' => 'Bussard'],
        ['field' => 'erz', 'text' => 'Ore'],
        ['field' => 'cloak', 'text' => 'Cloak'],
        ['field' => 'slots', 'text' => 'Slots'],
        ['field' => 'replikator', 'text' => 'Replicator'],
        ['field' => 'torps', 'text' => 'Torpedos'],
        ['field' => 'torp_evade', 'text' => 'Torpedo Evade'],
        ['field' => 'sorta', 'text' => 'Sort A'],
        ['field' => 'sortb', 'text' => 'Sort B'],
        ['field' => 'tachyon', 'text' => 'Tachyon'],
        ['field' => 'view', 'text' => 'View'],
        ['field' => 'ewerft', 'text' => 'EWerft'],
        ['field' => 'points', 'text' => 'Points'],
        ['field' => 'buildtime', 'text' => 'Construction time'],
        ['field' => 'trumfield', 'text' => 'Debris'],
        ['field' => 'eps_cost', 'text' => 'EPS Cost'],
        ['field' => 'probe', 'text' => 'Probe'],
        ['field' => 'probe_stor', 'text' => 'Probe Storage'],
        ['field' => 'size', 'text' => 'Size'],
        ['field' => 'secretimage', 'text' => 'Secret Image'],
    ];
}
