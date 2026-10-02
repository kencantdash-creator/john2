<?php require '../includes/functions.php'; require_role('admin'); $title='Admin Dashboard';
$n=fn($s)=>(int)$pdo->query($s)->fetchColumn();
$st=['Total Residents'=>$n("SELECT COUNT(*) FROM users WHERE role='resident'"),'Total Requests'=>$n('SELECT COUNT(*) FROM requests'),'Pending Requests'=>$n("SELECT COUNT(*) FROM requests WHERE status='Pending'"),'Completed Requests'=>$n("SELECT COUNT(*) FROM requests WHERE status='Completed'"),'Available Services'=>$n("SELECT COUNT(*) FROM services WHERE status='active'")];
$rows=$pdo->query('SELECT r.*,s.service_name,u.full_name FROM requests r JOIN services s ON s.id=r.service_id JOIN users u ON u.id=r.user_id ORDER BY r.submitted_at DESC LIMIT 8')->fetchAll(); require '../includes/header.php'; ?>
<h2>Dashboard</h2><div class="stats"><?php foreach($st as $k=>$v) echo '<div class="card"><small class="muted">'.e($k).'</small><h1>'.$v.'</h1></div>'; ?></div>
<section class="card scroll"><h3>Recent Requests</h3><table><tr><th>Reference #</th><th>Resident</th><th>Service</th><th>Status</th><th>Action</th></tr>
<?php foreach($rows as $r) echo '<tr><td>'.e($r['reference_number']).'</td><td>'.e($r['full_name']).'</td><td>'.e($r['service_name']).'</td><td>'.pill($r['status']).'</td><td><a class="btn ghost sm" href="requests.php">Manage</a></td></tr>'; ?></table></section><?php require '../includes/footer.php'; ?>
