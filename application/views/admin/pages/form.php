<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid mt--8">

    <div class="row mt-3 index-page">
        <div class="col">
            <div class="card shadow bg-secondary">

                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo lang('action_edit').' - '.ucfirst($page).' '.lang('menu_page'); ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart('/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/save', array('class' => 'form-horizontal', 'id' => 'form-create', 'role'=>"form")); ?>
                
                <div class="card-body">

                    <?php if(! empty($id)) { ?> <!-- in case of update pages -->
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <?php } ?>

                    <div class="form-row">
                        <div class="form-group">
                            <?php echo lang('common_title', 'title', array('class'=>'title')); ?>
                            <?php echo form_input($title);?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <?php echo lang('common_content', 'content', array('class'=>'content')); ?>
                            <?php echo form_textarea($content);?>
                        </div>
                    </div>

                </div> 
                <div class="card-footer">
                    <!-- Back Button -->
                    <a href="<?php echo site_url($this->uri->segment(1).'/'.$this->uri->segment(2)) ?>" class="btn btn-secondary">
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