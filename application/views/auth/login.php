<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<main>

<section class="section section-shaped section-lg">
    <div class="shape shape-style-1 bg-gradient-default">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="container pt-lg-md">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card bg-secondary shadow border-0">
                    
                    <!-- Include login card -->
                    <?php include('login_card.php') ?>

                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <a href="<?php echo site_url('auth/forgot_password') ?>" class="text-light">
                            <small><?php echo lang('users_forgot') ?></small>
                        </a>
                    </div>
                    <div class="col-6 text-right">
                        <a href="<?php echo site_url('register') ?>" class="text-light">
                            <small><?php echo lang('users_register_account') ?></small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</main>