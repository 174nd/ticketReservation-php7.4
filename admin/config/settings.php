<?php
require_once($backurl . 'config/conn.php');
require_once($backurl . 'config/function.php');
$df['home'] = $df['host'] . 'admin/';
kicked("admin");

$setSidebar = array(
  'Dashboard' => array('fas fa-window-restore', $df['home']),
  'Jadwal' => array('fas fa-business-time', $df['home'] . 'jadwal/'),
  'Kursi' => array('fas fa-couch', $df['home'] . 'kursi/'),
  'Kapal' => array('fas fa-ship', $df['home'] . 'kapal/'),
  'Perusahaan' => array('fas fa-building', $df['home'] . 'perusahaan/'),

  'Pelabuhan' => array('fas fa-anchor', $df['home'] . 'pelabuhan/'),
  'User' => array('fas fa-user', $df['home'] . 'user/'),



);
