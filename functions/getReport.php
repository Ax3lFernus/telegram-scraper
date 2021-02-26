<?php
$self = curl($baseUrl . 'api/users/' . $_COOKIE['token'] . '/getSelf');

require dirname(__DIR__, 1) . '/layouts/pdfReport.php';

$html2pdf->setDefaultFont('helvetica');
$html2pdf->writeHTML($htmlReportPage);
$html2pdf->output($tmpDir . '/report_' . $request_date_underscore .'.pdf', 'F');