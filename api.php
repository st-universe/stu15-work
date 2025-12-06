<?php

$entity = isset($_GET['entity']) ? $_GET['entity'] : null;
$page   = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit  = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 50;

$entities = [
    'commodity' => 'Commodity',
    'map-field' => 'MapField',
    'map-field-special' => 'MapFieldSpecial',
    'module' => 'Module',
    'ship' => 'Ship',
    'torpedo-type' => 'TorpedoType',
    'user' => 'User',
];

if (! $entity) {
    response(['error' => 'No entity selected.'], 400);
} elseif (! array_key_exists($entity, $entities)) {
    response(['error' => 'Invalid entity.'], 400);
}

$offset = ($page - 1) * $limit;

include_once('class/Database.php');
$database = new Database();

try {
    // All cases must set $items and $total
    switch ($entity) {
        case 'commodity':
        case 'map-field':
        case 'map-field-special':
        case 'module':
        case 'ship':
        case 'torpedo-type':
        case 'user':
            list($items, $total) = data($entity, $entities, $database, $offset, $limit);
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