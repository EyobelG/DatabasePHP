<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username   = "utx299ug72uc9";
$password   = "DATABASEPWORD123";
$dbname     = "dbkgyginqghrrn";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $result = $conn->query("SHOW TABLES");
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}


// Run query
$sql = "SELECT name, description, price, image_url FROM products ORDER BY name ASC";
$result = $conn->query($sql);

// If the query itself fails
if ($result === false) {
    http_response_code(500);
    $conn->close();
    exit("Database query failed: " . htmlspecialchars($conn->error));
}

// Fetch rows into an array
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
$result->free();

// Helper to format price
function format_price($p) {
    return '$' . number_format((float)$p, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Product Catalog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: beige;
        }
        h1 {
            text-align: center;
            color: #500082;
        }
        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 900px;
            margin: 0 auto;
        }
        .product-card {
            width: calc(50% - 10px);
            box-sizing: border-box;
            border: 1px solid #ccc;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .product-card img {
            display: block;
            width: 100%;
            height: 220px;        
            object-fit: cover;      
            border-radius: 6px;
            margin-bottom: 12px;   
            background: #f4f4f4;
        }
        .product-card h2 {
            margin: 0;
            margin-bottom: 5px;
            color: blueviolet;
            font-size: 1.2em;
        }
        .product-card .price {
            font-weight: bold;
            color: #ffbe0a;
            float: right;
        }
        .product-card .description {
            margin-top: 10px;
            color: #555;
            font-style: italic;
            clear: both;
        }
        @media (max-width: 600px) {
            .product-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<h1>Product Catalog</h1>

<div class="product-list">
    <?php if (count($products) === 0): ?>
        <p>No products found.</p>
    <?php else: ?>
        <?php foreach ($products as $p): ?>
            <div class="product-card">
            <?php

                $name  = htmlspecialchars($p['name'] ?? '', ENT_QUOTES, 'UTF-8');
                $desc  = htmlspecialchars($p['description'] ?? '', ENT_QUOTES, 'UTF-8');
                $price = format_price($p['price'] ?? 0);

                // Image handling: prepend 'images/' if not already present
                $img = trim((string)($p['image_url'] ?? ''));
                $imgPath = $img !== '' ? 'images/' . basename($img) : 'images/placeholder.png';
            ?>
            <img src="<?= htmlspecialchars($imgPath, ENT_QUOTES, 'UTF-8'); ?>"
                alt="<?= $name; ?>" loading="lazy" />


                <h2><?= strtoupper($name); ?>
                    <span class="price"><?= $price; ?></span>
                </h2>
                <p class="description"><?= $desc; ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
<?php
// Close connection at the end of the response
$conn->close();