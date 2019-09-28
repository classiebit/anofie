<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="<?php echo site_url('admin/dashboard') ?>">
            <img src="<?php echo base_url('upload/general/'.$this->settings->logo_thumb); ?>" class="navbar-brand-img mr-1" alt="<?php echo $this->settings->site_name; ?>">
            <span class="h4 text-uppercase"><?php echo $this->settings->site_name; ?></span>
        </a>

        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="<?php echo site_url('admin/dashboard') ?>">
                            <img src="<?php echo base_url('upload/general/'.$this->settings->logo_thumb); ?>">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo (uri_string() == 'admin' OR uri_string() == 'admin/dashboard') ? 'active' : ''; ?>" href="<?php echo site_url('/admin/dashboard'); ?>">
                        <i class="fas fa-columns text-primary"></i> <?php echo lang('menu_dashboard'); ?>
                    </a>
                </li>
                
                <?php if($this->acl->check_access('reports', 'p_view', false, true)) { ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (uri_string() == 'admin/reports') ? 'active' : ''; ?>" href="<?php echo site_url('/admin/reports'); ?>">
                        <i class="fas fa-bug text-red"></i> <?php echo lang('menu_reports'); ?>
                    </a>
                </li>
                <?php } ?>
                
                <?php if($this->acl->check_access('messages', 'p_view', false, true)) { ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (uri_string() == 'admin/messages') ? 'active' : ''; ?>" href="<?php echo site_url('/admin/messages'); ?>">
                        <i class="fas fa-envelope text-blue"></i> <?php echo lang('menu_messages'); ?>
                    </a>
                </li>
                <?php } ?>
                
                <?php if($this->acl->check_access('contacts', 'p_view', false, true)) { ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (uri_string() == 'admin/contacts') ? 'active' : ''; ?>" href="<?php echo site_url('/admin/contacts'); ?>">
                        <i class="fas fa-phone text-green"></i> <?php echo lang('menu_contacts'); ?>
                    </a>
                </li>
                <?php } ?>

                
                
                <?php if($this->acl->check_access('users', 'p_view', false, true)) { ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (uri_string() == 'admin/users') ? 'active' : ''; ?>" href="<?php echo site_url('/admin/users'); ?>">
                        <i class="fas fa-user text-teal"></i> <?php echo lang('menu_users'); ?>
                    </a>
                </li>
                <?php } ?>
                
                <!-- only for admin -->
                <?php if($this->ion_auth->is_admin()) { ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (uri_string() == 'admin/groups') ? 'active' : ''; ?>" href="<?php echo site_url('/admin/groups'); ?>">
                        <i class="fas fa-users text-purple"></i> <?php echo lang('menu_groups'); ?>
                    </a>
                </li>
                <?php } ?>

                <!-- only for admin -->
                <?php if($this->ion_auth->is_admin()) { ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (uri_string() == 'admin/manage_acl') ? 'active' : ''; ?>" href="<?php echo site_url('/admin/manage_acl'); ?>">
                        <i class="fas fa-user-lock text-yellow"></i> <?php echo lang('menu_manage_acl'); ?>
                    </a>
                </li>
                <?php } ?>
                
            </ul>

            <!-- Divider -->
            <hr class="my-3">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted"><?php echo lang('menu_site').' '.lang('menu_administration') ?></h6>

            <?php if($this->acl->check_access('pages', 'p_edit', false, true)) { ?>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo (uri_string() == 'admin/pages') ? 'active' : ''; ?>" href="<?php echo site_url('/admin/pages'); ?>">
                        <i class="fas fa-columns"></i> <?php echo lang('menu_pages'); ?>
                    </a>
                </li>
            </ul>
            <?php } ?>
            
            <?php if($this->acl->check_access('settings', 'p_view', false, true)) { ?>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo (uri_string() == 'admin/settings') ? 'active' : ''; ?>" href="<?php echo site_url('/admin/settings'); ?>">
                        <i class="fas fa-cogs"></i> <?php echo lang('menu_settings'); ?>
                    </a>
                </li>
            </ul>
            <?php } ?>

            <!-- Divider -->
            <hr class="my-3">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted"><?php echo lang('menu_site').' '.lang('menu_webapp') ?></h6>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url(); ?>">
                        <i class="fas fa-desktop"></i> <?php echo lang('menu_access_website'); ?>
                    </a>
                </li>
            </ul>
            
        </div>

    </div>
</nav>