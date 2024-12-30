<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['productName'] ?? '';
    $quantity = $_POST['quantityInStock'] ?? '';
    $price = $_POST['pricePerItem'] ?? '';

    $response = [];
    if ($productName && $quantity && $price) {
        $uniqueId = time() . substr(microtime(), 2, 3);
        $currentDateTime = date("Y-m-d H:i:s");
        $totalValue = $quantity * $price;

        $newEntry = [
            'id' => $uniqueId,
            'productName' => $productName,
            'quantity' => $quantity,
            'price' => $price,
            'dateTime' => $currentDateTime,
            'totalValue' => $totalValue,
        ];

        $filePath = 'storage.json';

        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $data = json_decode($jsonData, true) ?? [];
        } else {
            $data = [];
        }

        $data[] = $newEntry;
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        $file = fopen($filePath, 'wb');
        if ($file === false) {
            echo 'Failed to open the file for writing';
            exit;
        }

        if (fwrite($file, $jsonData) === false) {
            echo 'Failed to write to file';
        } else {
            $response['success'] = true;
            $response['message'] = 'Data saved successfully!';
        }

        fclose($file);
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid input data';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $filePath = 'storage.json';
    if (file_exists($filePath)) {
        $jsonData = file_get_contents($filePath);
        $data = json_decode($jsonData, true) ?? [];

        usort($data, static function ($a, $b) {
            return strtotime($b['dateTime']) - strtotime($a['dateTime']);
        });

        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    $filePath = 'storage.json';
    if (file_exists($filePath)) {
        $jsonData = file_get_contents($filePath);
        $data = json_decode($jsonData, true) ?? [];

        $data = array_filter($data, static function($entry) use ($id) {
            return $entry['id'] !== $id;
        });

        $data = array_values($data);
        $file = fopen($filePath, 'wb');
        if ($file === false) {
            echo json_encode(['success' => false, 'message' => 'Failed to open the file for writing']);
            exit;
        }

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        if (fwrite($file, $jsonData) === false) {
            echo json_encode(['success' => false, 'message' => 'Failed to write to file']);
        } else {
            echo json_encode(['success' => true]);
        }

        fclose($file);
    } else {
        echo json_encode(['success' => false, 'message' => 'File not found']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $inputData = file_get_contents("php://input");
    $data = json_decode($inputData, true);

    $productId = $data['id'] ?? '';
    $productName = $data['productName'] ?? '';
    $quantity = $data['quantity'] ?? '';
    $price = $data['price'] ?? '';

    if ($productId && $productName && $quantity && $price) {
        $totalValue = $quantity * $price;
        $filePath = 'storage.json';

        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $data = json_decode($jsonData, true) ?? [];

            $productFound = false;
            foreach ($data as &$product) {
                if ($product['id'] === $productId) {
                    $product['productName'] = $productName;
                    $product['quantity'] = $quantity;
                    $product['price'] = $price;
                    $product['totalValue'] = $totalValue; // Set calculated total value
                    $productFound = true;
                    break;
                }
            }

            if ($productFound) {
                $jsonData = json_encode($data, JSON_PRETTY_PRINT);
                $file = fopen($filePath, 'wb');
                if ($file === false) {
                    echo json_encode(['success' => false, 'message' => 'Failed to open the file for writing']);
                    exit;
                }

                if (fwrite($file, $jsonData) === false) {
                    echo json_encode(['success' => false, 'message' => 'Failed to write to file']);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
                }

                fclose($file);
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    }

    exit;
}
