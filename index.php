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

<div class="setup-note">
    <strong>Setup Instructions:</strong>
    <ol>
        <li>Create a MySQL database named 'products_db'</li>
        <li>Update database credentials in the PHP file if needed</li>
        <li>Create an 'images' folder and add product images</li>
        <li>The table will be created automatically when you run this file</li>
    </ol>
</div>

<div class="product-container">
    <?php
    // Database configuration
    $host = 'localhost';
    $dbname = 'products_db';
    $username = 'root';
    $password = '';

    try {
        // Create connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create table if it doesn't exist
        $createTable = "CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10, 2) NOT NULL,
            image_url VARCHAR(255)
        )";
        $pdo->exec($createTable);
        
        // Check if table is empty and insert sample data
        $count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        
        if ($count == 0) {
            // Insert actual products
            $insertData = "INSERT INTO products (name, description, price, image_url) VALUES
                ('Cole Haan Modern Essentials Cap Oxford', 'Full-grain oiled leather cap-toe shoe for smart-casual or dress wear', 99.99, 'images/colehaanshoes.jpg'),
                ('On Men\'s Cloud 6 Sneakers', 'Lightweight cushioning sneakers with elastic laces, sporty-chic style', 159.99, 'images/on.jpg'),
                ('L.L.Bean Men\'s Slim Fit Signature Washed Field Shirt', 'Rugged herringbone fabric, garment-dyed for a worn-in look, slim fit', 89.00, 'images/llbean-shirt.jpg'),
                ('Michael Kors Men\'s Classic Fit Stretch Dress Pants', 'Tailored dress pants incorporating stretch fabric for comfort and style', 47.50, 'images/michael-kors-pants.jpg'),
                ('Urban Outfitters Cotton Jump Shot Hoodie', 'Casual hoodie in cotton, versatile for everyday wear', 39.00, 'images/urban-outfitters-hoodie.jpg'),
                ('Calvin Klein Men\'s Classic Fit Coleman Overcoat', 'Notch-lapel long overcoat, clean tailored look for colder days', 118.50, 'images/calvin-klein-overcoat.jpg')";
            $pdo->exec($insertData);
        }
        
        // Fetch all products
        $stmt = $pdo->query("SELECT * FROM products ORDER BY id");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Display products
        foreach ($products as $product): ?>
            <div class="product-card">
                <?php if (!empty($product['image_url']) && file_exists($product['image_url'])): ?>
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                <?php else: ?>
                    <div class="placeholder-image">
                        <?php echo strtoupper(substr($product['name'], 0, 1)); ?>
                    </div>
                <?php endif; ?>
                
                <div class="product-info">
                    <div class="product-name">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </div>
                    <div class="product-description">
                        <?php echo htmlspecialchars($product['description']); ?>
                    </div>
                    <div class="product-price">
                        $<?php echo number_format($product['price'], 2); ?>
                    </div>
                </div>
            </div>
        <?php endforeach;
        
    } catch(PDOException $e) {
        echo '<div class="error-message">Connection failed: ' . $e->getMessage() . '</div>';
    }
    ?>
</div>
```

</body>
</html>