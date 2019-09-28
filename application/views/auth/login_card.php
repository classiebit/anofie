<!-- Login Card -->
<div class="card-body px-lg-5 py-lg-5" <?php echo !$this->uri->segment(1) ? 'id="login-card"' : ''; ?>>

    <div class="text-center text-muted mb-4">
        <small><?php echo lang('users_login_with') ?></small>
    </div>

    <?php if(DEMO_MODE) { ?>
    <div class="text-center text-muted mb-4">
        <small><a href="https://anofie-docs.classiebit.com/docs/1.0/demo-accounts" target="_blank">Visit here for Demo Accounts</a></small>
    </div>
    <?php } ?>
    
    <?php echo form_open('auth/do_login', array('class'=>'', 'id'=>'form_login')); ?>
        <div class="form-group mb-3">
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-envelope"></i></span>
                </div>
                <input class="form-control" name="identity" id="identity" placeholder="<?php echo lang('users_email') ?>" type="email" required>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <input class="form-control" name="password" id="password" placeholder="<?php echo lang('users_password') ?>" type="password" required>
            </div>
        </div>

        <?php if(!$this->uri->segment(1)) { ?>
        <div class="row mt-3">
            <div class="col-6">
                <a href="<?php echo site_url('auth/forgot_password') ?>" class="text-light">
                    <small><?php  echo lang('users_forgot') ?></small>
                </a>
            </div>
        </div>
        <?php } ?>
        
        <div class="text-center">
            <button type="submit" class="btn btn-primary my-4"><?php echo lang('users_login'); ?></button>
        </div>
    <?php echo form_close(); ?>

</div>