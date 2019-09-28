<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<main class="profile-page" id="send-message">

<!-- Profile header background -->
<section class="section-profile-cover section-shaped my-0">
    <!-- Circles background -->
    <div class="shape shape-style-1 shape-dark alpha-4">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <!-- SVG separator -->
    <div class="separator separator-bottom">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="seperator-polygon-color" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</section>

<section class="section section-components">
    <div class="container">
        <div class="mt--300">
            <div class="px-4">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card shadow">
                                <div class="card-header">
                                <div class="d-flex">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                            </div>
                            <div class="card-body"><?php echo $content; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</main>