# WordPress Breakdown

A practical, growing knowledge base for learning WordPress development,
operations, and security. The repository combines focused Markdown notes with
small, installable examples that show how WordPress themes and plugins are
structured.

> **Learning repository:** review and adapt every example before using it on a
> production site. Never edit WordPress core to implement site functionality.

## Start here

1. Use the [study checklist](docs/study-checklist.md) to track your progress.
2. Read [how WordPress is structured](docs/fundamentals/application-structure.md).
3. Follow the [local development workflow](docs/good-practices/development-workflow.md).
4. Choose the [theme](docs/themes/building-a-theme.md) or
   [plugin](docs/plugins/building-a-plugin.md) learning path.
5. Install the corresponding project under [`practice/`](practice/README.md) in
   a disposable local WordPress site.
6. Use the [security checklist](docs/security/hardening-and-vulnerabilities.md)
   before treating any example as production-ready.

## Repository map

| Path | Purpose |
| --- | --- |
| [`docs/fundamentals/`](docs/fundamentals/) | Application structure, database, and request lifecycle |
| [`docs/themes/`](docs/themes/) | Classic/block themes, templates, assets, and customization |
| [`docs/plugins/`](docs/plugins/) | Plugin architecture, hooks, data, activation, and cleanup |
| [`docs/operations/`](docs/operations/) | WP-Cron, debugging, deployment, backups, and maintenance |
| [`docs/security/`](docs/security/) | Threats, secure coding patterns, and incident basics |
| [`docs/good-practices/`](docs/good-practices/) | Tooling, code review, compatibility, and daily workflow |
| [`notes/`](notes/) | Short observations, tool evaluations, and reusable note template |
| [`practice/`](practice/) | Installable theme and plugin projects for experimentation |
| [`examples/`](examples/) | Standalone, topic-specific snippets from the original breakdown |
| [`wordpress-breakdown-with-examples.php`](wordpress-breakdown-with-examples.php) | Original long-form reference with commented examples |

## Learning paths

### Theme developer

- Learn the [request lifecycle](docs/fundamentals/request-lifecycle.md) and
  template hierarchy.
- Read [Building a theme](docs/themes/building-a-theme.md).
- Explore and activate [`practice/themes/learning-classic`](practice/themes/learning-classic/).
- Compare it with the block-theme files in `examples/`.

### Plugin developer

- Review [hooks and extension points](docs/plugins/building-a-plugin.md#hooks-first-design).
- Explore and activate [`practice/plugins/site-notes`](practice/plugins/site-notes/).
- Compare focused implementations in `examples/`.
- Add automated tests before growing a plugin beyond an exercise.

### Site maintainer / security learner

- Work through [WP-Cron and scheduled tasks](docs/operations/wp-cron.md).
- Follow the [operations runbook](docs/operations/debugging-and-deployment.md).
- Use the [hardening and vulnerabilities guide](docs/security/hardening-and-vulnerabilities.md).

## Adding your own knowledge

Copy [`notes/NOTE-TEMPLATE.md`](notes/NOTE-TEMPLATE.md), give the file a clear
kebab-case name, link to primary documentation, and distinguish verified facts
from personal observations. Keep runnable code in `practice/` or `examples/`
rather than hiding substantial implementations inside prose.

See [CONTRIBUTING.md](CONTRIBUTING.md) for review and validation steps.
