<?php

$entity = isset($_GET['entity']) ? $_GET['entity'] : null;

$entities = [
    'colony' => 'Colony',
    'colony-type' => 'ColonyType',
    'commodity' => 'Commodity',
    'map-field' => 'MapField',
    'map-field-special' => 'MapFieldSpecial',
    'module' => 'Module',
    'ship' => 'Ship',
    'torpedo-type' => 'TorpedoType',
    'trading-station' => null,
    'user' => 'User',
];

if (! $entity) {
    response(['error' => 'No entity selected.'], 400);
} elseif (! array_key_exists($entity, $entities)) {
    response(['error' => 'Invalid entity.'], 400);
}

include_once('class/Database.php');
$database = new Database();

$showColumns = isset($_GET['columns']);

if ($showColumns) {
    try {
        $repository = $entities[$entity].'Repository';
        include_once ('class/Repositories/'.$repository.'.php');
        $repository = new $repository($database);

        response(['columns' => $repository->columns()]);
    } catch (Exception $e) {
        response(['error' => $e->getMessage()], 400);
    }
}

$page   = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit  = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 50;


$offset = ($page - 1) * $limit;

try {
    // All cases must set $items and $total
    switch ($entity) {
        case 'colony':
        case 'colony-type':
        case 'commodity':
        case 'map-field':
        case 'map-field-special':
        case 'module':
        case 'ship':
        case 'torpedo-type':
        case 'user':
            list($items, $total) = data($entity, $entities, $database, $offset, $limit);
            break;
        case 'trading-station':
            include_once ('class/Repositories/ShipRepository.php');
            $shipRepository = new ShipRepository($database);
            $items = $shipRepository->tradingStations($offset, $limit);
            $total = count($items);
            break;
    }

    response([
        'data' => $items,
        'meta' => [
            'current_page' => $page,
            'from' => $offset + 1,
            'last_page' => ceil($total / $limit),
            'per_page' => $limit,
            'to' => min($offset + $limit, $total),
            'total' => $total,
        ],
    ]);

} catch (Exception $e) {
    response(['error' => $e->getMessage()], 400);
}

function response($content, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($content);
    die();
}

function data($entity, $entities, $database, $offset, $limit)
{
    $repository = $entities[$entity].'Repository';
    include_once ('class/Repositories/'.$repository.'.php');
    $repository = new $repository($database);

    return [
        $repository->index($offset, $limit),
        $repository->total(),
    ];
}
