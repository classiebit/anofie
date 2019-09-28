<html>
<body>
	<h1><?php echo sprintf(lang('account_creation_successful'), $identity);?></h1>
	<p><?php echo lang('activate_successful').' '.sprintf(lang('menu_welcome'), $title); ?></p>
	<p><?php echo anchor('/', $title); ?></p>
</body>
</html>