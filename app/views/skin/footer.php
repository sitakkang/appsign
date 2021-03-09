<div id="loading"> 
	<div class="loader">
        <img src="<?php echo base_url('img/loading.gif'); ?>">
        <label>Data sedang di proses<label>
    </div> 
	<div class="overlay"></div>
</div>
<div class="container">
	<br><hr class="footer">
	<p align="center"> <?=$this->l_skin->apps_config('footer');?> </p>
	<p align="center">Version <?=CI_VERSION;?> - Page rendered in <b>{elapsed_time}</b> second </p>
</div>