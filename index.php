<?php require 'includes/functions.php'; user()? go(user()['role']==='admin'?'admin/dashboard.php':'resident/dashboard.php') : go('login.php');
