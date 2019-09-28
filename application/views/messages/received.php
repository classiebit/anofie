<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(!empty($messages)) { ?>
    <?php foreach($messages as $value) { ?>
    <!-- Main Item row start-->
    <div class="list-group-item list-group-item-action border-0">
        
        <!-- Item title -->
        <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1">
            <?php if(!$type2) { ?>
                <?php echo lang("sm_anonymous") ?>
            <?php } ?>
            </h6>
            
            <small class="text-muted"><?php echo time_ago($value->added); ?></small>
        </div>
        
        <!-- Item content | message -->
        <p class="mb-1"><?php echo $value->message ?></p>
        
        <!-- Action row favorite|report|delete -->
        <div class="d-flex row-border-b">
            <!-- left side -->
            <div class="p-1">
                <!-- Favorite -->
                <small 
                    class="badge badge-pill badge-danger pointer-elem <?php echo $value->m_favorite == 0 ? 'bg-white' : '' ?>" 
                    data-toggle="tooltip" 
                    data-placement="top" 
                    title="<?php echo lang('sm_favorite') ?>" 
                    data-container="body" 
                    data-animation="true"
                    id="fav<?php echo $value->id ?>"     
                    onclick="messageFavorite(<?php echo $value->id ?>)"
                >
                    <i class="fas fa-heart"></i>
                </small>
            </div>

            <!-- right side -->
            <div class="ml-auto p-2">
                <small class="pointer-elem-hover text-muted" 
                    data-toggle="tooltip" 
                    data-placement="top" 
                    title="<?php echo lang('action_report').' '.lang('menu_message'); ?>" 
                    data-container="body" 
                    data-animation="true"
                    onclick="messageReport(<?php echo $value->id ?>, 
                                        '<?php echo $type=='r' ? 'r':'f' ?>')"
                >
                    <?php echo lang('action_report') ?>
                </small>
            </div>
            
            <div class="p-2">
                <small class="pointer-elem-hover text-muted" 
                    data-toggle="tooltip" 
                    data-placement="top" 
                    title="<?php echo lang('action_delete').' '.lang('menu_message'); ?>" 
                    data-container="body" 
                    data-animation="true"
                    onclick="messageDelete(<?php echo $value->id ?>, 
                                        '<?php echo $type=='f' ? 'f' : 'r' ?>')">
                    <?php echo lang('action_delete') ?>
                </small> 
            </div>
            
        </div>
        
    </div>
    <!-- Main Item row end-->
    <?php } ?> <!-- end foreach -->
<?php } else { ?> <!-- end main if and start else -->
    <div class="list-group-item"><p class="mb-1 text-center"><?php echo lang('sm_no_more')?></p></div>
<?php } ?> <!-- end else -->