<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/rsync.php';

// Config

set('application', 'RouteMaster');
set('repository', 'git@github.com:claassenmarius/demo_app_api.git');
set('ssh_multiplexing', true);  // Speed up deployment
set('rsync_src', function () {
    return __DIR__; // If your project isn't in the root, you'll need to change this.
});

// Configuring the rsync exclusions.
// You'll want to exclude anything that you don't want on the production server.
add('rsync', [
    'exclude' => [
        '.git',
        '/vendor/',
        '.github',
        'deploy.php',
    ],
]);
add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Set up a deployer task to copy secrets to the server.
// Grabs the dotenv file from the github secret
task('deploy:secrets', function () {
    file_put_contents(__DIR__ . '/.env', getenv('DOT_ENV'));
    upload('.env', get('deploy_path') . '/shared');
});

// Hosts

host('34.172.94.254')
    ->setHostname('34.172.94.254') // Hostname or IP address
    ->set('remote_user', 'root') // SSH user
    ->set('branch', 'main') // Git branch
    ->set('deploy_path', '/var/www/api');

// Hooks

after('deploy:failed', 'deploy:unlock');

desc('Start of Deploy the application');

task('deploy', [
    'deploy:prepare',
    'rsync',                // Deploy code & built assets
    'deploy:secrets',       // Deploy secrets
    'deploy:vendors',
    'deploy:shared',        //
    'artisan:storage:link', //
    'artisan:view:cache',   //
    'artisan:config:cache', // Laravel specific steps
    'artisan:migrate',      //
    'artisan:queue:restart',//
    'deploy:publish',       //
]);

desc('End of Deploy the application');
