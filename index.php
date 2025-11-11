<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Database</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Men's Fashion Collection</h1>

<?php
error_reporting(E_ALL);

// ----------------------------
// DATABASE CONFIG (SITEGROUND)
// ----------------------------
$host = 'localhost';
$dbname = 'utx299ug72uc9_dbkgyginqghrrn';
$username = 'utx299ug72uc9_dbuser';
$password = '@Ilovetufts1';

try {
    // SiteGround requires charset explicitly
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => true
        ]
    );

    // Create table if not exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            image_url VARCHAR(255)
        )
    ");

    // Insert only if empty
    $count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

    if ($count == 0) {
        $insert = "
            INSERT INTO products (name, description, price, image_url) VALUES
            ('Cole Haan Modern Essentials Cap Oxford', 'Full-grain oiled leather cap-toe shoe for smart-casual or dress wear', 99.99, 'images/colehaanshoes.jpg'),
            ('On Men''s Cloud 6 Sneakers', 'Lightweight cushioning sneakers with elastic laces, sporty-chic style', 159.99, 'images/on.jpg'),
            ('L.L.Bean Men''s Slim Fit Signature Washed Field Shirt', 'Rugged herringbone fabric, garment-dyed for a worn-in look, slim fit', 89.00, 'images/llbeanshirt.jpg'),
            ('Michael Kors Men''s Classic Fit Stretch Dress Pants', 'Tailored dress pants with stretch fabric for comfort', 47.50, 'images/michaelkors.jpg'),
            ('Urban Outfitters Cotton Jump Shot Hoodie', 'Casual cotton hoodie, great for daily wear', 39.00, 'images/urban.jpg'),
            ('Calvin Klein Men''s Classic Fit Coleman Overcoat', 'Notch-lapel long overcoat, tailored look', 118.50, 'images/calvinklein.jpg')
        ";
        $pdo->exec($insert);
    }

    // Fetch all products
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<p style='color:red;'>Database connection failed: " . $e->getMessage() . "</p>";
}
?>

<div class="product-container">
<?php foreach ($products as $product): ?>
    <div class="product-card">
        
        <div class="product-image">
            <?php if (!empty($product['image_url'])): ?>
                <img src="<?= htmlspecialchars($product['image_url']); ?>" 
                     alt="<?= htmlspecialchars($product['name']); ?>">
            <?php else: ?>
                <div class="placeholder">
                    <?= strtoupper(substr($product['name'], 0, 1)); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-info">
            <div class="product-name"><?= htmlspecialchars($product['name']); ?></div>
            <div class="product-description"><?= htmlspecialchars($product['description']); ?></div>
            <div class="product-price">$<?= number_format($product['price'], 2); ?></div>
        </div>

    </div>
<?php endforeach; ?>
</div>

</body>
</html>
