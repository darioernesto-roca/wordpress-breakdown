# Request lifecycle and hooks

Understanding when WordPress does work makes hooks easier to choose and avoids
queries or redirects at the wrong time.

## Simplified front-end request

1. The web server sends the request through `index.php` and `wp-blog-header.php`.
2. `wp-load.php` reads configuration and starts the bootstrap.
3. Must-use plugins, regular plugins, and the active theme are loaded.
4. WordPress parses the URL and creates the main `WP_Query`.
5. The template loader applies the template hierarchy.
6. The theme renders markup; WordPress sends the response.

Common milestones include `plugins_loaded`, `setup_theme`, `after_setup_theme`,
`init`, `wp`, `template_redirect`, `wp_enqueue_scripts`, and `wp_footer`. Do not
rely only on memorized order: consult the hook's current official documentation
and choose the narrowest hook that matches the job.

## Actions and filters

- An **action** performs work and does not replace a value.
- A **filter** receives a value and must return the filtered value.
- A callback's priority controls relative order; lower numbers run earlier.
- The accepted-arguments value must match how many hook arguments are needed.

```php
add_filter( 'excerpt_length', 'rocadev_excerpt_length', 20 );

function rocadev_excerpt_length( $length ) {
	return 24;
}
```

Avoid anonymous callbacks when another component may need to remove the hook.
Prefix functions, classes, options, handles, cron events, REST namespaces, and
nonces to prevent collisions.

## Template hierarchy

WordPress selects the most specific matching template and falls back toward
`index.php`. For a single `book` post, for example, candidates include
`single-book.php`, `single.php`, and `index.php`. Block themes use HTML templates
under `templates/`; classic themes use PHP templates. Query conditionals are
reliable after the main query is built, not during early bootstrap hooks.
