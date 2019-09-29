<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<main class="profile-page">

<!-- Profile header background -->
<section class="section-profile-cover section-shaped my-0">
    <div class="shape shape-style-1 shape-primary alpha-4">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="separator separator-bottom">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="seperator-polygon-color" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</section>

<!-- Profile & Info -->
<section class="">
    <div class="container">
        <div class="card card-profile shadow" style="z-index: 9;">
            <div class="px-4">

                <div class="row justify-content-center">
                    <div class="col-4">
                        <div class="card-profile-image">
                            <a role="button" data-toggle="modal" data-target="#modal-photo" href="#modal-photo">
                                <img src="<?php echo $user->image ? base_url('upload/users/images/').image_to_thumb($user->image) : base_url('themes/default/img/avatar.png'); ?>" class="rounded-circle" style="top: -5.5rem;">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-sm-5 mt-5 pb-2">
                    <h3><?php echo $user->fullname ?></h3>
                    <div class="h6 font-weight-300">
                        <i class="fas fa-share-alt mr-2"></i> <?php echo get_domain().'/'.$user->username; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Message tab -->
<section class="section-lg pt-2 pb-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                
                <?php echo form_open_multipart(site_url('profile/send_message'), array('role'=>'form', 'class'=>'form-horizontal', 'id'=>'form-create')); ?>
                <form action="" role="form" class="form-horizontal" id="form-create" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="user_id" value="<?php echo $user->id ?>">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="bg-wrapper">
                            <!-- dynamic background-image -->
                            <div class="bg-container" id="message_bg">
                                <!-- dynamic color -->
                                <textarea rows="4" name="message" id="message" class="form-control bg-textarea" placeholder="<?php echo lang('sm_type_message') ?>" required="" autofocus="" maxlength="500"></textarea>
                            </div>
                        </div>    

                        <div class="bg-wrapper">
                            <small class="text-muted"><?php echo lang('sm_max_length'); ?></small>
                        </div>    
                        
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block"><?php echo lang('sm_send') ?></button>
                    </div>

                </div>
                <?php echo form_close(); ?>
                
            </div>
            
        </div>
    </div>
</section>


</main>


<!-- Photo modal -->
<div class="modal fade" id="modal-photo" tabindex="-1" role="dialog" aria-labelledby="modal-photo" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger">
            <div class="modal-body p-0">
                <div class="text-center">
                    <a role="button" data-dismiss="modal" aria-label="Close">
                        <img src="<?php echo $user->image ? base_url('upload/users/images/').$user->image : base_url('themes/default/img/avatar.png'); ?>" class="img-fluid rounded shadow w-100" alt="<?php echo $user->fullname ?>">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var visited_username    = '<?php echo $user->username; ?>';
</script>