<div class="container-fluid mt-4">
    <!-- Footer -->
    <footer class="footer">
        <div class="row align-items-center justify-content-xl-between">
            <div class="col-xl-6">
                <div class="copyright text-center text-xl-left text-muted">
                    &copy; <?php echo date('Y').' ' ?>
                    <b><a href="<?php echo site_url() ?>"><?php echo $this->settings->site_name; ?></a></b>
                    <br>
                    <small><?php echo lang('product_by') ?> <b><a href="https://www.classiebit.com" target="_blank">Classiebit</a></b></small>
                </div>
            </div>
            <div class="col-xl-6">
                <ul class="nav nav-footer justify-content-center justify-content-xl-end">
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
                    <li class="nav-item">
                        <p><?php echo 'Anofie v<small>'.SITE_VERSION.'</small>'; ?></p>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
</div>