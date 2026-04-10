<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>企业网站管理系统</title>
    <link rel="stylesheet" href="/OA/assets/style.css?v=2">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1 class="logo">企业网站管理系统</h1>
            <nav class="main-nav">
                <ul>
                    <li><a href="/OA/index.php">首页</a></li>
                    <li><a href="/OA/products.php">产品中心</a></li>
                    <li><a href="/OA/admin.php">管理后台</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="/OA/logout.php">退出登录</a></li>
                    <?php else: ?>
                        <li><a href="/OA/login.php">登录</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">