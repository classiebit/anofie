<!-- General Meta -->
<meta name="title" content="<?php echo $this->meta_title; ?>">
<meta name="keywords" content="<?php echo $this->meta_tags; ?>">
<meta name="description" content="<?php echo $this->meta_description; ?>">
<meta name="image" content="<?php echo $this->meta_image; ?>" >
<meta name="url" content="<?php echo $this->meta_url; ?>" >
<meta name="author" content="<?php echo $this->settings->site_name; ?>">

<!-- Facebook Meta -->
<meta property="og:url"           content="<?php echo $this->meta_url; ?>" />
<meta property="og:type"          content="article" />
<meta property="og:title"         content="<?php echo $this->meta_title; ?>" />
<meta property="og:description"   content="<?php echo $this->meta_description; ?>" />
<meta property="og:image"         content="<?php echo $this->meta_image; ?>" />
<meta property="og:image:width"   content="300" />
<meta property="og:image:height"  content="200" />

<!-- Twitter Meta -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@<?php echo $this->settings->social_twitter ?>" />
<meta name="twitter:creator" content="@<?php echo $this->settings->social_twitter ?>" />
<meta name="twitter:title" content="<?php echo $this->meta_title; ?>">
<meta property="twitter:description" content="<?php echo $this->meta_description; ?>" />

<!-- Google Analytics -->
<?php if((ENVIRONMENT === 'production' || ENVIRONMENT === 'live')  && !empty($this->settings->g_analytic)) { ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $this->settings->g_analytic ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo $this->settings->g_analytic ?>');
</script>
<?php } ?>