<?php
// dashboard.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require 'includes/db.php';

// READ Operation: Fetch all active products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore - Dashboard</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { background-color: #f3f4f6; }
        
        nav { background-color: #111827; color: white; padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .user-controls { display: flex; align-items: center; gap: 15px; }
        .logout-btn { background-color: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; }
        .admin-link { color: #60a5fa; text-decoration: none; font-weight: 600; }
        
        .hero { background-color: #2563eb; color: white; text-align: center; padding: 4rem 2rem; }
        .hero h1 { font-size: 2.5rem; margin-bottom: 1rem; }
        
        .products { padding: 4rem 5%; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; }
        .card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; }
        .card img { width: 100%; height: 200px; object-fit: contain; padding: 10px; border-radius: 8px; margin-bottom: 1rem; }
        .price { font-size: 1.25rem; font-weight: bold; color: #2563eb; margin: 10px 0; }
        .buy-btn { width: 100%; padding: 10px; background-color: #111827; color: white; border: none; border-radius: 6px; cursor: pointer; }
        .description { font-size: 0.9rem; color: #6b7280; margin-bottom: 15px; }
    </style>
</head>
<body>

    <nav>
        <div class="logo">TechStore</div>
        
        <div class="user-controls">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin_inventory.php" class="admin-link">Inventory Admin</a>
            <?php endif; ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="logout-btn">Log Out</a>
        </div>
    </nav>

    <header class="hero">
        <h1>High-Performance Hardware</h1>
        <p>Explore our latest arrivals in gaming and LLM compute.</p>
    </header>

    <section class="products">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $row): ?>
                <div class="card">
                    <img src="../Images/<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p class="description"><?php echo htmlspecialchars($row['description']); ?></p>
                    <p class="price">Ksh <?php echo htmlspecialchars(number_format($row['price'], 2)); ?></p>
                    <button class="buy-btn">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; text-align: center; font-size: 1.2rem;">Inventory is currently empty.</p>
        <?php endif; ?>
    </section>

</body>
</html>