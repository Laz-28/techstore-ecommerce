<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
   
    header("Location: dashboard.php"); 
    exit();
}

require 'includes/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $stmt = $pdo->prepare("INSERT INTO products (name, price, image_url, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['price'], $_POST['image_url'], $_POST['description']]);
    header("Location: admin_inventory.php");
    exit();
}

// --- DELETE OPERATION ---
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header("Location: admin_inventory.php");
    exit();
}

// --- READ OPERATION ---
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Admin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { background-color: #f3f4f6; padding: 40px 20px; color: #374151; }
        .wrapper { max-width: 1000px; margin: 0 auto; }
        header { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .card { background: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        form { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .full { grid-column: span 2; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; }
        button { background: #2563eb; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; font-size: 12px; text-transform: uppercase; color: #6b7280; }
        .btn-del { background: #ef4444; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; }
    </style>
</head>
<body>
<div class="wrapper">
    <header>
        <h2>Admin: Inventory Management</h2>
        <div>
            <a href="dashboard.php" style="margin-right: 15px; text-decoration: none; color: #2563eb; font-weight: bold;">Back to Store</a>
            <a href="logout.php" style="background: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: bold;">Log Out</a>
        </div>
    </header>

    <div class="card">
        <h3 style="margin-bottom: 20px;">Add New Hardware</h3>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div><label>Hardware Name</label><input type="text" name="name" required></div>
            <div><label>Price (Ksh)</label><input type="number" step="0.01" name="price" required></div>
            <div class="full"><label>Image Filename (e.g. wks.webp)</label><input type="text" name="image_url" required></div>
            <div class="full"><label>Description</label><textarea name="description" rows="3" required></textarea></div>
            <button type="submit" class="full">Save to Database</button>
        </form>
    </div>

    <div class="card" style="padding: 0;">
        <table>
            <tr><th>ID</th><th>Name</th><th>Price</th><th>Image File</th><th>Action</th></tr>
            <?php foreach ($products as $row): ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                <td>Ksh <?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['image_url']); ?></td>
                <td><a href="?delete_id=<?php echo $row['id']; ?>" class="btn-del" onclick="return confirm('Delete this item?')">Delete</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
</body>
</html>