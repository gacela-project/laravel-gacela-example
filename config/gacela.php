<?php

declare(strict_types=1);

/*
 * What `config/gacela.php` may say. These are the defaults; publish the file
 * with `artisan vendor:publish --tag=gacela-config` and edit what differs.
 *
 * An unknown key fails boot naming the key -- Laravel has no compile step
 * where a validated tree could catch it, so the provider checks here, where it
 * is a five-second fix, instead of at the first use of whatever the key was
 * supposed to configure.
 */
return [
    // Bootstrap Gacela when the application boots.
    'enabled' => true,

    // The directory holding gacela.php. Null defaults to base_path().
    'app_root_dir' => null,

    // Where Gacela writes its caches. Null leaves Gacela's own default in place,
    // which is the *system temp directory* -- not somewhere under the app. On a
    // deploy point this at storage: 'cache_dir' => storage_path('framework/gacela').
    'cache_dir' => null,

    // Enable the on-disk resolution cache. Null leaves Gacela's own default in
    // place (off). Turn it on in production and `artisan optimize` writes the
    // resolution cache alongside Laravel's own; `optimize:clear` removes it.
    // Left off here so an example never serves a stale module resolution.
    'file_cache' => null,

    // Namespaces Gacela scans for modules.
    'project_namespaces' => [],

    // Gacela external-service key => Laravel binding id, reachable from a Factory.
    'external_services' => [],

    // Add Gacela's console commands to artisan.
    'register_commands' => true,

    // Prefix for those commands. Required: `make:*` would otherwise collide with artisan's own.
    'command_prefix' => 'gacela:',
];
