<?php $u=user(); $adm=$u['role']==='admin'; $cur=basename($_SERVER['SCRIPT_NAME']);
$nav=$adm?[['dashboard.php','Dashboard','home'],['requests.php','Requests','file'],['services.php','Services','grid']]
 :[['dashboard.php','Home','home'],['submit-request.php','Submit Request','file'],['my-requests.php','My Requests','file'],['services.php','Services','grid']];
$dir=$adm?'admin/':'resident/'; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($title??'Barangay Service Hub')?></title><link rel="stylesheet" href="<?=BASE?>/assets/css/style.css"></head><body>
<aside class="side" id="side"><div class="brand"><img src="<?=BASE?>/assets/images/barangay-logo-placeholder.svg" alt=""><div><b>Barangay<br>Service Hub</b></div></div>
<small class="tag">Mas Mabilis &bull; Mas Madali &bull; Para sa Lahat</small>
<nav><?php foreach($nav as $n): ?><a class="<?=$cur===$n[0]?'on':''?>" href="<?=BASE.'/'.$dir.$n[0]?>"><?=icon($n[2])?><?=e($n[1])?></a><?php endforeach; ?>
<a href="<?=BASE?>/logout.php"><?=icon('out')?>Logout</a></nav>
<img class="comm" src="<?=BASE?>/assets/images/community-placeholder.svg" alt=""></aside>
<div class="wrap"><header class="top"><button class="burger" onclick="document.getElementById('side').classList.toggle('open')" aria-label="Menu">&#9776;</button>
<span class="sp"></span><span class="bell"><?=icon('bell')?><i></i></span><div class="me"><?=icon('user')?><div><b><?=e($u['full_name'])?></b><small><?=e(ucfirst($u['role']))?></small></div></div></header><main>
