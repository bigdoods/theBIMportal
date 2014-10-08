<?php
if (file_exists($doc_details['path'])) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$doc_details['name']);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($doc_details['path']));
    readfile($doc_details['path']);
    exit;
}
?>