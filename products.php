<?php
session_start();
require_once 'config/database.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';

// 【SQL注入漏洞】故意使用不安全的查询方式
if($category) {
    $sql = "SELECT * FROM products WHERE category = '$category' ORDER BY name";
} else {
    $sql = "SELECT * FROM products ORDER BY name";
}

$result = $pdo->query($sql);
$products = $result->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<h2>产品中心</h2>

<div class="category-filter" style="margin: 20px 0;">
    <a href="/OA/products.php?category=硬件" class="btn">硬件</a>
    <a href="/OA/products.php?category=软件" class="btn">软件</a>
    <a href="/OA/products.php?category=服务" class="btn">服务</a>
    <a href="/OA/products.php?category=安全" class="btn">安全</a>
    <a href="/OA/products.php" class="btn">全部</a>
</div>

<div class="product-grid">
    <?php foreach($products as $product): ?>
    <div class="product-card">
        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
        <p><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
        <div class="price">¥<?php echo number_format($product['price'], 2); ?></div>
        <div class="category">分类: <?php echo htmlspecialchars($product['category']); ?></div>
        <div style="margin-top: 15px;">
            <a href="/OA/product_detail.php?id=<?php echo $product['id']; ?>" class="btn">查看详情</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>