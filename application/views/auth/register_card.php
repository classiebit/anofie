<!-- Signup Card -->
<div class="card-body px-lg-5 py-lg-5" <?php echo !$this->uri->segment(1) ? 'id="signup-card" style="display: none;"' : ''; ?>>

    <div class="text-center text-muted mb-4">
        <small><?php echo lang('users_register_with') ?></small>
    </div>

    <?php echo form_open('auth/do_login', array('class'=>'', 'id'=>'form_register')); ?>
        <div class="form-group mb-3">
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="<?php echo lang('users_fullname') ?>" id="fullname" name="fullname" required >
            </div>
        </div>
        <div class="form-group">
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-envelope"></i></span>
                </div>
                <input type="email" class="form-control" placeholder="<?php echo lang('users_email') ?>" id="email" name="email" required >
            </div>
        </div>
        <div class="form-group">
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text"><?php echo get_domain(); ?>/</span>
                </div>
                <input type="text" class="form-control" placeholder="<?php echo lang('users_username') ?>" id="username" name="username" required >
                
                <div class="valid-feedback p-2"><?php echo lang('users_username_available') ?></div>
                <div class="invalid-feedback p-2"><?php echo lang('users_username_notavailable') ?></div>
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
        <div class="custom-control custom-control-alternative custom-checkbox">
            <input class="custom-control-input" id="accept" name="accept" type="checkbox" value="1" checked>
            <label class="custom-control-label" for="accept">
                <span><?php echo lang('users_register_accept') ?>
                    <a href="<?php echo site_url('terms') ?>"><?php echo lang('users_register_terms') ?></a>
                </span>
            </label>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary my-4"><?php echo lang('users_register'); ?></button>
        </div>
    <?php echo form_close(); ?>

</div>