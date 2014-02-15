<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;

if (is_file(FUSIONPATH.'core'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Kohana'.EXT))
{
	// Load FusionFramework core extension
	require FUSIONPATH.'core'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/Kohana'.EXT;
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('America/Chicago');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable composer support
 */
include_once APPPATH . 'vendor/autoload.php';

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
//spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

/**
 * Set the mb_substitute_character to "none"
 *
 * @link http://www.php.net/manual/function.mb-substitute-character.php
 */
mb_substitute_character('none');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-us');

if (isset($_SERVER['SERVER_PROTOCOL']))
{
	// Replace the default protocol.
	HTTP::$protocol = $_SERVER['SERVER_PROTOCOL'];
}

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
	'base_url'   => (DIRECTORY_SEPARATOR == '/' ? dirname($_SERVER['SCRIPT_NAME']): str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']))),
	'index_file' => FALSE,
	'profile'    => Kohana::$environment !== Kohana::PRODUCTION,
	'caching'    => Kohana::$environment === Kohana::PRODUCTION,
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Cookie
 */
Cookie::$salt = 'salt';

/**
 * Session
 */
Session::$default = 'database';

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array (
	// fusionFramework modules
	'economy'               => FUSIONPATH.'economy',
	'social'                => FUSIONPATH.'social',
	'pets'                  => FUSIONPATH.'pets',
	'admin'                 => FUSIONPATH.'admin',
	'user'                  => FUSIONPATH.'user',
	'core'                  => FUSIONPATH.'core',
	// Kohana modules
	'purifier'              => MODPATH.'purifier',   // Protection against XSS attacks
	'data-table'            => MODPATH.'data-table', // dataTable preparer
	'kohana-datatables'     => MODPATH.'kohana-datatables', //dataTable request helper
	'kohana-paginate'       => MODPATH.'kohana-paginate', // Pagination helper
	'arr'                   => MODPATH.'arr',        // Array helper
	'kostache'              => MODPATH.'kostache',   // Template engine
	'elements'              => MODPATH.'elements',   // Navigation element manager
	'kohana-plugin-system'  => MODPATH.'kohana-plugin-system', // Plugin/event system
	'quill'                 => MODPATH.'quill',      // Portable discussion helper
	'req'                   => MODPATH.'req',        // Handles flash messages (even over ajax)
	'tasks-migrations'      => MODPATH.'tasks-migrations', //Migration task runner
	'datalog'               => MODPATH.'kohana_datalog', // ORM record revision tracker
	'txt'                   => MODPATH.'txt',        // Text helpers for Sentry
	'formo'                 => MODPATH.'kohana-formo', //Form generation
	'cache'                 => MODPATH.'cache',      // Caching with multiple backends
	'database'              => MODPATH.'database',   // Database access
	'minion'                => MODPATH.'minion',     // CLI Tasks
	'orm'                   => MODPATH.'orm',        // Object Relationship Mapping
	'userguide'             => MODPATH.'userguide',  // User guide and API documentation
	)
);

// Initialise the Fusion helper
Fusion::init();

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('default', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'welcome',
		'action'     => 'index',
	)
);
