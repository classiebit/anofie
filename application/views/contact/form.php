<?php defined('BASEPATH') OR exit('No direct script access allowed');  ?>

<main>

<section class="section section-lg bg-gradient-default">
    <div class="container pt-lg pb-300">
        <div class="row text-center justify-content-center">
            <div class="col-lg-10">
                <h2 class="display-3 text-white"><?php echo lang('contact') ?></h2>
            </div>
        </div>
        <div class="row row-grid mt-5">
            <div class="col-lg-12 text-center">
                <div class="icon icon-lg icon-shape bg-gradient-white shadow rounded-circle text-primary">
                    <i class="far fa-envelope text-primary"></i>
                </div>
                <h5 class="text-white mt-3"><?php echo lang('users_email') ?></h5>
                <p class="text-white mt-3"><?php echo $this->settings->sender_email ?></p>
            </div>
        </div>
    </div>
    <!-- SVG separator -->
    <div class="separator separator-bottom zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="seperator-polygon-color" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</section>

<section class="section section-lg pt-lg-0 section-contact-us">
    <div class="container">
        <div class="row justify-content-center mt--300">
            <div class="col-lg-8">
                <div class="card bg-gradient-secondary shadow">
                    <div class="card-body p-lg-5">
                        <h4 class="mb-1"><?php echo lang('contacts_any_issue'); ?></h4>
                        <p class="mt-0"><?php echo lang('contacts_email_us'); ?></p>

                        <!-- Alert messages -->
                        <?php if ($this->session->flashdata('message')) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <span class="alert-inner--text">
                                <?php echo $this->session->flashdata('message'); ?>
                            </span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <?php elseif ($this->session->flashdata('error') || validation_errors() || $this->error) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="alert-inner--text">
                                <strong><?php echo lang('alert_oops') ?></strong> 
                                <?php echo $this->session->flashdata('error'); ?>
                                <?php echo validation_errors(); ?>
                                <?php echo $this->error; ?>
                            </span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <?php endif; ?>

                        <?php echo form_open('', array('role'=>'form', 'id'=>'form-create')); ?>

                        <div class="form-group mt-5  <?php echo form_error('name') ? ' has-danger' : ''; ?>">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-sign-out-alt"></i></span>
                                </div>
                                <input class="form-control  <?php echo form_error('name') ? ' has-danger' : ''; ?>" placeholder="<?php echo lang('common_name') ?>" name="name" type="text" value="<?php echo set_value('name') ?>" required="">
                            </div>
                        </div>
                        
                        <div class="form-group <?php echo form_error('email') ? ' has-danger' : ''; ?>">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-envelope"></i></span>
                                </div>
                                <input class="form-control <?php echo form_error('email') ? ' is-invalid' : ''; ?>" placeholder="<?php echo lang('users_email') ?>" name="email" type="email" value="<?php echo set_value('email') ?>" required="">
                            </div>
                        </div>

                        <div class="form-group mb-4 <?php echo form_error('title') ? ' has-danger' : ''; ?>">
                            <input class="form-control <?php echo form_error('title') ? ' is-invalid' : ''; ?>" placeholder="<?php echo lang('contacts_subject') ?>" name="title" type="text" value="<?php echo set_value('title') ?>" required="">
                        </div>

                        <div class="form-group mb-4 <?php echo form_error('message') ? ' has-danger' : ''; ?>">
                            <textarea class="form-control form-control-alternative <?php echo form_error('message') ? ' is-invalid' : ''; ?>" name="message" rows="4" cols="80" placeholder="<?php echo lang('contacts_message') ?>" required=""><?php echo set_value('message') ?></textarea>
                        </div>

                        <div class="form-group mb-4 <?php echo form_error('captcha') ? ' has-danger' : ''; ?>">
                            <?php echo $captcha_image; ?>
                            <input class="form-control <?php echo form_error('captcha') ? ' is-invalid' : ''; ?>" placeholder="<?php echo lang('contacts_captcha') ?>" name="captcha" type="text" value="" required="">
                        </div>

                        <div>
                            <button type="submit" class="btn btn-default btn-round btn-block btn-lg"><?php echo lang('contacts_send_message') ?></button>
                        </div>
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</main>