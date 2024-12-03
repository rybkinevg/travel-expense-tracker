<?php

declare(strict_types=1);

namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:rybkinevg/travel-expense-tracker.git');

set('default_timeout', 3600);
set('keep_releases', 2);
set('release_name', static fn() => date('Y-m-d-His'));

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('development')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '/var/www/projects/travel-expense-tracker')
    ->set('php_version', '8.3')
;

// Hooks

after('deploy:failed', 'deploy:unlock');
