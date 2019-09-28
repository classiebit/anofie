<header class="header-global">
    <nav id="navbar-main" class="navbar navbar-main navbar-expand-lg navbar-transparent navbar-light headroom">
        <div class="container">
            <a class="navbar-brand mr-lg-5" href="<?php echo site_url(); ?>">
                <img src="<?php echo base_url('upload/general/'.$this->settings->logo_thumb); ?>" class="mr-2" alt="<?php echo $this->settings->site_name; ?>" style="height: 50px !important;"> <?php echo $this->settings->site_name; ?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbar_global">
                <div class="navbar-collapse-header">
                    <div class="row">
                        <div class="col-6 collapse-brand">
                            <a href="<?php echo site_url(); ?>"><img src="<?php echo base_url('upload/general/'.$this->settings->logo_thumb); ?>"></a>
                        </div>
                        <div class="col-6 collapse-close">
                            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <ul class="navbar-nav navbar-nav-hover align-items-lg-center">

                    <?php if($this->session->userdata('logged_in')) { ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url('messages'); ?>" class="btn btn-primary btn-icon btn-block mb-2 mb-sm-0">
                            <span class="btn-inner--icon">
                                <i class="fas fa-envelope mr-2"></i>
                            </span>
                            <span class="nav-link-inner--text"><?php echo lang('menu_messages') ?>
                                <?php if(count($this->notifications)) { 
                                        $not_count = 0;
                                        foreach($this->notifications as $val) {
                                            if($val->n_type === 'messages') {
                                                $not_count += $val->total;
                                            }
                                        }

                                        if($not_count)
                                        echo '<i class="nav-link-notification">'.$not_count.'</i>';
                                } ?>
                            </span>
                        </a>
                    </li>
                    <?php } ?>
                
                    
                    <!-- search bar call -->
                    <li class="nav-item">
                        
                        <a class="nav-link nav-link-icon js-search btn btn-block mb-2 mb-sm-0" href="javascript:void(0);" data-close="true">
                            <i class="fas fa-search"></i>
                            <span class="nav-link-inner--text"><?php echo lang('action_search') ?></span>
                        </a>
                        
                    </li>

                </ul>

                <ul class="navbar-nav navbar-nav-hover align-items-lg-center ml-lg-auto">

                    <?php if(! $this->session->userdata('logged_in')) { ?>

                        <?php if(DEMO_MODE === 1) { ?>
                        <li class="nav-item">
                            <a href="https://github.com/classiebit/anofie" class="btn btn-neutral btn-block mb-2 mb-sm-0" target="_blank">
                                <span class="btn-inner--icon text-default"><i class="fab fa-github fa-2x"></i></span>
                            </a>
                        </li> 
                        <?php } ?>

                        <?php if($this->uri->segment(1) || isset($on_subdomain)) { ?>
                        <li class="nav-item">
                            <a href="<?php echo site_url(); ?>" class="btn btn-primary btn-block mb-2 mb-sm-0">
                                <span class="nav-link-inner--text"><?php echo lang('action_login') ?></span>
                            </a>
                        </li> 
                        <?php } else { ?>
                        <li class="nav-item">
                            <button class="btn btn-primary btn-block mb-2 mb-sm-0" onclick="toggleCard()" >
                                <span class="nav-link-inner--text login-signup-toggle"><?php echo lang('action_register') ?></span>
                            </button>
                        </li> 
                        <?php } ?>
                    
                    <?php } ?>

                    <?php if($this->session->userdata('logged_in')) { ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link nav-link-icon nav-link-icon--profile" data-toggle="dropdown" href="#" role="button">
                            <img src="<?php echo $this->user['image'] ? base_url('upload/users/images/').image_to_thumb($this->user['image']) : base_url('themes/default/img/avatar.png'); ?>" class="rounded-circle">
                            <span class="nav-link-inner--text"><?php echo $this->user['fullname'] ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg">
                            <div class="dropdown-menu-inner">
                                <a href="<?php echo site_url('settings') ?>" class="media d-flex align-items-center">
                                    <div class="icon icon-shape bg-gradient-primary rounded-circle text-white">
                                        <i class="fas fa-cog fa-2x"></i>
                                    </div>
                                    <div class="media-body ml-3">
                                        <h6 class="heading text-primary mb-md-1"><?php echo lang('menu_settings') ?></h6>
                                        <p class="description d-none d-md-inline-block mb-0"><?php echo lang('settings_manage') ?></p>
                                    </div>
                                </a>
                                <a href="<?php echo site_url('logout') ?>" class="media d-flex align-items-center">
                                    <div class="icon icon-shape bg-gradient-success rounded-circle text-white">
                                        <i class="fas fa-sign-out-alt fa-2x"></i>
                                    </div>
                                    <div class="media-body ml-3">
                                        <h6 class="heading text-primary mb-md-1"><?php echo lang('action_logout') ?></h6>
                                        <p class="description d-none d-md-inline-block mb-0"><?php echo lang('settings_see_you') ?></p>
                                    </div>
                                </a>

                                <?php if ($_SESSION['groups_id'] != 3) { ?>
                                <div class="dropdown-divider"></div>
                                <a href="<?php echo site_url('admin/dashboard'); ?>" class="media d-flex align-items-center">
                                    <div class="icon icon-shape bg-gradient-warning rounded-circle text-white">
                                        <i class="fas fa-tachometer-alt fa-2x"></i>
                                    </div>
                                    <div class="media-body ml-3">
                                        <h6 class="heading text-primary mb-md-1"><?php echo lang('menu_admin'); ?></h6>
                                        <p class="description d-none d-md-inline-block mb-0"><?php echo lang('settings_admin_panel') ?></p>
                                    </div>
                                </a>
                                <?php } ?>
                                
                            </div>
                        </div>
                    </li>
                    <?php } ?>

                </ul>
                
            </div>
        </div>
    </nav>
</header>

<!-- Search Bar -->
<div class="search-bar">
    <div class="search-icon">
        <i class="fas fa-search"></i>
    </div>
    <input type="text" oninput="searchUser(this)" placeholder="<?php echo strtolower(lang('action_search').' '.lang('users_name').'/'.lang('users_username')); ?>...">

    <div class="close-search">
        <i class="fas fa-times"></i>
    </div>
    
    <!-- ajax list -->
    <div class="user-search list-group" id="list-users"></div>
</div>
<!-- #END# Search Bar -->