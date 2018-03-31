<?php

/* CLEANUP TASK
/* --------------------- */

namespace Deployer;

require __DIR__ . '/vendor/autoload.php';
require_once 'recipe/common.php';

task('deploy:cleanup', function () {
    $config = get('wp-recipes');

    writeln('<comment>> Cleanin\'up that mess ... !</comment>');

    $targets = $config['clean_after_deploy'];

    foreach ($targets as $element) {
        run('rm -rf {{release_path}}/' . $element);
    }
})->desc('Upload dist assets folder');
