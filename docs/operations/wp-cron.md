# WP-Cron and scheduled tasks

WP-Cron is a request-driven scheduler, not a continuously running system daemon.
On a low-traffic site an event can run late; on a busy site overlapping requests
can expose race conditions. Tasks must therefore be safe to retry and should
process bounded batches.

## Register and clean up an event

```php
register_activation_hook( __FILE__, 'rocadev_activate' );
register_deactivation_hook( __FILE__, 'rocadev_deactivate' );
add_action( 'rocadev_daily_cleanup', 'rocadev_daily_cleanup' );

function rocadev_activate() {
	if ( ! wp_next_scheduled( 'rocadev_daily_cleanup' ) ) {
		wp_schedule_event( time(), 'daily', 'rocadev_daily_cleanup' );
	}
}

function rocadev_deactivate() {
	wp_clear_scheduled_hook( 'rocadev_daily_cleanup' );
}
```

Do not schedule on every request without `wp_next_scheduled()`. When an event has
arguments, supply the same arguments when checking or unscheduling it.

## Production scheduling

For predictable execution, hosts commonly disable request spawning with
`DISABLE_WP_CRON` and invoke WordPress cron from a real scheduler. Confirm the
host's recommended command and interval. Do not disable spawning until the
replacement is verified, or queued work will stop.

Useful WP-CLI diagnostics:

```bash
wp cron event list
wp cron event run rocadev_daily_cleanup
wp cron test
```

## Reliable job design

- Record progress and operate on a limited batch.
- Acquire a lock with an expiration when overlap would be harmful.
- Log identifiers and outcomes, never secrets or unnecessary personal data.
- Set timeouts for network calls and retry only transient failures with backoff.
- Measure runtime and memory; hand long or critical work to a queue/worker system.
- Remember that deleting a transient is not a perfect distributed lock on every
  object-cache backend; select a lock suited to the infrastructure.
