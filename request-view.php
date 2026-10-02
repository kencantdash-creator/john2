<?php require '../includes/functions.php'; require_role('resident'); $title='Request Details';
$q=$pdo->prepare('SELECT r.*,s.service_name FROM requests r JOIN services s ON s.id=r.service_id WHERE r.id=? AND r.user_id=?'); $q->execute([(int)($_GET['id']??0),user()['id']]); $r=$q->fetch();
if(!$r){ http_response_code(404); exit('Request not found.'); }
$d=$pdo->prepare('SELECT * FROM request_documents WHERE request_id=?'); $d->execute([$r['id']]); $docs=$d->fetchAll();
$steps=['Submitted','In Process','Approved','For Payment','Completed']; $idx=['Pending'=>0,'In Process'=>1,'Approved'=>2,'For Payment'=>3,'Completed'=>4][$r['status']]??-1;
require '../includes/header.php'; ?>
<h2>Request <?=e($r['reference_number'])?></h2>
<?php if($idx>=0): ?><div class="track"><?php foreach($steps as $i=>$s) echo '<div class="step '.($i<$idx?'past':($i==$idx?'now':'')).'"><i>'.($i+1).'</i>'.e($s).'</div>'; ?></div><?php else: echo '<p class="err">This request was rejected.</p>'; endif; ?>
<section class="card"><p>Service: <b><?=e($r['service_name'])?></b></p><p>Purpose: <?=e($r['purpose'])?></p><p>Date Submitted: <?=date('M d, Y',strtotime($r['submitted_at']))?></p>
<p>Current Status: <?=pill($r['status'])?></p><p>Payment Status: <?=e($r['payment_status'])?></p><p>Notes: <?=e($r['notes']?:'None')?></p>
<h3>Uploaded Requirements</h3><?php foreach($docs as $f) echo '<p><a href="'.BASE.'/uploads/requests/'.e($f['file_path']).'" target="_blank">'.e($f['file_name']).'</a></p>'; if(!$docs) echo '<p class="muted">No files uploaded.</p>'; ?>
<a class="btn ghost" href="my-requests.php">Back to My Requests</a></section><?php require '../includes/footer.php'; ?>
