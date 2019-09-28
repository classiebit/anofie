<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<main class="mt-5 mb-5">
<section class="section section-lg mt-5 mb-5">
    <div class="container">
        <div class="row row-grid justify-content-center">
        <div class="col-lg-8 text-center">
            <h2 class="display-3"><?php echo lang('alert_oops'); ?>
            <span class="text-danger"><?php echo lang('action_404_lost') ?></span>
            </h2>
            <p class="lead"><?php echo lang('action_404'); ?></p>
            <div class="btn-wrapper">
                <a href="<?php echo site_url(); ?>" class="btn btn-primary mb-3 mb-sm-0"><?php echo lang('action_home'); ?></a>
            </div>
        </div>
        </div>
    </div>
</section>
</main>