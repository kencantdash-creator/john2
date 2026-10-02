<?php require 'includes/functions.php'; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf(); $n=trim($_POST['full_name']??''); $m=trim($_POST['email']??''); $p=$_POST['password']??'';
 if($n===''||!filter_var($m,FILTER_VALIDATE_EMAIL)||strlen($p)<6) $err='Enter a name, a valid email, and a password of 6+ characters.';
 else { $c=$pdo->prepare('SELECT 1 FROM users WHERE email=?'); $c->execute([$m]);
  if($c->fetch()) $err='That email is already registered.';
  else { $pdo->prepare("INSERT INTO users(full_name,email,password,phone,address,role,status) VALUES(?,?,?,?,?,'resident','active')")
   ->execute([$n,$m,password_hash($p,PASSWORD_DEFAULT),trim($_POST['phone']??''),trim($_POST['address']??'')]); go('login.php'); } } } ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Register</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="auth"><form class="card authbox" method="post"><h2>Create resident account</h2>
<?php if($err) echo '<p class="err">'.e($err).'</p>'; echo csrf(); ?>
<label>Full name<input name="full_name" required></label><label>Email<input type="email" name="email" required></label><label>Phone<input name="phone"></label>
<label>Address<input name="address"></label><label>Password<input type="password" name="password" required minlength="6"></label>
<button class="btn">Register</button><p class="muted"><a href="login.php">Back to sign in</a></p></form></body></html>
