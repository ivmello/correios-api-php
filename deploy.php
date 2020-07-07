<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'correios-api-php');

// Project repository
set('repository', 'https://github.com/ivmello/correios-api-php.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server 
set('writable_dirs', []);


// Hosts
host('178.128.159.237')
    ->user('root')
    ->identityFile('~/.ssh/id_rsa')
    ->set('deploy_path', '/var/www/correios');

// Tasks

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
