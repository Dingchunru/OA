<?php
session_start();
require_once 'config/database.php';

// 获取最新新闻
$stmt = $pdo->query("SELECT * FROM news ORDER BY published_date DESC LIMIT 5");
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<h2>欢迎访问企业网站</h2>

<div class="welcome-section" style="background: white; padding: 30px; border-radius: 8px; margin-bottom: 30px;">
    <h3>关于我们</h3>
    <p>我们是一家领先的科技企业，致力于为企业客户提供高质量的解决方案和服务。</p>
</div>

<h3>最新动态</h3>
<?php foreach($news as $item): ?>
<div class="news-item">
    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
    <div class="news-meta">
        发布时间: <?php echo $item['published_date']; ?> | 
        作者: <?php echo htmlspecialchars($item['author']); ?> | 
        阅读量: <?php echo $item['views']; ?>
    </div>
    <p><?php echo nl2br(htmlspecialchars(substr($item['content'], 0, 200))); ?>...</p>
</div>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>