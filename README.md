# Deployer WP Recipes

These recipes were originally written by [cstaelen](https://github.com/cstaelen), using his personal WordPress stack based on [Bedrock](https://roots.io/bedrock/), using [phpdotenv](https://github.com/vlucas/phpdotenv). This fork is maintained by [danielroe](https://github.com/danielroe).

## Features

* Deploy repository code as well as gulp or webpack compiled files
* Sync and push Wordpress uploads
* Pull and push Wordpress database
* Clean up some files

## Requirements

* [Deployer PHP](http://deployer.org/)
* [WP CLI](https://wp-cli.org/)
* [phpdotenv](https://github.com/vlucas/phpdotenv) (optional)

## Installation

Add this repo url to `composer.json` first ([info](https://gemfury.com/help/php-composer-server)). Then run `composer require jcchikikomori/deployer-wp-recipes`.

If you're using phpdotenv, run `composer require vlucas/phpdotenv`.

Make sure to include recipe files in your `deploy.php`:

    require 'vendor/danielroe/deployer-wp-recipes/recipes/assets.php';
    require 'vendor/danielroe/deployer-wp-recipes/recipes/cleanup.php';
    require 'vendor/danielroe/deployer-wp-recipes/recipes/db.php';
    require 'vendor/danielroe/deployer-wp-recipes/recipes/uploads.php';
    # Include if you're using phpdotenv
    # require 'vendor/vlucas/phpdotenv/src/Dotenv.php';

## Configuration

Just add those lines below in your `deploy.php` with your own values :

    set('wp-recipes', [
        'theme_name'        => 'Your WP theme folder name',
        'theme_dir'         => '/web/app/themes',
        'shared_dir'        => '{{ deploy_path }}/shared',
        'assets_cmd'          => 'yarn && yarn run build:production',
        'assets_dist'       => '/web/app/themes/dist',
        'local_wp_url'      => 'http://mysite.test',
        'remote_wp_url'     => 'http://mysite.com',
        'clean_after_deploy'=>  [
            'deploy.php',
            '.gitignore',
            '*.md'
        ]
    ]);

## Available tasks

Upload your WP database : `dep db:push prod`
Download your WP database : `dep db:pull prod`
Pull WP uploads with rsync : `dep uploads:pull prod`
Push WP uploads with rsync : `dep uploads:push prod`
Upload your local copy of WP uploads with rsync : `dep uploads:push prod`

You can also use those rules below in your `deploy.php` file to compile and deploy assets and cleanup some useless files on your staging/production server :

    after('deploy', 'deploy:cleanup');

You can use `deploy:assets` as part of your deploy process. For example:

    task('deploy', [
        'deploy:prepare',
        'deploy:lock',
        'deploy:release',
        'deploy:update_code',
        'deploy:shared',
        'deploy:vendors',
        'deploy:assets',
        'deploy:writable',
        'deploy:symlink',
        'deploy:unlock',
        'cleanup',
        'varnish:reload',
        'php-fpm:reload',
    ])->desc('Deploy your Bedrock project');
    after('deploy', 'success');

## WP recipes using phpdotenv

If you are using **phpdotenv** to configure your servers as Bedrock does, you can use these task rules below to grab `WP_HOME` value filled in your .env file.

    before('db:cmd:pull', 'env:uri');
    before('db:cmd:push', 'env:uri');

_In order to do that, we assume your local .env file is in the root project folder, and the remote one in the repo shared folder._

Make sure to leave `local_wp_url` and `remote_wp_url` empty:

    set('wp-recipes', [
        ...
        'local_wp_url' => '',
        'remote_wp_url' => ''
        ...
    ]);
