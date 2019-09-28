<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Widgets -->
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>
                    <?php echo lang('users_register_terms'); ?>
                </h2>
            </div>
            <div class="body">
                <?php echo $this->settings->terms_n_conditions; ?>        
            </div>
        </div>
    </div>
</div>