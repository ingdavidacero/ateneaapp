# Description

The purpose of this project is to show the structure of an API for questions like "Who wants to be a millionaire?".

# PHP + Mysql

The project structure is created from scratch with PHP (-v 7.2.6) code. Mysql is also used as a database, which you can consult in the database folder.

## Project setup

In order to run this project, certain folders and files must be created that I omit due to server configurations.

### Settings file

In the /classes/base folder you must create a settings.class.php file with the following code:

class Settings{
	
    public static $database = [
		'host' => '', //database host
		'user' => '', //username to access the database
		'password' => '', //database password with the user typed
		'name' => '', //database name
		'port' => '' //database port
	];

	public static $paths = [
		'logs'=>'' //Exact path where the log file will be hosted.
	];

	public static $files = [
		'logs' => [
			'system' => '' //Log file name .log
		],
	];
}

### Logs folder

A folder must be created to host the logs, which was configured in the Settings file.
