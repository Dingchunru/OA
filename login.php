<?php
session_start();
require_once 'config/database.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // 检查空值
    if(empty($username) || empty($password)) {
        $error = '用户名和密码不能为空';
    } else {
        // 【SQL注入漏洞】故意构造不安全的查询
        $sql = "SELECT * FROM admin_users WHERE username = '$username' AND password = MD5('$password')";
        
        try {
            $result = $pdo->query($sql);
            $user = $result->fetch(PDO::FETCH_ASSOC);
            
            if($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // 更新最后登录时间
                $updateSql = "UPDATE admin_users SET last_login = NOW() WHERE id = " . $user['id'];
                $pdo->exec($updateSql);
                
                header('Location: /OA/admin.php');
                exit;
            } else {
                $error = '用户名或密码错误';
            }
        } catch(PDOException $e) {
            $error = '登录失败，请重试';
        }
    }
}

include 'includes/header.php';
?>

<div class="login-form">
    <h2>管理员登录</h2>
    
    <?php if($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="/OA/login.php">
        <div class="form-group">
            <label for="username">用户名</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">密码</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn" style="width: 100%;">登录</button>
    </form>
    
    <div style="margin-top: 20px; font-size: 14px; color: #7f8c8d;">
        <p>提示：系统存在SQL注入漏洞，可用于安全测试</p>
        <p style="margin-top: 10px; background: #f0f0f0; padding: 10px; border-radius: 4px;">
            <strong>测试账号：</strong><br>
            用户名: admin / 密码: Admin@123456<br>
            用户名: manager1 / 密码: Manager@123456
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>