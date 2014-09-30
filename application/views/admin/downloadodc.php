<?php
$file = $doc_details[0];
if (file_exists($file['path'])) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$file['name']);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file['path']));
    readfile($file['path']);
    exit;
}
?>