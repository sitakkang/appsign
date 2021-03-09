<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="form-group">
		<label for="title_front" class="control-label">Title Frontend</label>
		<input type="text" class="form-control" value="<?=$id->title_front;?>" id="title_front" required>
	</div>
	<div class="form-group">
		<label for="title_admin" class="control-label">Title Backend</label>
		<input type="text" class="form-control" value="<?=$id->title_back;?>" id="title_back" required>
	</div>
	<div class="form-group">
		<label for="logo1_app" class="control-label">Logo Frontend</label>
		<div class="input-group">
			<input type="text" class="form-control" value="<?=$id->logo_front;?>" id="logo_front" readonly>
			<div class="input-group-btn">
				<a class="btn btn-default btn_upload_app" data-id="<?=$id->logo_front;?>" data-type=".png" title="Upload">
					<i class="ion-upload"></i>
				</a>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="logo2_app" class="control-label">Logo Backend</label>
		<div class="input-group">
			<input type="text" class="form-control" value="<?=$id->logo_back;?>" id="logo_back" readonly>
			<div class="input-group-btn">
				<a class="btn btn-default btn_upload_app" data-id="<?=$id->logo_back;?>" data-type=".png" title="Upload">
					<i class="ion-upload"></i>
				</a>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="favicon_app" class="control-label">Favicon</label>
		<div class="input-group">
			<input type="text" class="form-control" value="<?=$id->favicon_;?>" id="favicon_" readonly>
			<div class="input-group-btn">
				<a class="btn btn-default btn_upload_app" data-id="<?=$id->favicon_;?>" data-type=".png" title="Upload">
					<i class="ion-upload"></i>
				</a>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="loading_app" class="control-label">Loading Gif</label>
		<div class="input-group">
			<input type="text" class="form-control" value="<?=$id->loading_;?>" id="loading_" readonly>
			<div class="input-group-btn">
				<a class="btn btn-default btn_upload_app"" data-id="<?=$id->loading_;?>" data-type=".gif" title="Upload">
					<i class="ion-upload"></i>
				</a>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="form-group">
		<label for="email_app" class="control-label">Email Server</label>
		<input type="text" class="form-control" value="<?=$id->email_;?>" id="email_" required>
	</div>
	<div class="form-group">
		<label for="footer_app" class="control-label">Footer Text</label>
		<input type="text" class="form-control" value="<?=$id->footer_;?>" id="footer_" required>
	</div>
	<div class="form-group">
		<label for="meta_keyword" class="control-label">Meta Description</label>
		<textarea rows="4" class="form-control" id="meta_description" required><?=$id->meta_description;?></textarea>
	</div>
	<div class="form-group">
		<label for="meta_keyword" class="control-label">Meta Keyword</label>
		<textarea rows="4" class="form-control" id="meta_keyword" required><?=$id->meta_keyword;?></textarea>
	</div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12"><hr>
	<div class="panel-group">
		<input type="hidden" id="id_tbl" value="<?=$id->id_label;?>">
		<button class="btn btn-default center-block" id="btn_save_config">Simpan</button>
	</div>
</div>