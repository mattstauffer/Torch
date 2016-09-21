# <img src="torch-logo.png" alt="Torch Logo" align="right">Torch - Using Laravel's Illuminate Components Independently

Torch is a project to provide instructions and examples for using Illuminate components as standalone components in non-Laravel applications. The current `master` branch shows how to use Illuminate's `5.1` components.

**Note**: If you are working with an older project, you might have more success using the [4.2 components](https://github.com/mattstauffer/torch/tree/4.2).

## Usage
At the moment, the project is divided into many directories beneath `components` which will each contain an index file, usually written with [Slim](http://www.slimframework.com/). Navigate to that directory in your terminal and run the following to serve a web site from that directory:

```bash
$ composer install
$ php -S localhost:8000
```

Now you can visit [http://localhost:8000/](http://localhost:8000/) in your browser to view the output of each.

## Packages

### Done
 * [Cache](https://github.com/mattstauffer/Torch/tree/master/components/cache)
 * [Config](https://github.com/mattstauffer/Torch/tree/master/components/config)
 * [Container](https://github.com/mattstauffer/Torch/tree/master/components/container)
 * [Database](https://github.com/mattstauffer/Torch/tree/master/components/database)
 * [Encryption](https://github.com/mattstauffer/Torch/tree/master/components/encryption)
 * [Routing](https://github.com/mattstauffer/Torch/tree/master/components/routing)
 * [Translation](https://github.com/mattstauffer/Torch/tree/master/components/translation)
 * [Session](https://github.com/mattstauffer/Torch/tree/master/components/session)
 * [Support](https://github.com/mattstauffer/Torch/tree/master/components/support)
 * [Validation](https://github.com/mattstauffer/Torch/tree/master/components/validation)
 * [Events](https://github.com/mattstauffer/Torch/tree/master/components/events)
 * [View](https://github.com/mattstauffer/Torch/tree/master/components/view)
 * [Pagination](https://github.com/mattstauffer/Torch/tree/master/components/pagination)
 * [Log](https://github.com/mattstauffer/Torch/tree/master/components/log)
 * [Middleware](https://github.com/mattstauffer/Torch/tree/master/components/middleware)

### In Progress
 * [Mail](https://github.com/mattstauffer/Torch/tree/master/components/mail) - Imported from 4.2 but needs to be tested/tweaked
 * [Queue](https://github.com/mattstauffer/Torch/tree/master/components/queue) - Imported from 4.2 but needs to be tested/tweaked

### Planned
 * Artisan - [Work In Progress PR](https://github.com/mattstauffer/Torch/pull/22)
 * Logging & Errors
 * More?

## Contributing
A few important notes:

 1. The imagined end user is a developer of *any* Symfony-HttpFoundation-using project copying the route closure directly into a project, so try to avoid using any Slim conventions and use as little preparation code outside the closure as possible.
 2. While some components would be *easier* to implement with a Laravel-style Application instance and a fuller bootstrap, I'd prefer we implement as many as possible *without* loading Laravel's Service Providers.
 3. Some components will require a bootstrap, and I hope we can come up with a Best-Practice bootstrap and Laravel-style Application instance for loading Service Providers, etc.

## Contributing
The [4.2](https://github.com/mattstauffer/torch/tree/4.2) branch is still going strong, but this 5.1 branch is brand-new. I would appreciate any and all contributions.

At this point, most contributions could be just copying the 4.2 component over, adding `composer.json`, tweaking the old code for the new folder structure, and making sure our old code still works.

## But my framework doesn't use Symfony's HttpFoundation!
That's hard, unfortunately. If you're using CodeIgniter, and it's a new project, honestly, it's time to upgrade frameworks. `¯\(°_o)/¯` But if it's a pre-existing project, and we all have those, the majority of the 4.2 components will still work with CodeIgniter and other older projects.
