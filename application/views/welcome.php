<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<main>

<!-- Section-1 Hero with login and register -->
<section class="section section-lg section-shaped">
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
    <div class="container py-md">
        <div class="row row-grid justify-content-between align-items-center">
            <div class="col-lg-6">
                <h3 class="display-3 text-white"><?php echo $title ?></h3>
                <p class="lead text-white"><?php echo $subtitle ?></p>
                <div class="btn-wrapper">
                    <?php if(DEMO_MODE === 1) { ?>

                    <a class="btn btn-neutral mb-3 mb-sm-0" href="https://classiebit.com/anofie" target="_blank">
                        <span class="nav-link-inner--icon"><i class="fas fa-cloud-download-alt"></i> Download FREE</span>
                    </a>
                        
                    <a class="btn btn-success mb-3 mb-sm-0" href="https://classiebit.com/anofie-pro" target="_blank">
                        <span class="nav-link-inner--icon"><i class="fas fa-shopping-cart"></i> Purchase PRO</span>
                    </a>

                        <?php if(PRODUCT_TYPE == 'pro') { ?>
                        <a class="btn btn-neutral mb-3 mb-sm-0" href="https://anofie-pro-docs.classiebit.com" target="_blank">
                            <span class="nav-link-inner--icon text-primary"><i class="fas fa-book"></i> Docs</span>
                        </a>
                        <?php } else { ?>
                        <a class="btn btn-neutral mb-3 mb-sm-0" href="https://anofie-docs.classiebit.com" target="_blank">
                            <span class="nav-link-inner--icon text-primary"><i class="fas fa-book"></i> Docs</span>
                        </a>
                        <?php } ?>

                
                    <?php } else { ?>
                    <button class="btn btn-primary" onclick="toggleCard()">
                        <span class="nav-link-inner--text login-signup-toggle"><?php echo lang('action_register') ?></span>
                    </button>
                    <?php } ?>
                </div>
            </div>
            <div class="col-lg-5 mb-lg-auto">
                <div class="transform-perspective-right">
                    
                    <div class="card bg-secondary shadow border-0">
                        
                        
                        <!-- Include login card -->
                        <?php include('auth/login_card.php') ?>

                        
                        <!-- Include register card -->
                        <?php include('auth/register_card.php') ?>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- SVG separator -->
    <div class="separator separator-bottom">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="seperator-polygon-color" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</section>

<!-- Users thumbnails -->
<section class="section section-lg section-nucleo-icons pb-250">
<div class="container">
    <div class="row justify-content-center">
        <div class="col text-center">
            <h2 class="display-3"><?php echo lang('users_recent') ?></h2>
            <p class="lead"></p>
            <div class="btn-wrapper">
                <a href="<?php echo site_url('register') ?>" class="btn btn-default mt-3 mt-md-0"><?php echo lang('action_register') ?></a>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col text-center">
            <div class="blur--hover">
                <div class="u-icons-container mt-5" data-toggle="on-screen">
                    <?php foreach($recent as $key => $val) { ?>
                    <a href="<?php echo site_url($val->username) ?>" >
                        <img src="<?php echo $val->image ? base_url('upload/users/images/').image_to_thumb($val->image) : base_url('themes/default/img/avatar.png'); ?>" class="rounded-circle icon icon-sm">
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

</main>