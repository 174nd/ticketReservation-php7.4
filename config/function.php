<?php

session_start();
require_once('conn.php');

function anti_injection($sasdsa, $data)
{
  $hasilnya = stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES)));
  $filter = mysqli_real_escape_string($sasdsa, $hasilnya);
  return $filter;
}

function kicked($akses)
{
  global $conn;
  global $df;
  $cekdata = mysqli_query($conn, "SELECT * FROM login WHERE username LIKE '$_SESSION[username]' AND password LIKE '$_SESSION[password]'" . (($akses != false) ? " AND akses LIKE '$akses'" : ''));
  if (mysqli_num_rows($cekdata) <= 0) {
    header("location:" . $df['host'] . 'logout/');
  } else {
    return true;
  }
}

function setKode($huruf, $digit, $tabel, $field)
{
  global $conn;
  $cekdata = mysqli_query($conn, "SELECT MAX($field) AS taking FROM $tabel");
  $ada = mysqli_fetch_array($cekdata);
  if (mysqli_num_rows($cekdata) > 0) {
    $depan = strlen($huruf);
    $angka = $digit - $depan;
    $IDAuto = ((int) substr($ada['taking'], $depan, $digit)) + 1;
    $IDAuto = $huruf . str_pad($IDAuto, $angka, "0", STR_PAD_LEFT);
  } else {
    $depan = strlen($huruf);
    $angka = $digit - $depan;
    $IDAuto = $huruf . str_pad("1", $angka, "0", STR_PAD_LEFT);
  }
  return $IDAuto;
}


function cekSama($kiriman, $returnnya, $val = "selected", $notsame = "")
{
  if ((is_array($returnnya)) ? in_array($kiriman, $returnnya) : $kiriman == $returnnya) {
    return $val;
  } else {
    return $notsame;
  }
}


function notifikasi($set = 'out')
{
  $set = ($set != 'in') ? ' mx-3' : '';
  if ((isset($_SESSION['notifikasi']) && $_SESSION['notifikasi'] != '')) {
    global $conn;
    $cekdata = mysqli_query($conn, "SELECT * FROM notifikasi where kdn='$_SESSION[notifikasi]'");
    $r = mysqli_fetch_array($cekdata);
    if (mysqli_num_rows($cekdata) == 1) {
      if ($r['dc'] == 'alert') { ?>
        <div class="alert alert-<?= $r['jenis']; ?> alert-dismissible<?= $set ?>">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="<?= $r['icon']; ?>"></i> <?= $r['head']; ?></h5>
          <?= $r['isi']; ?>
        </div>
      <?php } else { ?>
        <div class="callout callout-<?= $r['jenis']; ?>">
          <h5><i class="<?= $r['icon']; ?>"></i> <?= $r['head']; ?></h5>
          <p><?= $r['isi']; ?></p>
        </div>
      <?php }
    } else { ?>
      <div class="alert alert-danger alert-dismissible<?= $set ?>">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        Notifikasi Tidak Ditemukan!
      </div>
    <?php }
    $_SESSION['notifikasi'] = '';
  } else if ((isset($_SESSION['duplikasi']) && $_SESSION['duplikasi'] != '')) { ?>
    <div class="alert alert-warning alert-dismissible<?= $set ?>">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-exclamation-triangle"></i> Warning!</h5>
      <?= $_SESSION['duplikasi'] . ' Telah Digunakan!'; ?>
    </div>
<?php
    $_SESSION['duplikasi'] = '';
  }
}

function sidebar($sidebar, $setOn = null, $setForBG = null)
{
  if ($setOn == null) {
    $toset = null;
    $tosend = null;
  } else {
    $x = $setOn;
    $y = $setOn;
    $toset = array_splice($x, 0, 1);
    $tosend = array_splice($y, 1, count($y));
  }


  foreach ($sidebar as $key => $value) {
    if (is_bool($value[0]) && $value[0] == true) {
      echo "<li class='nav-header'>$key</li>";
    } else {
      if (is_array($value[1])) {
        $setBG = (isset($value[3]) && $value[3] != NULL) ? ' ' . $value[3] : '';
        $treeview = ((isset($value[2]) && $value[2] == true) || $toset[0] == $key) ? '  menu-open' . $setBG : '';
        $navlink = ((isset($value[2]) && $value[2] == true) || $toset[0] == $key) ? ' active' . $setBG : '';
        echo "<li class='nav-item has-treeview$treeview'><a href='#' class='nav-link$navlink'><i class='nav-icon $value[0]'></i><p>$key<i class='right fas fa-angle-left'></i></p></a><ul class='nav nav-treeview'>";
        sidebar($value[1], $tosend);
        echo "</ul>
                      </li>";
      } else {
        $setBG = (isset($value[3]) && $value[3] != NULL) ? ' ' . $value[3] : '';
        $navlink = (isset($value[2])) ? (($value[2] == true || $toset[0] == $key) ? ' active' . $setBG : '') : '';
        echo "<li class='nav-item'><a href='$value[1]' class='nav-link$navlink'><i class='nav-icon $value[0]'></i><p>$key</p></a></li>";
      }
    }
  }
}



