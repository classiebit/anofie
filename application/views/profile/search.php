<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(!empty($users)) { ?>
    <?php foreach($users as $key => $val) { ?>
    <a href="<?php echo site_url($val->username); ?>" class="list-group-item list-group-item-action">
        <div class="row">
            <div class="col-8">
                <img src="<?php echo $val->image ? base_url('upload/users/images/').image_to_thumb($val->image) : base_url('themes/default/img/avatar.png'); ?>" class="float-left img-fluid rounded-circle mr-3 shadow float-rtl" width="52px">
                <h6 class="mt-1 mb-0"><?php echo $val->fullname; ?></h6>
                <small class="text-muted"><?php echo $val->username; ?></small>
            </div>
            <div class="col-4 text-right">
                <small class="text-muted"><?php echo time_ago($val->date_updated, lang('common_status_active')) ?></small>
            </div>
        </div>
    </a>
    <?php } ?>
<?php } else { ?> <!-- end main if and start else -->
<div class="list-group-item list-group-item-action"><p class="mb-1 text-center"><?php echo sprintf(lang('alert_not_found'), lang('menu_user')) ?></p></div>
<?php } ?> <!-- end else -->

