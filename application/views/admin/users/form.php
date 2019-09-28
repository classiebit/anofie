<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid mt--8">

    <div class="row mt-3 index-page">
        <div class="col">
            <div class="card shadow bg-secondary">

                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo !empty($id) ? lang('action_edit') : lang('action_create'); ?></h3>
                        </div>
                        <div class="col-4 text-right">
                            <!-- Delete Button -->
                            <?php if(!empty($id)) { echo '<button type="button" onclick="ajaxDelete('.$id.', ``, `'.lang('action_delete').'`)" class="btn btn-sm btn-danger">'.lang('action_delete').'</button>'; } ?>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart(site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'save'), array('class' => 'form-horizontal', 'id' => 'form-create', 'role'=>"form")); ?>
                
                <div class="card-body">

                    <?php if(! empty($id)) { ?> <!-- in case of update users -->
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <?php } ?>

                    <div class="form-row col-md-6">
                        <div class="form-group">
                            <?php echo lang('users_fullname', 'fullname', array('class'=>'fullname')); ?>
                            <?php echo form_input($fullname);?>
                        </div>
                    </div>

                    <div class="form-row col-md-6">
                        <div class="form-group ">
                            <?php echo lang('users_username', 'username', array('class'=>'username')); ?>
                            <?php echo form_input($username);?>
                        </div>
                    </div>

                    <div class="form-row col-md-6">
                        <div class="form-group ">
                            <?php echo lang('users_email', 'email', array('class'=>'email')); ?>
                            <?php echo form_input($email);?>
                        </div>
                    </div>

                    <div class="form-row col-md-6">
                        <div class="form-group ">
                            <?php echo lang('users_password', 'password', array('class'=>'password')); ?>
                            <?php echo form_password($password);?>
                        </div>
                    </div>

                    <div class="form-row col-md-6">
                        <div class="form-group ">
                            <?php echo lang('users_password_confirm', 'password_confirm', array('class'=>'password_confirm')); ?>
                            <?php echo form_password($password_confirm);?>
                        </div>
                    </div>

                    <div class="form-row col-md-6">
                        <div class="form-group ">
                            <?php echo lang('common_image', 'image'); ?>

                            <?php if(! empty($c_image)) { ?>
                            <img id="c_image" src="<?php echo base_url('upload/users/images/'.image_to_thumb($c_image)); ?>" class="img-fluid mb-2" width="64px">
                            <?php } else { ?>
                            <img id="c_image" src="<?php echo base_url('themes/admin/img/avatar2.png'); ?>" class="img-fluid mb-2" width="64px">
                            <?php } ?>

                            <?php echo form_input($image);?>
                        </div>
                    </div>

                    <?php if ($this->ion_auth->is_admin() && !empty($id)) { ?>
                    <div class="form-row col-md-6">
                        <div class="form-group ">
                            <?php echo lang('menu_group', 'groups', array('class'=>'groups')); ?>
                            <?php echo form_dropdown($groups); ?>
                        </div>
                    </div>

                    <div class="form-row col-md-6">
                        <div class="form-group ">
                            <?php echo lang('common_status', 'active', array('class'=>'active')); ?>
                            <?php echo form_dropdown($status);?>
                        </div>
                    </div>
                    <?php }?>

                </div> 
                <div class="card-footer">
                    <!-- Back Button -->
                    <a href="<?php echo site_url('admin/'.$this->uri->segment(2)) ?>" class="btn btn-secondary">
                        <?php echo lang('action_back') ?>
                    </a>
                    <button type="submit" class="btn btn-primary"><?php echo lang('action_submit') ?></button>
                    <span id="submit_loader"></span>
                </div>

                <?php echo form_close();?>

            </div>
        </div>
    </div>
</div>