function activedSidebar($side, $set, $setBG = null)
{
  if (is_array($set)) {
    $tocheck = $set[0];
    $tosend = array_splice($set, 1, count($set));
  }
  foreach ($side as $key => $value) {
    if (is_array($set)) {
      if ($key == $tocheck) {
        if (!empty($tosend)) {
          $side[$key][1] = activedSidebar($side[$key][1], $tosend, $setBG);
        } else {
          if (isset($setBG) && $setBG != NULL) {
            array_push($side[$key], true, $setBG);
          } else {
            array_push($side[$key], true);
          }
        }
      }
    } else {
      if ($key == $set) {
        if (isset($setBG) && $setBG != NULL) {
          array_push($side[$key], true, $setBG);
        } else {
          array_push($side[$key], true);
        }
      }
    }
  }

  return $side;
}


function setData($kiriman)
{
  global $conn;
  $sett = array();
  foreach ($kiriman as $key => $value) {

    if (is_array($value)) {
      $sett[$key] = $value;
    } else {
      if ($key != '' && $key != NULL) {
        $sett[$key] = mysqli_real_escape_string($conn, $value);
      } else {
        $sett[$key] = NULL;
      }
    }
  }
  return $sett;
}

function setInsert($value, $tabel, $pencarian = NULL, $change = NULL, $field = NULL)
{
  $set = '';
  $val = '';
  $no = 0;
  foreach ($value as $key => $values) {
    if ($no == 0) {
      $set .= "(`$key`";

      $val .= ($values != NULL) ? "('$values'" : "(NULL";
    } else {
      $set .= ", `$key`";
      $val .= ($values != NULL) ? ", '$values'" : ", NULL";
    }
    $no++;
  }
  $set .= ")";
  $val .= ")";



  global $conn;
  $qwery = "INSERT INTO `$tabel` $set VALUES $val";

  if ($pencarian == NULL && $change == NULL && $field == NULL) {
    $save = mysqli_query($conn, $qwery);
  } else {
    $set2 = '';
    if (is_array($pencarian)) {
      $no = 1;
      foreach ($pencarian as $key1 => $values1) {
        if ($no == 1) {
          $set2 .= ($values1 != NULL) ? " `$key1`='$values1'" : " `$key1`=NULL";
        } else {
          $set2 .= ($values1 != NULL) ? " AND `$key1`='$values1'" : " AND `$key1`=NULL";
        }
        $no++;
      }
    } else {
      $set2 .= $pencarian;
    }
    $save = mysqli_query($conn, "UPDATE `$tabel` SET `$change`='$field' WHERE $set2") && mysqli_query($conn, $qwery);
  }

  // Perform a query, check for error
  if ($save) {
    return true;
  } else {
    return ("Error description: " . mysqli_error($conn));
  }
}

function setUpdate($value, $tabel, $val, $pencarian = NULL, $change = NULL, $field = NULL)
{
  $set = '';
  $no = 0;
  if (is_array($value)) {
    foreach ($value as $key => $values) {
      if ($no != 0) {
        $set .= ($values != NULL) ? ", `$key`='$values'" : ", `$key`=NULL";
      } else {
        $set .= ($values != NULL) ? "`$key`='$values'" : "`$key`=NULL";
      }
      $no++;
    }
  } else {
    $set .= $value;
  }


  $set2 = '';
  $no2 = 0;
  if (is_array($val)) {
    foreach ($val as $key1 => $values1) {
      if ($no2 != 0) {
        $set2 .= ($values1 != NULL) ? " AND `$key1`='$values1'" : " AND `$key1`=NULL";
      } else {
        $set2 .= ($values1 != NULL) ? "`$key1`='$values1'" : "`$key1`=NULL";
      }
      $no2++;
    }
  } else {
    $set2 .= $val;
  }



  global $conn;
  $qwery = "UPDATE `$tabel` SET $set WHERE $set2";


  if ($pencarian == NULL && $change == NULL && $field == NULL) {
    $save = mysqli_query($conn, $qwery);
  } else {
    $set2 = '';
    if (is_array($pencarian)) {
      $no = 1;
      foreach ($pencarian as $key1 => $values1) {
        if ($no == 1) {
          $set2 .= ($values1 != NULL) ? " `$key1`='$values1'" : " `$key1`=NULL";
        } else {
          $set2 .= ($values1 != NULL) ? " AND `$key1`='$values1'" : " AND `$key1`=NULL";
        }
        $no++;
      }
    } else {
      $set2 .= $pencarian;
    }


    $save = mysqli_query($conn, "UPDATE `$tabel` SET `$change`='$field' WHERE $set2") && mysqli_query($conn, $qwery);
  }

  global $conn;

  // Perform a query, check for error
  if ($save) {
    return true;
  } else {
    return ("Error description: " . mysqli_error($conn));
  }
}

