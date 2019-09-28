<footer class="footer">
    <div class="container">
        <div class="row align-items-center justify-content-md-between">
            <div class="col-md-6 mb-1">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link" data-toggle="dropdown" href="#" role="button">
                        <!-- show selected language name -->
                        <?php foreach ($this->languages as $key=>$name) { ?>
                            <?php if ($key == $this->session->language) { ?>
                                <span class="nav-link-inner--text"><i class="fas fa-globe"></i> &nbsp;<?php echo $name ?></span>
                            <?php } ?>
                        <?php } ?>
                    </a>
                    <div class="dropdown-menu" style="height: 400px !important; overflow-y: auto;">
                        <!-- change language on select -->
                        <?php ksort($this->languages); foreach ($this->languages as $key=>$name) { ?>
                        <a class="dropdown-item" href="<?php echo site_url('language/'.$key) ?>" rel="<?php echo $key; ?>"><?php echo $name; ?></a>
                        <?php } ?>
                    </div>
                </li>
            </div>
            <div class="col-md-6 text-lg-right btn-wrapper">
                <?php echo lang('social') ?>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php if($this->settings->social_facebook) { ?>
                <a target="_blank" href="https://www.facebook.com/<?php echo $this->settings->social_facebook ?>" class="btn btn-neutral btn-icon-only btn-facebook btn-round btn-lg" data-toggle="tooltip" data-original-title="<?php echo lang('share_like') ?>">
                    <i class="fab fa-facebook-square"></i>
                </a>
                <?php } ?>

                <?php if($this->settings->social_twitter) { ?>
                <a target="_blank" href="https://twitter.com/<?php echo $this->settings->social_twitter ?>" class="btn btn-neutral btn-icon-only btn-twitter btn-round btn-lg" data-toggle="tooltip" data-original-title="<?php echo lang('share_follow') ?>">
                    <i class="fab fa-twitter-square"></i>
                </a>
                <?php } ?>

                <?php if($this->settings->social_instagram) { ?>
                <a target="_blank" href="<?php echo $this->settings->social_instagram ?>" class="btn btn-neutral btn-icon-only btn-twitter btn-round btn-lg" data-toggle="tooltip" data-original-title="<?php echo lang('share_follow') ?>">
                    <i class="fab fa-instagram"></i>
                </a>
                <?php } ?>
                
                <?php if($this->settings->social_linkedin) { ?>
                <a target="_blank" href="<?php echo $this->settings->social_linkedin ?>" class="btn btn-neutral btn-icon-only btn-twitter btn-round btn-lg" data-toggle="tooltip" data-original-title="<?php echo lang('share_like') ?>">
                    <i class="fab fa-linkedin"></i>
                </a>
                <?php } ?>
            </div>
        </div>

        <hr>
        <div class="row align-items-center justify-content-md-between">
            <div class="col-md-6">
                <ul class="nav nav-footer">
                    <li class="nav-item">
                        <a href="<?php echo site_url('about') ?>" class="nav-link"><?php echo lang('about') ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo site_url('contact') ?>" class="nav-link"><?php echo lang('contact') ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo site_url('terms') ?>" class="nav-link"><?php echo lang('terms') ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo site_url('privacy') ?>" class="nav-link"><?php echo lang('privacy') ?></a>
                    </li>
                </ul>
            </div>
            <div class="col-md-6 text-right">
                <div class="copyright">
                    &copy; <?php echo date('Y').' ' ?>
                    <b><a href="<?php echo site_url() ?>"><?php echo $this->settings->site_name; ?></a></b>
                    <br>
                    <small><?php echo lang('product_by') ?> <b><a href="https://www.classiebit.com" target="_blank">Classiebit</a></b></small>
                </div>
            </div>
            
        </div>
    </div>
</footer>

