<?php
$backurl = '../../';
require_once($backurl . 'pimpinan/config/settings.php');
require_once($backurl . 'plugins/dompdf/autoload.inc.php');
require_once($backurl . 'plugins/phpqrcode/qrlib.php');

use Dompdf\Dompdf;

$dompdf = new Dompdf();

ob_start();
require_once($backurl . 'pimpinan/export/isi-tiket.php');
$script = ob_get_clean();



$dompdf->loadHtml($script);

$customPaper = array(0, 0, 600, 263);
$dompdf->set_paper($customPaper);
// $dompdf->setPaper('A4', 'potrait');
$dompdf->render();
$dompdf->stream("Tiket", array("Attachment" => 0));
exit(0);
