<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
                            
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart(site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'save'), array('class' => 'form-horizontal', 'id' => 'form-create', 'role'=>"form")); ?>
                
                <div class="card-body">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <?php echo lang('manage_acl_select_group', 'role', array('class'=>'role')); ?>
                            <?php echo form_dropdown($groups); ?>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <h3><?php echo lang('manage_acl_permissions'); ?></h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?php foreach($controllers as $val) { // start foreach ?>
                            <div class="row border-bottom p-2">
                                <div class="col-md-3">
                                    <label class="section-title mt-0">
                                        <?php echo lang("menu_".$val['name']) ?>
                                    </label>
                                </div>
                                <div class="col-md-2">
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input 
                                            class="custom-control-input" 
                                            type="checkbox" 
                                            id="<?php echo $val['name'] ?>_view" 
                                            name="<?php echo $val['name'] ?>_view" 
                                            value="1" 
                                            class="filled-in" 
                                            <?php echo !empty($p[$val['name'].'_view']) ? 'checked' : ''; ?>
                                        >
                                        <label class="custom-control-label" for="<?php echo $val['name'] ?>_view">
                                            <?php echo lang('manage_acl_p_view'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <?php if(in_array($val['name'], $add)) { ?>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input 
                                            class="custom-control-input" 
                                            type="checkbox" 
                                            id="<?php echo $val['name'] ?>_add" name="<?php echo $val['name'] ?>_add" 
                                            value="1" 
                                            class="filled-in" 
                                            <?php echo !empty($p[$val['name'].'_add']) ? 'checked' : ''; ?>
                                        >
                                        <label class="custom-control-label" for="<?php echo $val['name'] ?>_add">
                                            <?php echo lang('manage_acl_p_add'); ?>
                                        </label>
                                    </div>
                                    <?php } ?> 
                                </div>
                                <div class="col-md-2">
                                    <?php if(in_array($val['name'], $edit)) { ?>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input 
                                            class="custom-control-input" 
                                            type="checkbox" 
                                            id="<?php echo $val['name'] ?>_edit" 
                                            name="<?php echo $val['name'] ?>_edit" 
                                            value="1" 
                                            class="filled-in" 
                                            <?php echo !empty($p[$val['name'].'_edit']) ? 'checked' : ''; ?>
                                        >
                                        <label class="custom-control-label" for="<?php echo $val['name'] ?>_edit">
                                            <?php echo lang('manage_acl_p_edit'); ?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="col-md-2">
                                    <div class="custom-control custom-checkbox custom-control-inline">   
                                    <?php if(in_array($val['name'], $delete)) { ?>
                                        <input 
                                            class="custom-control-input"
                                            type="checkbox" id="<?php echo $val['name'] ?>_delete" 
                                            name="<?php echo $val['name'] ?>_delete" value="1"
                                            class="filled-in" 
                                            <?php echo !empty($p[$val['name'].'_delete']) ? 'checked' : ''; ?>
                                        >
                                        <label class="custom-control-label"
                                            for="<?php echo $val['name'] ?>_delete">
                                            <?php echo lang('manage_acl_p_delete'); ?>
                                        </label>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div><br>
                            <?php } // end foreach ?>
                        </div>
                    </div>

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