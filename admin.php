<?php
session_start();
require_once 'config/database.php';

// 检查是否登录
if(!isset($_SESSION['user_id'])) {
    header('Location: /OA/login.php');
    exit;
}

// 获取统计数据
$productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$newsCount = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
$totalViews = $pdo->query("SELECT SUM(views) FROM news")->fetchColumn();

// 获取管理员信息
$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="admin-panel">
    <div class="admin-header">
        <h2>管理后台</h2>
        <div>
            欢迎回来，<?php echo htmlspecialchars($admin['username']); ?> 
            (<?php echo $admin['role']; ?>)
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>产品数量</h3>
            <div class="stat-number"><?php echo $productCount; ?></div>
        </div>
        <div class="stat-card">
            <h3>新闻数量</h3>
            <div class="stat-number"><?php echo $newsCount; ?></div>
        </div>
        <div class="stat-card">
            <h3>总阅读量</h3>
            <div class="stat-number"><?php echo $totalViews ?: 0; ?></div>
        </div>
    </div>
    
    <h3>管理员信息</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">ID</th>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo $admin['id']; ?></td>
        </tr>
        <tr>
            <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">用户名</th>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($admin['username']); ?></td>
        </tr>
        <tr>
            <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">邮箱</th>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($admin['email']); ?></td>
        </tr>
        <tr>
            <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">角色</th>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo $admin['role']; ?></td>
        </tr>
        <tr>
            <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">最后登录</th>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo $admin['last_login'] ?: '首次登录'; ?></td>
        </tr>
        <tr>
            <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">密码哈希</th>
            <td style="padding: 10px; border-bottom: 1px solid #ddd; font-family: monospace; font-size: 12px;">
                <?php echo htmlspecialchars($admin['password']); ?>
            </td>
        </tr>
    </table>
    
    <div style="margin-top: 30px;">
        <h3>快速操作</h3>
        <div style="margin-top: 20px;">
            <a href="#" class="btn">管理产品</a>
            <a href="#" class="btn">管理新闻</a>
            <a href="#" class="btn">系统设置</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>