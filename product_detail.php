<?php
session_start();
require_once 'config/database.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// 【SQL注入漏洞】不安全的查询方式
$sql = "SELECT * FROM products WHERE id = $id";
$result = $pdo->query($sql);
$product = $result->fetch(PDO::FETCH_ASSOC);

if(!$product) {
    header('Location: /OA/products.php');  // ← 修改1：加 /OA/
    exit;
}

include 'includes/header.php';
?>

<div style="background: white; padding: 30px; border-radius: 8px;">
    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
    
    <div style="margin: 20px 0;">
        <span class="price" style="font-size: 24px;">¥<?php echo number_format($product['price'], 2); ?></span>
        <span style="margin-left: 20px; color: #7f8c8d;">分类: <?php echo htmlspecialchars($product['category']); ?></span>
        <span style="margin-left: 20px; color: #7f8c8d;">库存: <?php echo $product['stock']; ?> 件</span>
    </div>
    
    <h3>产品描述</h3>
    <p style="line-height: 1.8;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
    
    <div style="margin-top: 30px;">
        <a href="/OA/products.php" class="btn">返回产品列表</a>  <!-- ← 修改2：加 /OA/ -->
    </div>
</div>

<?php include 'includes/footer.php'; ?>