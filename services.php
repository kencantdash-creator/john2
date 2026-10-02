<?php require '../includes/functions.php'; require_role('admin'); $title='Services';
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf(); $id=(int)($_POST['id']??0);
 if(($_POST['do']??'')==='delete'){ try{ $pdo->prepare('DELETE FROM services WHERE id=?')->execute([$id]); }catch(PDOException $x){ $pdo->prepare("UPDATE services SET status='inactive' WHERE id=?")->execute([$id]); } }
 else { $v=[trim($_POST['service_name']),trim($_POST['description']),trim($_POST['requirements']),(int)$_POST['processing_days'],(float)$_POST['fee'],$_POST['icon']??'file',$_POST['status']==='inactive'?'inactive':'active'];
  if($v[0]!==''){ if($id) $pdo->prepare('UPDATE services SET service_name=?,description=?,requirements=?,processing_days=?,fee=?,icon=?,status=? WHERE id=?')->execute([...$v,$id]);
   else $pdo->prepare('INSERT INTO services(service_name,description,requirements,processing_days,fee,icon,status) VALUES(?,?,?,?,?,?,?)')->execute($v); } }
 go('admin/services.php'); }
$ed=null; if(isset($_GET['edit'])){ $q=$pdo->prepare('SELECT * FROM services WHERE id=?'); $q->execute([(int)$_GET['edit']]); $ed=$q->fetch(); }
$rows=$pdo->query('SELECT * FROM services ORDER BY id')->fetchAll(); require '../includes/header.php'; ?>
<h2>Services</h2><form class="card form" method="post"><?=csrf()?><input type="hidden" name="id" value="<?=e($ed['id']??0)?>"><h3><?=$ed?'Edit service':'Add service'?></h3>
<label>Name<input name="service_name" required value="<?=e($ed['service_name']??'')?>"></label><label>Description<input name="description" value="<?=e($ed['description']??'')?>"></label>
<label>Requirements<input name="requirements" value="<?=e($ed['requirements']??'')?>"></label><label>Processing days<input type="number" name="processing_days" min="0" value="<?=e($ed['processing_days']??1)?>"></label>
<label>Fee (PHP)<input type="number" step="0.01" min="0" name="fee" value="<?=e($ed['fee']??0)?>"></label>
<label>Icon<select name="icon"><?php foreach(['file','user','grid','users'] as $i) echo '<option '.(($ed['icon']??'')===$i?'selected':'').'>'.$i.'</option>'; ?></select></label>
<label>Status<select name="status"><option>active</option><option <?=($ed['status']??'')==='inactive'?'selected':''?>>inactive</option></select></label><button class="btn">Save service</button></form>
<section class="card scroll"><table><tr><th>Name</th><th>Fee</th><th>Days</th><th>Status</th><th>Action</th></tr><?php foreach($rows as $s): ?><tr><td><?=e($s['service_name'])?></td><td><?=number_format($s['fee'],2)?></td><td><?=(int)$s['processing_days']?></td><td><?=e($s['status'])?></td>
<td class="inl"><a class="btn ghost sm" href="?edit=<?=$s['id']?>">Edit</a><form method="post"><?=csrf()?><input type="hidden" name="id" value="<?=$s['id']?>"><button class="btn danger sm" name="do" value="delete" onclick="return confirm('Delete service?')">Delete</button></form></td></tr><?php endforeach; ?></table></section><?php require '../includes/footer.php'; ?>
