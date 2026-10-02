<?php require '../includes/functions.php'; require_role('resident'); $u=user(); $title='Submit Request'; $err=''; $done=null;
$svcs=$pdo->query("SELECT * FROM services WHERE status='active' ORDER BY service_name")->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf(); $sid=(int)($_POST['service_id']??0); $pur=trim($_POST['purpose']??'');
 $s=$pdo->prepare("SELECT * FROM services WHERE id=? AND status='active'"); $s->execute([$sid]); $s=$s->fetch();
 if(!$s||$pur==='') $err='Choose a service and enter the purpose.'; else {
  $files=[]; if(!empty($_FILES['docs']['name'][0])) foreach($_FILES['docs']['name'] as $i=>$nm){ if($_FILES['docs']['error'][$i]!==UPLOAD_ERR_OK) continue;
   $ext=strtolower(pathinfo($nm,PATHINFO_EXTENSION));
   if(!in_array($ext,['pdf','jpg','jpeg','png'])||$_FILES['docs']['size'][$i]>5*1024*1024){ $err='Files must be PDF, JPG or PNG, up to 5 MB each.'; break; }
   $files[]=[$_FILES['docs']['tmp_name'][$i],$nm,$ext]; }
  if(!$err){ $ref=next_ref($pdo);
   $pdo->prepare("INSERT INTO requests(reference_number,user_id,service_id,purpose,notes,status,payment_status) VALUES(?,?,?,?,?,'Pending','Unpaid')")->execute([$ref,$u['id'],$sid,$pur,trim($_POST['notes']??'')]);
   $rid=$pdo->lastInsertId();
   foreach($files as [$tmp,$nm,$ext]){ $fn=bin2hex(random_bytes(12)).'.'.$ext; move_uploaded_file($tmp,__DIR__.'/../uploads/requests/'.$fn);
    $pdo->prepare('INSERT INTO request_documents(request_id,file_name,file_path) VALUES(?,?,?)')->execute([$rid,basename($nm),$fn]); }
   $done=['ref'=>$ref,'svc'=>$s['service_name'],'id'=>$rid]; } } }
require '../includes/header.php'; ?>
<h2>Submit Request</h2>
<?php if($done): ?><section class="card"><h3>Request submitted</h3><p>Reference Number: <b><?=e($done['ref'])?></b></p><p>Service: <?=e($done['svc'])?></p><p>Date Submitted: <?=date('M d, Y')?></p><p>Current Status: <?=pill('Pending')?></p>
<a class="btn" href="request-view.php?id=<?=$done['id']?>">View request</a></section>
<?php else: if($err) echo '<p class="err">'.e($err).'</p>'; ?>
<form class="card form" method="post" enctype="multipart/form-data"><?=csrf()?>
<label>Select Service<select name="service_id" required><option value="">Choose a service</option><?php foreach($svcs as $s): ?><option value="<?=$s['id']?>" <?=($_GET['service']??'')==$s['id']?'selected':''?>><?=e($s['service_name'])?></option><?php endforeach; ?></select></label>
<label>Purpose<input name="purpose" required></label>
<label>Full Name<input value="<?=e($u['full_name'])?>" readonly></label><label>Email<input value="<?=e($u['email'])?>" readonly></label>
<label>Phone Number<input value="<?=e($u['phone'])?>" readonly></label><label>Address<input value="<?=e($u['address'])?>" readonly></label>
<label>Additional Notes<textarea name="notes" rows="3"></textarea></label><label>Upload Requirements (PDF, JPG, PNG)<input type="file" name="docs[]" multiple accept=".pdf,.jpg,.jpeg,.png"></label>
<button class="btn">Submit Request</button></form><?php endif; require '../includes/footer.php'; ?>
