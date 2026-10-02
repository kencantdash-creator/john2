<?php require '../includes/functions.php'; require_role('admin'); $title='Requests';
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf(); $id=(int)$_POST['id'];
 if(($_POST['do']??'')==='delete'){ $d=$pdo->prepare('SELECT file_path FROM request_documents WHERE request_id=?'); $d->execute([$id]); foreach($d->fetchAll() as $f) @unlink(__DIR__.'/../uploads/requests/'.$f['file_path']); $pdo->prepare('DELETE FROM requests WHERE id=?')->execute([$id]); }
 elseif(in_array($_POST['status']??'',STATUSES)){ $pay=in_array($_POST['payment']??'',['Unpaid','Paid'])?$_POST['payment']:'Unpaid'; $pdo->prepare('UPDATE requests SET status=?,payment_status=? WHERE id=?')->execute([$_POST['status'],$pay,$id]); }
 go('admin/requests.php'); }
$rows=$pdo->query('SELECT r.*,s.service_name,u.full_name FROM requests r JOIN services s ON s.id=r.service_id JOIN users u ON u.id=r.user_id ORDER BY r.submitted_at DESC')->fetchAll(); require '../includes/header.php'; ?>
<h2>Requests</h2><section class="card scroll"><table><tr><th>Reference #</th><th>Resident</th><th>Service</th><th>Status</th><th>Update</th></tr>
<?php foreach($rows as $r): ?><tr><td><?=e($r['reference_number'])?></td><td><?=e($r['full_name'])?></td><td><?=e($r['service_name'])?></td><td><?=pill($r['status'])?></td>
<td><form method="post" class="inl"><?=csrf()?><input type="hidden" name="id" value="<?=$r['id']?>"><select name="status"><?php foreach(STATUSES as $s) echo '<option '.($s===$r['status']?'selected':'').'>'.e($s).'</option>'; ?></select>
<select name="payment"><?php foreach(['Unpaid','Paid'] as $p) echo '<option '.($p===$r['payment_status']?'selected':'').'>'.$p.'</option>'; ?></select><button class="btn sm">Save</button>
<button class="btn danger sm" name="do" value="delete" onclick="return confirm('Delete this request?')">Delete</button></form></td></tr><?php endforeach; ?></table></section><?php require '../includes/footer.php'; ?>
