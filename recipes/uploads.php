<?php

/* WP UPLOADS TASK
/* --------------------- */

namespace Deployer;

require __DIR__ . '/vendor/autoload.php';
require_once 'recipe/common.php';

task('uploads:push', function () {
    $upload_dir = 'web/app/uploads';
    $rsync_options = '-avzO --no-o --no-g -e --delete';

    writeln('<comment>> Send local uploads ... </comment>');
    upload($upload_dir . '/', '{{deploy_path}}/shared/' . $upload_dir, [$rsync_options]);
})->desc('Sync uploads');

task('uploads:pull', function () {
    $upload_dir = 'web/app/uploads';
    $rsync_options = '-avzO --no-o --no-g -e --delete';

    writeln('<comment>> Receive remote uploads ... </comment>');
    download('{{deploy_path}}/shared/' . $upload_dir . '/', $upload_dir, [$rsync_options]);
})->desc('Sync uploads');
