<div class="container-fluid" style="margin-top:20px;">
    <div class="row justify-content-center">
        <div class="col col-lg-4 col-md-6 col-sm-6">
            <div class="card login-box">
                <div class="card-body">
                    <h3 class="card-title"><img class="card-img-top" src="<?=site_url($this->l_skin->apps_config('logo'));?>"></h3>
                    <hr>
                    <form action="<?=site_url('login');?>" method="POST">
                        <p class="text-center"><?=$this->l_skin->apps_config('title');?></p>
                        <hr>
                        <?=$this->session->tempdata('notif_login');?>
                        <div class="form-group">
                            <label><i class="fa fa-user"></i> Username </label>
                            <input class="form-control" type="text" placeholder="Username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-key"></i> Password </label>
                            <input class="form-control" type="password" placeholder="Password" name="password" required>
                            <?=$token;?>
                            <hr>
                            <button class="btn btn-default btn-block" type="submit"><i class="fa fa-sign-in-alt"></i> LOGIN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>