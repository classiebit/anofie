<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(!empty($messages)) { ?>
    <?php foreach ($messages as $key => $value) { ?>
    <!-- Main Item row start-->
    <div class="list-group-item list-group-item-action border-0">
        <div class="row">
            <div class="col-8">
                <a href="<?php echo site_url($value->username) ?>">
                    <img src="<?php echo $value->image ? base_url('upload/users/images/').image_to_thumb($value->image) : base_url('themes/default/img/avatar.png'); ?>" class="float-left img-fluid rounded-circle mr-2 shadow float-rtl" alt="..." width="40px">
                    <h6 class="mt-2 ml-2"><?php echo $value->fullname ?></h6>
                </a>
            </div>
            <div class="col-4 text-right">
                <small class="text-muted"><?php echo time_ago($value->added); ?></small>
            </div>
        </div>
                
        <div class="d-flex w-100 justify-content-between mt-2"></div>
        
        <p class="mb-1"><?php echo '<small><strong>'.lang('sm_reply_you').'</strong></small> '.$value->message ?></p>
        
        
        <!-- Action row -->
        <div class="d-flex row-border-b">
            <!-- right side -->
            <div class="ml-auto p-2">
                <small class="pointer-elem-hover text-muted" 
                    data-toggle="tooltip" 
                    data-placement="top" 
                    title="<?php echo lang('action_delete').' '.lang('menu_message'); ?>" 
                    data-container="body" 
                    data-animation="true"
                    onclick="messageDelete(<?php echo $value->id ?>, 
                                        's')">
                    <?php echo lang('action_delete') ?>
                </small> 
            </div>
        </div>
        
    </div>
    <!-- Main Item row end-->
    <?php } ?> <!-- end for loop -->
<?php } else { ?> <!-- end main if and start else -->
    <div class="list-group-item"><p class="mb-1 text-center"><?php echo lang('sm_no_more')?></p></div>
<?php } ?> <!-- end else -->