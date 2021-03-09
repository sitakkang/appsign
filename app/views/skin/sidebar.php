<nav class="sidenav" data-sidenav data-sidenav-toggle="#sidenav-toggle">
	<div class="sidenav-brand text-center">
		<a class="toggle back" id="sidenav-toggle"><i class="fa fa-angle-left"></i></a>
		<a class="avatar-username" href="<?=site_url();?>"> <img class="img-responsive logo-sidebar center-block" src="<?=site_url().$this->l_skin->apps_config('logo');?>"></a>
	</div>

	<ul class="sidenav-menu">
		<?=$this->l_skin->menu_sidebar();?>
	</ul>

 </nav>