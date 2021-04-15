<div class="jumbotron">
	<div class="container-fluid">
		<h2><?=(isset($head_title)) ? $head_title : $this->l_skin->apps_config('head_title');?></h2>
		<p class="text-justify"><?=(isset($head_subtitle)) ? $head_subtitle : $this->l_skin->apps_config('head_subtitle');?></p>
	</div>
</div>

<div  class="container-fluid">
	<div class="row">

		<div class="col-xl-2 col-lg-2 col-md-4 col-sm-4">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title"><b>Repositories</b></h5>
				</div>
  				<div class="card-body is-no-padding">

  					<nav class="nav flex-column"">
  						<?php ksort($sidebar_data); foreach ($sidebar_data as $k => $v ) { ?>
  							<a class="nav-link" href="<?=$v['link'];?>"><i class="fa fa-caret-right"></i> <?=$k;?></a>
  						<?php } ?>
					</nav>

  				</div>
  			</div>
		</div>

		<div class="col-xl-10 col-lg-10 col-md-8 col-sm-8">
			<div class="row justify-content-center">

				<?php foreach ($icon_data as $a => $b ) { ?>

					<div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
						<div class="card is-icon-box">
							<div class="card-body">
								<a class="is-link" href="<?=$b['link'];?>">
									<div class="text-center">
										<img class="card-img-top" src="<?=base_url($b['icon']);?>" alt="Image Is Not Found !">
									</div>
								</a>
							</div>
							<div class="card-footer">
								<p class="card-text text-center"><?=$a;?></p>
							</div>
						</div>
					</div>

				<?php } ?>
			
			</div>
		</div>

	</div>
</div>