function setDelete($tabel, $val)
{
  $set2 = '';
  $no2 = 0;
  if (is_array($val)) {
    foreach ($val as $key1 => $values1) {
      if ($no2 != 0) {
        $set2 .= " AND `$key1`='$values1'";
      } else {
        $set2 .= "WHERE `$key1`='$values1'";
      }
      $no2++;
    }
  } else {
    $set2 .= "WHERE $val";
  }

  $qwery = "DELETE FROM `$tabel` $set2";
  global $conn;

  return mysqli_query($conn, $qwery);
}

function uploadFile($filenya, $ekstensi_diperbolehkan, $tempat_kirim, $behindfile = '', $filenyasebelumnya = '')
{
  if (!file_exists($filenya['tmp_name']) || !is_uploaded_file($filenya['tmp_name'])) {
    return $filenyasebelumnya;
  } else {
    $nama = $filenya['name'];
    $x = explode('.', $nama);
    $ekstensi = strtolower(end($x));
    $file_tmp = $filenya['tmp_name'];
    $newnameis = cekFile($behindfile . $nama, $tempat_kirim);
    $lokasi =  $tempat_kirim . '/' . $newnameis;


    if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {

      move_uploaded_file($file_tmp, $lokasi);

      if ($filenyasebelumnya != '') {
        if (file_exists($tempat_kirim . '/' . $filenyasebelumnya)) {
          unlink($tempat_kirim . '/' . $filenyasebelumnya);
        }
      }

      return $newnameis;
    } else {
      return 'Wrong Extension';
    }
  }
}

function deleteFile($filenya, $tempat_kirim)
{
  if (file_exists($tempat_kirim . '/' . $filenya)) {
    if (unlink($tempat_kirim . '/' . $filenya)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

function cekFile($nama, $lokasi, $no = 0)
{
  if ($no == 0) {
    $cekinnama = $nama;
  } else {
    $x = explode('.', $nama);
    $before = substr($nama, 0, strlen($nama) - strlen('.' . end($x)));
    $cekinnama = $before . '-' . $no . '.' . end($x);
  }
  if (file_exists($lokasi . '/' . $cekinnama)) {
    $no++;
    return cekFile($nama, $lokasi, $no);
  } else {
    return $cekinnama;
  }
}

function renameFile($lokasi, $belakang, $sebelumnya, $pemisahsebelumnya)
{
  if (file_exists($lokasi . '/' . $sebelumnya) && $sebelumnya != "") {
    $x = explode($pemisahsebelumnya, $sebelumnya);
    $before = substr($sebelumnya, strlen($x[0]) + strlen($pemisahsebelumnya));
    $cekinnama = $belakang . $before;
    rename($lokasi . '/' . $sebelumnya, $lokasi . '/' . $cekinnama);
    return $cekinnama;
  }
}



function tanggal_indo($tanggal)
{
  $bulan = array(
    1 =>   'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );

  $split    = explode('-', $tanggal);
  $tgl_indo = $split[2] . ' ' . $bulan[(int) $split[1]] . ' ' . $split[0];
  return $tgl_indo;
}



function bulan_indo($bulan)
{
  $hasil = array(
    1 =>   'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );
  return $hasil[intval($bulan)];
}


function cekFoto($nama, $lokasi, $pengganti = null)
{
  global $df;
  global $backurl;
  if ($nama != NULL && $nama != '') {
    if (file_exists($backurl . $lokasi . '/' . $nama)) {
      return $df['host'] . $lokasi . '/' . $nama;
    } else {
      return ($pengganti != null && $pengganti != '') ? $df['host'] . $pengganti : $df['user-image'];
    }
  } else {
    return ($pengganti != null && $pengganti != '') ? $df['host'] . $pengganti : $df['user-image'];
  }
}

function format_rupiah($angka)
{

  $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
  return $hasil_rupiah;
}
