<?php
session_start();
require_once __DIR__ . '/../config/database.php';
const BASE = '/barangay-service-hub';
const STATUSES = ['Pending','In Process','Approved','For Payment','Completed','Rejected'];
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function go($p){ header('Location: '.BASE.'/'.$p); exit; }
function user(){ return $_SESSION['user'] ?? null; }
function require_login(){ if(!user()) go('login.php'); }
function require_role($r){ require_login(); if(user()['role']!==$r) go(user()['role']==='admin'?'admin/dashboard.php':'resident/dashboard.php'); }
function csrf(){ if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(16)); return '<input type="hidden" name="csrf" value="'.$_SESSION['csrf'].'">'; }
function check_csrf(){ if(!hash_equals($_SESSION['csrf']??'', $_POST['csrf']??'')) { http_response_code(400); exit('Invalid form token.'); } }
function pill($s){ $c=['Pending'=>'gray','In Process'=>'warn','Approved'=>'ok','For Payment'=>'blue','Completed'=>'ok','Rejected'=>'bad'][$s]??'gray'; return '<span class="pill '.$c.'">'.e($s).'</span>'; }
function icon($n){ $p=[
 'home'=>'M3 11l9-8 9 8v10h-6v-6H9v6H3z','file'=>'M6 2h9l5 5v15H6zM14 2v6h6M9 13h8M9 17h8','user'=>'M12 12a5 5 0 100-10 5 5 0 000 10zM3 22c0-5 4-8 9-8s9 3 9 8',
 'grid'=>'M3 3h8v8H3zM13 3h8v8h-8zM3 13h8v8H3zM13 13h8v8h-8z','bell'=>'M6 9a6 6 0 1112 0c0 6 3 7 3 8H3c0-1 3-2 3-8zM10 21h4','arrow'=>'M5 12h14M13 6l6 6-6 6',
 'out'=>'M9 3H4v18h5M16 8l5 4-5 4M21 12H9','users'=>'M9 11a4 4 0 100-8 4 4 0 000 8zM2 21c0-4 3-6 7-6s7 2 7 6M17 4a4 4 0 010 7M22 21c0-3-2-5-4-5'];
 return '<svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="'.($p[$n]??$p['file']).'"/></svg>'; }
function next_ref($pdo){ $y=date('Y'); $n=(int)$pdo->query("SELECT COUNT(*) FROM requests")->fetchColumn()+1;
 do{ $r=sprintf('BRGY-%s-%04d',$y,$n++); $q=$pdo->prepare('SELECT 1 FROM requests WHERE reference_number=?'); $q->execute([$r]); }while($q->fetch()); return $r; }
