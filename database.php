<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ----------------------------
// DATABASE CONFIG (SITEGROUND)
// ----------------------------
$servername = "localhost";
$username   = "utx299ug72uc9";
$password   = "DATABASEPWORD123";  
$dbname     = "dbkgyginqghrrn";

$status_color    = "green";
$status_message  = "";
$products        = [];

$conn = @new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $status_color   = "red";
    $status_message = "Database connection failed: " . htmlspecialchars($conn->connect_error);
} else {
    $conn->set_charset('utf8mb4');

    $createSql = "
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            image_url VARCHAR(255)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ";
    if (!$conn->query($createSql)) {
        $status_color   = "red";
        $status_message = "Failed creating table: " . htmlspecialchars($conn->error);
    } else {
    
        $count = 0;
        $res = $conn->query("SELECT COUNT(*) AS cnt FROM products");
        if ($res) {
            $row = $res->fetch_assoc();
            $count = (int)$row['cnt'];
            $res->free();
        } else {
            $status_color   = "red";
            $status_message = "Failed counting products: " . htmlspecialchars($conn->error);
        }

        if ($status_color !== "red" && $count === 0) {
            $insert = "
                INSERT INTO products (name, description, price, image_url) VALUES
                ('Cole Haan Oxfords', 'Full-grain oiled leather cap-toe shoe.', 99.99, 'images/colehaanshoes.jpg'),
                ('On Cloud 6 Sneakers', 'Lightweight cushioning sneakers with elastic laces.', 159.99, 'images/on.jpg'),
                ('LLBean Field Shirt', 'Rugged herringbone fabric, garment-dyed for a worn-in look.', 89.00, 'images/llbeanshirt.jpg'),
                ('Michael Kors Stretch Dress Pants', 'Tailored dress pants with stretch fabric for comfort.', 47.50, 'images/michaelkors.jpg'),
                ('Urban Outfitters Hoodie', 'Casual cotton hoodie, great for daily wear.', 39.00, 'images/urban.jpg'),
                ('Calvin Klein Overcoat', 'Notch-lapel long overcoat, tailored look.', 118.50, 'images/calvinklein.jpg')
            ";
            if ($conn->query($insert)) {
                $count = 6;
                $status_message = "✓ Database connection successful. Sample data inserted successfully! Found {$count} products.";
            } else {
                $status_color   = "red";
                $status_message = "Connected, but failed inserting sample data: " . htmlspecialchars($conn->error);
            }
        } elseif ($status_color !== "red") {
            $status_message = "✓ Database connection successful. Found {$count} products.";
        }

        // ----------------------------
        // FETCH PRODUCTS
        // ----------------------------
        if ($status_color !== "red") {
            $stmt = $conn->query("SELECT * FROM products ORDER BY id");
            if ($stmt) {
                while ($row = $stmt->fetch_assoc()) {
                    $products[] = $row;
                }
                $stmt->free();
            } else {
                $status_color   = "red";
                $status_message = "SQL Error while fetching products: " . htmlspecialchars($conn->error);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Database Product Viewer</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background-color: #f4f7f9;
            color: #333;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #3b82f6;
            margin-bottom: 30px;
        }
        .status-message {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .product-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 300px;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }
        .product-image {
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e5e7eb;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .placeholder {
            font-size: 40px;
            font-weight: bold;
            color: #6b7280;
        }
        .product-info {
            padding: 15px;
            flex-grow: 1;
        }
        .product-name {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #1f2937;
        }
        .product-description {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 10px;
        }
        .product-price {
            font-size: 1.4rem;
            font-weight: 800;
            color: #10b981;
            text-align: right;
        }
    </style>
</head>
<body>

<h1>Men's Fashion Collection (Live Data)</h1>

<div class="status-message"
     style="color: <?= $status_color === 'green' ? '#065f46' : '#991b1b' ?>;
            border: 1px solid <?= $status_color === 'green' ? '#10b981' : '#ef4444' ?>;
            background-color: <?= $status_color === 'green' ? '#d1fae5' : '#fee2e2' ?>;">
    <?= htmlspecialchars($status_message ?: 'Status unavailable.') ?>
</div>

<div class="product-container">
<?php if (!empty($products)): ?>
    <?php foreach ($products as $product): ?>
        <div class="product-card">
            <div class="product-image">
                <?php if (!empty($product['image_url'])): ?>
                    <img src="<?= htmlspecialchars($product['image_url']) ?>"
                         alt="<?= htmlspecialchars($product['name']) ?>"
                         onerror="this.onerror=null;this.src='https://placehold.co/300x150/CCCCCC/666666?text=NO+IMG';">
                <?php else: ?>
                    <div class="placeholder">
                        <?= htmlspecialchars(strtoupper(substr($product['name'], 0, 1))) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="product-info">
                <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                <div class="product-description"><?= htmlspecialchars($product['description']) ?></div>
                <div class="product-price">$<?= number_format((float)$product['price'], 2) ?></div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p style="color: #666; text-align: center; width: 100%;">No products found. Please check the database connection details above.</p>
<?php endif; ?>
</div>

</body>
</html>