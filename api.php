<?php

$entity = isset($_GET['entity']) ? $_GET['entity'] : null;
$page   = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit  = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 20;

$entities = [
    'map-field',
    'module',
];

if (! $entity) {
    response(['error' => 'No entity selected.'], 400);
} elseif (! in_array($entity, $entities)) {
    response(['error' => 'Invalid entity.'], 400);
}

$offset = ($page - 1) * $limit;

include_once('class/Database.php');
$db = new Database();

try {
    // All cases must set $items and $total
    switch ($entity) {
        case 'map-field':
            include_once('class/Repositories/MapFieldRepository.php');
            $mapFieldRepository = new MapFieldRepository($db);
            $items = $mapFieldRepository->index($offset, $limit);
            $total = $mapFieldRepository->total();
            break;
        case 'module':
            include_once('class/Repositories/ModuleRepository.php');
            $moduleRepository = new ModuleRepository($db);
            $items = $moduleRepository->index($offset, $limit);
            $total = $moduleRepository->total();
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