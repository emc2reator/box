<?php
header('Content-Type: application/json');

// Подключаемся к базе данных
include 'db.php';

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'getlists':
        getLists($pdo);
        break;

    case 'newitem':
        $name = $_POST['name'] ?? '';
        $item = $_POST['item'] ?? '';
        newItem($pdo, $name, $item);
        break;

    case 'update':
        $name = $_POST['name'] ?? '';
        updateList($pdo, $name);
        break;

    case 'delete':
        $name = $_POST['name'] ?? '';
        deleteList($pdo, $name);
        break;

    case 'delitem':
        $name = $_POST['name'] ?? '';
        $item = $_POST['item'] ?? '';
        deleteItem($pdo, $name, $item);
        break;

    case 'togglebuy':
        $name = $_POST['name'] ?? '';
        $item = $_POST['item'] ?? '';
        $buy = $_POST['buy'] ?? '';
        toggleBuy($pdo, $name, $item, $buy);
        break;

    case 'edititem':
        $name = $_POST['name'] ?? '';
        $oldItem = $_POST['oldItem'] ?? '';
        $newItem = $_POST['newItem'] ?? '';
        editItem($pdo, $name, $oldItem, $newItem);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}

function getLists($pdo) {
    $stmt = $pdo->query("SELECT DISTINCT name FROM lists ORDER by name DESC");
    $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($lists);
}

function newItem($pdo, $name, $item) {
    if ($name && $item) {
        $stmt = $pdo->prepare("INSERT INTO lists (name, items, buy) VALUES (?, ?, 0)");
        $stmt->execute([$name, $item]);
        echo json_encode(['success' => 'Item added successfully']);
    } else {
        echo json_encode(['error' => 'Name and item are required']);
    }
}

function updateList($pdo, $name) {
    if ($name) {
        $stmt = $pdo->prepare("SELECT * FROM lists WHERE name = ? ORDER BY `lists`.`buy` ASC, `lists`.`items` ASC");
        $stmt->execute([$name]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($items);
    } else {
        echo json_encode(['error' => 'Name is required']);
    }
}

function deleteList($pdo, $name) {
    if ($name) {
        $stmt = $pdo->prepare("DELETE FROM lists WHERE name = ?");
        $stmt->execute([$name]);
        echo json_encode(['success' => 'List deleted successfully']);
    } else {
        echo json_encode(['error' => 'Name is required']);
    }
}

function deleteItem($pdo, $name, $item) {
    if ($name && $item) {
        $stmt = $pdo->prepare("DELETE FROM lists WHERE name = ? AND items = ?");
        $stmt->execute([$name, $item]);
        echo json_encode(['success' => 'Item deleted successfully']);
    } else {
        echo json_encode(['error' => 'Name and item are required']);
    }
}

function toggleBuy($pdo, $name, $item, $buy) {
    if ($name && $item) {
        $stmt = $pdo->prepare("UPDATE lists SET buy = ? WHERE name = ? AND items = ?");
        $stmt->execute([$buy, $name, $item]);
        echo json_encode(['success' => 'Item updated successfully']);
    } else {
        echo json_encode(['error' => 'Name and item are required']);
    }
}

function editItem($pdo, $name, $oldItem, $newItem) {
    if ($name && $oldItem && $newItem) {
        $stmt = $pdo->prepare("UPDATE lists SET items = ? WHERE name = ? AND items = ?");
        $stmt->execute([$newItem, $name, $oldItem]);
        echo json_encode(['success' => 'Item updated successfully']);
    } else {
        echo json_encode(['error' => 'Name, old item, and new item are required']);
    }
}
?>
