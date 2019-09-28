<?php

$domain = strtolower($_SERVER['SERVER_NAME']);
if (strpos($domain, 'anofie.com') !== FALSE || strpos($domain, 'classiebit.com') !== FALSE)
{
    define('DEMO_MODE', 1);
    define('INSTALL_MODE', 0);
}
else
{
    define('DEMO_MODE', 0);
    define('INSTALL_MODE', 1);
}

// Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.
error_reporting(0); 

$db_config_path = '../application/config/production/database.php';

// Only load the classes in case the user submitted the form
if($_POST && INSTALL_MODE === 1) {

	// Load the classes and create the new objects
	require_once('includes/Core.php');
	require_once('includes/Database.php');

	$core = new Core();
	$database = new Database();

	// Validate the post data
	if($core->validate_post($_POST) == true)
	{
		$redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
		$redir .= "://".$_SERVER['HTTP_HOST'];
		$redir .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
		$redir = str_replace('install/','',$redir);

		// First  create the database, then create tables, then write config file
        /**
         * 1. Create database
         * 2. Transfer upload data
         * 3. Write config.php
         * 4. Write database.php
         * 5. Create database tables and add data
          */
        if($database->create_database($_POST) == false) 
        {
			$message = $core->show_message('error',"The database could not be created, please verify your settings.");
		} 
        else if($core->transfer_files($_POST['sample_data']) == false) 
        {
            $message = $core->show_message('error', "Uploads files could not be transferred, please make sure you have set correct write permissions to upload directory.");
        }
		else if ($core->write_config($_POST) == false) 
        {
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod application/config/development/database.php file to 777");
		} 
        else if ($core->write_config_2($_POST) == false) 
        {
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod application/config/production/database.php file to 777");
		} 
        else if ($core->write_config_3($redir) == false) 
        {
			$message = $core->show_message('error',"The config file could not be written, please chmod application/config/development/config.php file to 777");
		} 
        else if ($core->write_config_4($redir) == false) 
        {
			$message = $core->show_message('error',"The config file could not be written, please chmod application/config/production/config.php file to 777");
		}
        else if ($database->create_tables($_POST) == false) 
        {
			$message = $core->show_message('error',"The database tables could not be created, please verify your settings.");
		} 

		// If no errors, redirect to registration page
		if(!isset($message)) {
	      	header( 'Location: ' . $redir ) ;
		}
	}
	else 
    {
		$message = $core->show_message('error','Please fill in &nbsp;&nbsp;(required) fields');
	}
}

?>
<!doctype html>
<html lang="en">
<head>
    <title>Anofie Lite | Installer</title>
    <!-- &nbsp;&nbsp;(required) meta tags -->
    <meta charset="utf-8">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    
    <style type="text/css">
    	body {
		  padding-top: 40px;
		  padding-bottom: 40px;
		  background-color: #eee;
		}
        .container {
            padding-top: 15px;
            padding-bottom: 15px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 6px 0px 26px 0 rgba(0,0,0,.1);
            width: 100%;
            display: table;
            min-height: 84vh;
            overflow: hidden;
        }
        .card-heading {
            background-image: url(http://anofie.com/themes/default/img/anofie-installer.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position-x: left;
            display: table-cell;
            width: 50%;
            position: relative;
        }
        .card-body {
            padding-bottom: 60px;
        }
        .instructions {
            color: #fff;
            background: rgba(0, 0, 0, 0.5);
            height: 150px;
        }
        .instructions h2 {
            padding-top: 7%;
        }
        footer {
            position: absolute;
            bottom: 0;
            left: 5%;
            color: #fff;
            font-weight: 500;
            font-size: 18px;
        }
        a {
            text-decoration: none !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-heading">
                    <div class="instructions">
                        <h2 class="text-center"><small style="font-size: 12px !important;font-weight: 600 !important;">V1.0</small> Anofie Lite | Installer</h2>
                        <p style="text-align: center;font-size: 14px;"><strong><em>IMPORTANT! </em> Please read the installation guide in <a href="http://anofie-docs.classiebit.com" target="_blank"></a>Anofie docs.</strong></p>
                        
                    </div>
                    <footer>
                        <div>
                        <p> <a href="https://classiebit.com/">product by Classiebit</a></p>
                        </div>
                    </footer>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="offset-md-2 col-md-8">
                            
                            <?php if(is_writable($db_config_path)) { ?>

                            <?php if(isset($message)) {echo '<div class="alert alert-danger alert-dismissible fade show"  role="alert"><strong>'.$message.'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button></div>';} ?>

                            <?php if(INSTALL_MODE === 0) {echo '<div class="alert alert-info fade show"  role="alert"><strong>Currently in demo mode, nothing will work.</strong></div>';} ?>

                            <form class="form-signin" id="install_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <div class="form-group">
                                    <label for="hostname">Hostname<small >&nbsp;&nbsp;(required)</small></label>
                                    <input type="text" id="hostname" placeholder="e.g localhost" class="form-control form-control-lg" name="hostname" aria-describedby="hostnameHelp" autocomplete="false" &nbsp;&nbsp;(required)="" />
                                    <small id="hostnameHelp" class="form-text text-muted">Enter Mysql hostname.</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username">Username<small >&nbsp;&nbsp;(required)</small></label>
                                    <input type="text" id="username" placeholder="e.g root" class="form-control form-control-lg" name="username" aria-describedby="usernameHelp" autocomplete="false" &nbsp;&nbsp;(required)=""/>
                                    <small id="usernameHelp" class="form-text text-muted">Enter Mysql username.</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" placeholder="e.g *******" class="form-control form-control-lg" name="password" aria-describedby="passwordHelp" autocomplete="false"/>
                                    <small id="passwordHelp" class="form-text text-muted">Enter Mysql password</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="database">Database<small >&nbsp;&nbsp;(required)</small></label>
                                    <input type="text" id="database" placeholder="e.g anonym_db" class="form-control form-control-lg" name="database" aria-describedby="databaseHelp" autocomplete="false"/>
                                    <small id="databaseHelp" class="form-text text-muted">Enter new database name.</small>
                                </div>

                                <div class="custom-control custom-radio">
                                    <input type="radio" id="sample_data_no" name="sample_data" class="custom-control-input" value="0" checked>
                                    <label class="custom-control-label" for="sample_data_no">Install without dummy data</label>
                                </div>

                                <div class="custom-control custom-radio">
                                    <input type="radio" id="sample_data_yes" name="sample_data" class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="sample_data_yes">Install with dummy data</label>
                                </div>
                                <br>
                                <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block">Install</button>

                            </form>
                            <?php } else { ?>
                            <br><br><br><br>
                            <div class="alert alert-danger" role="alert"><strong>Please make application/config/production and application/config/development folder writable. </strong></div>
                                <strong>Try</strong>:<br>
                                <code>chmod -R 775 application/config/production</code><br>
                                <code>chmod -R 775 application/config/development</code>
                            
                            <?php } ?>
                            
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

    
</body>
</html>