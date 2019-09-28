<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

/**
 * Frontend Template
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

    <!-- Meta -->
    <?php include('meta.php') ?>

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

    <!-- Header & Search bar -->
    <?php include('header.php') ?>

    <!--  Page Content -->
    <?php echo $content; ?>
    
    <!-- Footer -->
    <?php include('footer.php') ?>    

    <!-- Javascript global variables -->
    <script type="text/javascript">
        var base_url    = "<?php echo base_url(); ?>";
        var site_url    = "<?php echo site_url(); ?>";
        var uri_seg_1   = "<?php echo $this->uri->segment(1); ?>";
        var uri_seg_2   = "<?php echo $this->uri->segment(2); ?>";
        var uri_seg_3   = "<?php echo $this->uri->segment(3); ?>";
        var csrf_name   = "<?php echo $this->security->get_csrf_token_name(); ?>";
        var csrf_token  = "<?php echo $this->security->get_csrf_hash(); ?>";
        var user_id     = "<?php echo isset($this->user['id']) ? $this->user['id'] : 0; ?>";
        var username    = "<?php echo isset($this->user['username']) ? $this->user['username'] : ''; ?>";
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


    <!-- GDPR -->
    <script type="text/javascript">
        window.addEventListener("load", function(){
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#546cf5",
                        "text": "#ffffff"
                    },
                    "button": {
                        "background": "#172b4d",
                        "text": "#ffffff"
                    }
                },
                "theme": "classic",
                "content": {
                    "dismiss": "<?php echo lang('action_continue') ?>",
                    "link": "<?php echo lang('privacy') ?>",
                    "href": "<?php echo site_url('privacy') ?>",
                }
            })
        });
    </script>


    <!-- Javascript global notifications -->
    <script type="text/javascript">
        $(function() {
            var message     = `<?php echo null !== $this->session->flashdata('message') ? $this->session->flashdata('message') : null ?>`;
            var error       = `<?php echo null !== $this->session->flashdata('error') ? $this->session->flashdata('error') : null ?>`;
            var v_errors    = `<?php echo null !== validation_errors() ? validation_errors() : null ?>`;
            var s_error     = `<?php echo null !== $this->error ? $this->error : null ?>`;
            var info       = `<?php echo null !== $this->session->flashdata('info') ? $this->session->flashdata('info') : null ?>`;

            if(message != '') showToast('success', message);
            if(error != '') showToast('error', error);
            if(v_errors != '') showToast('error', v_errors);
            if(s_error != '') showToast('error', s_error);
            if(info != '') showToast('info', info);
        });
    </script>
</body>
</html>