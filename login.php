<?php require 'includes/functions.php'; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf();
 $q=$pdo->prepare("SELECT * FROM users WHERE email=? AND status='active'"); $q->execute([trim($_POST['email']??'')]); $u=$q->fetch();
 if($u && password_verify($_POST['password']??'',$u['password'])){ session_regenerate_id(true); unset($u['password']); $_SESSION['user']=$u; go($u['role']==='admin'?'admin/dashboard.php':'resident/dashboard.php'); }
 $err='Wrong email or password.'; } ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="auth"><form class="card authbox" method="post"><h2>Barangay Service Hub</h2><p class="muted">Sign in to continue.</p>
<?php if($err) echo '<p class="err">'.e($err).'</p>'; echo csrf(); ?>
<label>Email<input type="email" name="email" required></label><label>Password<input type="password" name="password" required></label>
<button class="btn">Sign in</button><p class="muted">No account? <a href="register.php">Register</a></p>
<p class="demo">Demo credentials only:<br>Admin: admin@barangayhub.test / admin123<br>Resident: juan@barangayhub.test / resident123</p></form></body></html>
