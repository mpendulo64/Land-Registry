<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

if (is_logged_in()) {
  header("Location: app.php");
  exit;
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = strtolower(trim($_POST['email'] ?? ''));
  $password = (string)($_POST['password'] ?? '');
  $password2 = (string)($_POST['password2'] ?? '');

  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email.";
  if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters.";
  if ($password !== $password2) $errors[] = "Passwords do not match.";

  if (!$errors) {
    $pdo = db();

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email=:e LIMIT 1");
    $stmt->execute([":e" => $email]);
    if ($stmt->fetch()) {
      $errors[] = "Email already registered.";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);

      $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (:e, :h)");
      $stmt->execute([":e" => $email, ":h" => $hash]);

      $id = (int)$pdo->lastInsertId();
      $_SESSION['user_id'] = $id;
      $_SESSION['user_email'] = $email;

      header("Location: app.php");
      exit;
    }
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Create Account</title>

<style>
:root{
  --blue-dark:#0f172a;
  --blue-main:#2563eb;
  --blue-light:#3b82f6;
  --blue-soft:#eff6ff;
  --white:#ffffff;
  --gray:#64748b;
  --danger:#ef4444;
}

*{
  box-sizing:border-box;
  margin:0;
  padding:0;
  font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body{
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(135deg,#1e3a8a,#2563eb,#3b82f6);
  padding:20px;
}

.card{
  width:100%;
  max-width:440px;
  background:rgba(255,255,255,0.95);
  backdrop-filter:blur(12px);
  border-radius:18px;
  padding:35px 30px;
  box-shadow:0 20px 50px rgba(0,0,0,.25);
  animation:fadeIn .6s ease;
}

@keyframes fadeIn{
  from{opacity:0;transform:translateY(20px);}
  to{opacity:1;transform:translateY(0);}
}

h2{
  text-align:center;
  margin-bottom:25px;
  color:var(--blue-dark);
  font-size:26px;
  font-weight:700;
}

label{
  display:block;
  margin-bottom:6px;
  font-size:14px;
  font-weight:600;
  color:var(--blue-dark);
}

input{
  width:100%;
  padding:12px 14px;
  border-radius:10px;
  border:1.5px solid #cbd5e1;
  margin-bottom:18px;
  font-size:14px;
  transition:.25s ease;
}

input:focus{
  border-color:var(--blue-main);
  box-shadow:0 0 0 3px rgba(37,99,235,.15);
  outline:none;
}

button{
  width:100%;
  padding:13px;
  border:none;
  border-radius:10px;
  background:linear-gradient(135deg,var(--blue-main),var(--blue-light));
  color:white;
  font-size:15px;
  font-weight:600;
  cursor:pointer;
  transition:.3s ease;
}

button:hover{
  transform:translateY(-2px);
  box-shadow:0 10px 25px rgba(37,99,235,.4);
}

.err{
  background:#fee2e2;
  border-left:4px solid var(--danger);
  color:#991b1b;
  padding:10px 14px;
  margin-bottom:18px;
  border-radius:8px;
  font-size:14px;
}

.err ul{
  margin-left:16px;
}

.links{
  margin-top:20px;
  text-align:center;
  font-size:14px;
  color:var(--gray);
}

.links a{
  color:var(--blue-main);
  font-weight:600;
  text-decoration:none;
}

.links a:hover{
  text-decoration:underline;
}

.footer{
  text-align:center;
  margin-top:15px;
  font-size:13px;
  color:var(--gray);
}
</style>
</head>

<body>

<div class="card">
  <h2>📝 Create Account</h2>

  <?php if ($errors): ?>
    <div class="err">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post">
    <label>Email Address</label>
    <input name="email"
           value="<?= htmlspecialchars($email) ?>"
           placeholder="you@email.com"
           required />

    <label>Password</label>
    <input type="password"
           name="password"
           placeholder="Minimum 8 characters"
           required />

    <label>Confirm Password</label>
    <input type="password"
           name="password2"
           placeholder="Re-enter password"
           required />

    <button type="submit">Create Account</button>
  </form>

  <div class="links">
    Already have an account? <a href="login.php">Login</a>
  </div>

  <div class="footer">
    <a href="index.php">← Back to Home</a>
  </div>
</div>

</body>
</html>