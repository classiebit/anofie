<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<main class="profile-page">

<!-- Profile header background -->
<section class="section-profile-cover section-shaped my-0">
    <!-- Circles background -->
    <div class="shape shape-style-1 shape-primary alpha-4">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <!-- SVG separator -->
    <div class="separator separator-bottom">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="seperator-polygon-color" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</section>

<!-- Message tab -->
<section class="section mt--300">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" data-toggle="tab" href="#tab-1"><i class="fas fa-user-cog mr-2"></i><?php  echo lang('settings_general') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab" href="#tab-2"><i class="fas fa-key mr-2"></i><?php echo lang('users_password') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab" href="#tab-3"><i class="fas fa-user-circle mr-2"></i><?php echo lang('settings_account') ?></a>
                        </li>
                    </ul>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <?php echo form_open_multipart('settings', array('role'=>'form', 'id'=>'form_login')); ?>
                        <div class="tab-content" id="myTabContent">
                            
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                                
                                <div class="form-group row">
                                    <label for="image" class="col-sm-2 col-form-label"><?php echo lang('users_profile_pic') ?></label>
                                    <div class="col-sm-10">
                                        <input type="file" id="wizard-picture" name="image" class="form-control form-control-alternative">
                                        <img src="<?php echo $user['image'] ? base_url('upload/users/images/').$user['image'] : base_url('themes/default/img/avatar.png'); ?>"  class="mt-3 rounded-circle img-fluid shadow shadow-lg--hover" id="wizardPicturePreview" width="64px"/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="fullname" class="col-sm-2 col-form-label"><?php echo lang('users_fullname') ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-alternative" id="fullname" name="fullname" value="<?php echo set_value('fullname', (isset($user['fullname']) ? $user['fullname'] : '')) ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label"><?php echo lang('users_email') ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-alternative" id="email" name="email" value="<?php echo set_value('email', (isset($user['email']) ? $user['email'] : '')) ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="username" class="col-sm-2 col-form-label"><?php echo lang('users_username') ?></label>
                                    <div class="col-sm-10">
                                        <div class="input-group input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><?php echo get_domain(); ?>/</span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="<?php echo lang('users_username') ?>" id="username" name="username" value="<?php echo set_value('username', (isset($user['username']) ? $user['username'] : '')) ?>" required >
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-5">
                                    <div class="col-md-2">
                                        <h6 class="text-muted"><?php echo lang('settings_privacy') ?></h6>
                                    </div>
                            
                                    <div class="col-md-10">
                                        <div class="custom-control custom-checkbox mb-3">
                                            <input class="custom-control-input" id="notifications" name="notifications" type="checkbox" <?php echo $user['notifications'] ? 'checked=""' : ''; ?>>
                                            <label class="custom-control-label" for="notifications"><?php echo lang('users_email').' '.lang('notifications') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <div class="form-group row">
                                    <div class="offset-2 col-md-10">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block"><?php echo lang('action_save') ?></button>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="tab-2-tab">
                                
                                <div class="form-group row">
                                    <label for="password_current" class="col-sm-2 col-form-label"><?php echo lang('users_password_current') ?></label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control form-control-alternative" id="password_current" placeholder="*********" name="password_current" autocomplete="off">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-sm-2 col-form-label"><?php echo lang('users_password_new') ?></label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control form-control-alternative" id="password" name="password" placeholder="*********" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="password_confirm" class="col-sm-2 col-form-label"><?php echo lang('users_password_confirm') ?></label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control form-control-alternative" name="password_confirm" id="password_confirm" placeholder="*********" autocomplete="off">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="offset-2 col-md-10">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block"><?php echo lang('action_save') ?></button>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="tab-3-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            <strong><?php echo lang('alert_caution') ?></strong> <?php echo lang('settings_account_delete_permanent') ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-4">
                                    <label for="first_name" class="col-md-2 col-form-label"><?php echo lang('settings_account_delete') ?></label>
                                    <div class="col-md-10">
                                        <button type="button" onclick="accountDelete()" class="btn btn-danger"><?php echo lang('action_delete') ?></button>
                                    </div>
                                </div>
                                
                            </div>

                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
</main>