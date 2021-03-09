<!DOCTYPE html>
<html lang="en">
<head>
<title><?=(isset($title)) ? $title : $this->l_skin->apps_config('title');?></title>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="<?php if(isset($description)){echo $description;} ?>">
<meta name="keywords" content="<?php if(isset($keywords)){echo $keywords;} ?>">
<meta name="author" content="Ardi Arcadia">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="no-cache">

<link rel="stylesheet" href="<?=base_url('lib/bootstrap/css/bootstrap.min.css');?>">
<link rel="stylesheet" href="<?=base_url('lib/fontawesome/fontawesome.min.css');?>">
<?php if(isset($css)){$this->l_skin->css_load($css);} ?>
<link rel="stylesheet" href="<?=base_url('src/css/global.css?v='.filemtime('src/css/global.css').'');?>">
<link rel="icon" href="<?=base_url($this->l_skin->apps_config('favicon'));?>" type="image/x-icon">
<script>var site_url='<?=site_url();?>';</script>
</head>
<body>

<?=$content;?> 

<script src="<?=base_url('lib/jquery/jquery.min.js')?>"></script>
<script src="<?=base_url('lib/bootstrap/js/bootstrap.min.js')?>"></script>
<?php if(isset($js)){$this->l_skin->js_load($js);} ?>
</body>
</html>