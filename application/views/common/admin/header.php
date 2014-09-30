<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $this->config->item('default_title')?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800|Oswald:400,700,300|Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css' />
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.css">
<link href="<?php echo base_url('css/validationengine.css') ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('css/multiple-select.css') ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('css/jquery.qtip.min.css') ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('css/admin-style.css') ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('css/admin-responsive.css') ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('css/common.css').'?v='.rand() ?>" rel="stylesheet" type="text/css">


<script type="text/javascript" src="<?php echo base_url('js/jquery-latest.min.js')?>"></script>
<!--<script type="text/javascript" src="<?php echo base_url('js/Selectyze.jquery.js')?>"></script>
--><script type="text/javascript" src="<?php echo base_url('js/validationengine.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/validationengine-en.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/html5uploader.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/tabs.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/jquery-form.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/jquery.multiple.select.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/ckeditor/ckeditor.js')?>"></script>
<script src="<?php echo base_url('js/visuallightbox.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('js/vlbdata1.js')?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('js/ckeditor/adapters/jquery.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/jquery.multiple.select.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/jquery.qtip-1.0.0-rc3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/peerJs.js?v=').filemtime('js/peerJs.js')?>"></script>
<script src="<?php echo base_url('js/jquery.mCustomScrollbar.concat.min.js')?>"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.js"></script>
<!-- User defined js-->
<script type="text/javascript">
	var base_path = ' <?php echo base_url();?>';
	var user_role = '<?php echo @getCurrentUserRole()?>';
	var main_container = '<?php echo @getCurrentUserRole()== '2' ? "portal" : "admin"?>';
</script>
<script type="text/javascript" src="<?php echo base_url('js/base.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/admin-script.js?v=').filemtime('js/admin-script.js')?>"></script>
</head>
<body>