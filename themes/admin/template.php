<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Admin Template
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <title><?php echo $page_title; ?> - <?php echo $this->settings->site_name; ?></title>
    
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url('upload/general/'.$this->settings->logo_thumb); ?>">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans:300,400,600,700" rel="stylesheet">

    <!-- Load if RTL -->
    <?php if($this->is_rtl) { ?>
    <link rel="stylesheet" href="<?php echo base_url('themes/core/plugins/bootstrap-rtl/bootstrap-rtl.min.css') ?>">
    <?php } ?>
    
    <!-- include css files  -->
    <?php if (isset($css_files) && is_array($css_files)) : ?>
        <?php foreach ($css_files as $css) : ?>
            <?php if ( ! is_null($css)) : ?>
                <link rel="stylesheet" href="<?php echo $css; ?>?v=<?php echo SITE_VERSION; ?>"><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- End css files  -->

</head>
<body <?php echo $this->is_rtl ? 'dir="rtl"' : ''; ?>>

    <!-- Sidebar -->
    <?php include('sidebar.php') ?>

    <!-- Main content -->
    <div class="main-content">
        <!-- Top Header -->
        <?php include('header.php') ?>

                
        <!-- Exclude in dashboard -->
        <?php if($this->uri->segment(2) != 'dashboard' && $this->uri->segment(2) != '') { ?>
        <!-- Content Header BG Gradient -->
        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-6">
            <div class="container-fluid">
                <div class="header-body">
                    
                    <div class="d-flex">
                        <!-- left -->
                        <div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <?php 
                                        echo !$this->uri->segment(2) ? '<li class="breadcrumb-item active">'.lang('menu_dashboard').'</li>' : '<li class="breadcrumb-item"><a href="'.site_url($this->uri->segment(1).'/dashboard').'">'.lang('menu_dashboard').'</a></li>';

                                        if($this->uri->segment(2))
                                            echo !$this->uri->segment(3) ? '<li class="breadcrumb-item active">'.lang('menu_'.$this->uri->segment(2)).'</li>' : '<li class="breadcrumb-item"><a href="'.site_url('/admin/'.$this->uri->segment(2)).'">'.lang('menu_'.$this->uri->segment(2)).'</a></li>';

                                        if($this->uri->segment(3)) 
                                            echo '<li class="breadcrumb-item active">'.($this->uri->segment(3) !== 'form' ? lang('action_'.$this->uri->segment(3)) : ($this->uri->segment(4) ? lang('action_edit') : lang('action_create'))).'</li>';
                                    ?>
                                </ol>
                            </nav>
                        </div>
                        
                        <!-- right -->
                        <div class="ml-auto">
                            <!-- Ajax validation error -->
                            <div class="alert alert-danger alert-dismissable" id="validation-error" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <p></p>
                            </div>
                                                        
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php } ?>
        

        <!-- Page content start-->
        <?php echo $content; ?>
        <!-- Page content end-->
        
        <!-- FOOTER -->
        <?php include('footer.php') ?>
        
    </div>

    <script type="text/javascript">
        var base_url    = "<?php echo base_url(); ?>";
        var site_url    = "<?php echo site_url(); ?>";
        var uri_seg_1   = "<?php echo $this->uri->segment(1); ?>";
        var uri_seg_2   = "<?php echo $this->uri->segment(2); ?>";
        var uri_seg_3   = "<?php echo $this->uri->segment(3); ?>";
        var csrf_name   = "<?php echo $this->security->get_csrf_token_name(); ?>";
        var csrf_token  = "<?php echo $this->security->get_csrf_hash(); ?>";
    </script>
    
    <!-- js files include -->
    <?php if (isset($js_files) && is_array($js_files)) : ?>
        <?php foreach ($js_files as $js) : ?>
            <?php if ( ! is_null($js)) : ?>
                <?php echo "\n"; ?><script type="text/javascript" src="<?php echo $js; ?>?v=<?php echo SITE_VERSION; ?>"></script><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($js_files_i18n) && is_array($js_files_i18n)) : ?>
        <?php foreach ($js_files_i18n as $js) : ?>
            <?php if ( ! is_null($js)) : ?>
                <?php echo "\n"; ?><script type="text/javascript"><?php echo "\n" . $js . "\n"; ?></script><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- End js files  -->

    <script>
        /*System Notification*/
        $(function() {
            var message     = `<?php echo null !== $this->session->flashdata('message') ? $this->session->flashdata('message') : null ?>`;
            var error       = `<?php echo null !== $this->session->flashdata('error') ? $this->session->flashdata('error') : null ?>`;
            var v_errors    = `<?php echo null !== validation_errors() ? validation_errors() : null ?>`;
            var s_error     = `<?php echo null !== $this->error ? $this->error : null ?>`;

            if(message != '') showToast('success', message);
            if(error != '') showToast('error', error);
            if(v_errors != '') showToast('error', v_errors);
            if(s_error != '') showToast('error', s_error);
        });
    </script>
      
</body>
</html>