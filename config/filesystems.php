<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        'bus' => [
            'driver' => 's3',
            'key' => env('FS_BUS_KEY'),
            'secret' => env('FS_BUS_SECRET'),
            'region' => env('FS_BUS_REGION', 'nyc3'),
            'bucket' => env('FS_BUS_BUCKET'),
            'endpoint' => env('FS_BUS_ENDPOINT'),
            'root' => env('FS_BUS_ROOT', ''),
            'visibility' => env('FS_BUS_VISIBILITY', 'public'),
            'bucket_endpoint' => env('FS_BUS_BUCKET_ENDPOINT', false),
            'use_path_style_endpoint' => env('FS_BUS_PATH_STYLE_ENDPOINT', true),
        ],

        'cms' => [
            'driver' => 's3',
            'key' => env('FS_CMS_KEY'),
            'secret' => env('FS_CMS_SECRET'),
            'region' => env('FS_CMS_REGION', 'nyc3'),
            'bucket' => env('FS_CMS_BUCKET'),
            'endpoint' => env('FS_CMS_ENDPOINT'),
            'root' => env('FS_CMS_ROOT', '/cms'),
            'visibility' => env('FS_CMS_VISIBILITY', 'public'),
            'bucket_endpoint' => env('FS_CMS_BUCKET_ENDPOINT', false),
            'use_path_style_endpoint' => env('FS_CMS_PATH_STYLE_ENDPOINT', true),
        ],

        'tmp' => [
            'driver' => 's3',
            'key' => env('FS_TMP_KEY'),
            'secret' => env('FS_TMP_SECRET'),
            'region' => env('FS_TMP_REGION', 'nyc3'),
            'bucket' => env('FS_TMP_BUCKET'),
            'endpoint' => env('FS_TMP_ENDPOINT'),
            'root' => env('FS_TMP_ROOT', ''),
            'visibility' => env('FS_TMP_VISIBILITY', 'private'),
            'bucket_endpoint' => env('FS_TMP_BUCKET_ENDPOINT', false),
            'use_path_style_endpoint' => env('FS_TMP_PATH_STYLE_ENDPOINT', true),
        ],

    ],

];
