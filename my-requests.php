<?php require '../includes/functions.php'; require_role('resident'); $u=user(); $title='My Requests';
$q=trim($_GET['q']??''); $st=$_GET['status']??''; $sv=(int)($_GET['service']??0);
$sql='SELECT r.*,s.service_name FROM requests r JOIN services s ON s.id=r.service_id WHERE r.user_id=?'; $a=[$u['id']];
if($q!==''){ $sql.=' AND r.reference_number LIKE ?'; $a[]="%$q%"; } if(in_array($st,STATUSES)){ $sql.=' AND r.status=?'; $a[]=$st; } if($sv){ $sql.=' AND r.service_id=?'; $a[]=$sv; }
$s=$pdo->prepare($sql.' ORDER BY r.submitted_at DESC'); $s->execute($a); $rows=$s->fetchAll(); $svcs=$pdo->query('SELECT id,service_name FROM services ORDER BY service_name')->fetchAll();
require '../includes/header.php'; ?>
<h2>My Requests</h2><form class="filters" method="get"><input name="q" placeholder="Search reference #" value="<?=e($q)?>">
<select name="status"><option value="">All statuses</option><?php foreach(STATUSES as $x) echo '<option '.($x===$st?'selected':'').'>'.e($x).'</option>'; ?></select>
<select name="service"><option value="0">All services</option><?php foreach($svcs as $x) echo '<option value="'.$x['id'].'" '.($x['id']==$sv?'selected':'').'>'.e($x['service_name']).'</option>'; ?></select><button class="btn">Filter</button></form>
<section class="card scroll"><table><tr><th>Reference #</th><th>Service</th><th>Date Submitted</th><th>Status</th><th>Action</th></tr>
<?php foreach($rows as $r): ?><tr><td><?=e($r['reference_number'])?></td><td><?=e($r['service_name'])?></td><td><?=date('M d, Y',strtotime($r['submitted_at']))?></td><td><?=pill($r['status'])?></td><td><a class="btn ghost sm" href="request-view.php?id=<?=$r['id']?>">View</a></td></tr>
<?php endforeach; if(!$rows) echo '<tr><td colspan="5" class="muted">No matching requests.</td></tr>'; ?></table></section><?php require '../includes/footer.php'; ?>
