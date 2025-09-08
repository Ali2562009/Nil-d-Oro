<?php
session_start();
include("db.php");

$message = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $message = "✅ Login successful! Redirecting...";
        header("refresh:2; url=products.php");
        exit();
    } else {
        $message = "❌ Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Login</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body { font-family: 'Times New Roman', serif; background:#f9f5ec; margin:20px; color:#333; }
header { text-align:center; margin-bottom:30px; }
.logo { font-family:'Great Vibes', cursive; font-size:52px; color:#333; letter-spacing:1px; }
nav { background:#333; padding:10px 0; margin-bottom:20px; }
nav ul { list-style:none; display:flex; justify-content:center; margin:0; padding:0; }
nav li { margin:0 15px; }
nav a { color:white; text-decoration:none; font-family:'Great Vibes', cursive; font-size:22px; }
nav a:hover { text-decoration:underline; }
form { max-width:400px; margin:auto; background:#fffdf6; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
label { display:block; margin-top:10px; font-family:'Great Vibes', cursive; font-size:20px; }
input[type="email"], input[type="password"] {
  width:100%; padding:8px; border-radius:6px; border:1px solid #ccc; margin-top:5px;
}
button { margin-top:15px; width:100%; padding:10px; border:none; border-radius:6px; background:#333; color:white; font-size:20px; cursor:pointer; font-family:'Great Vibes', cursive; }
button:hover { background:#555; }
.message { text-align:center; margin:15px; color:red; font-weight:bold; }
</style>
</head>
<body>
<header>
  <div class="logo">Nil d’Oro</div>
  <h1>Login 🔑</h1>
</header>

<nav>
  <ul>
    <li><a href="products.php">Products</a></li>
    <li><a href="cart.php">Cart</a></li>
    <li><a href="checkout.php">Checkout</a></li>
    <li><a href="admin.php">Admin</a></li>
    <li><a href="signup.php">Signup</a></li>
  </ul>
</nav>

<?php if($message): ?>
  <p class="message"><?= $message ?></p>
<?php endif; ?>

<form method="post">
  <label>Email</label>
  <input type="email" name="email" required>

  <label>Password</label>
  <input type="password" name="password" required>

  <button type="submit" name="login">Login</button>
</form>
</body>
</html>
