# <img src="torch-logo.png" alt="Torch Logo" align="right">Torch - Using Laravel's Illuminate Components Independently

Torch is a project to provide instructions and examples for using Illuminate components as standalone components in non-Laravel applications. The current `master` branch shows how to use Illuminate's `5.5` components.

**Note**: If you are working with an older project, you might have more success using the [5.1 components](https://github.com/mattstauffer/torch/tree/5.1) or the [4.2 components](https://github.com/mattstauffer/torch/tree/4.2).

## Usage

At the moment, the project is divided into many directories beneath `components` which will each contain an index file, usually written with [Slim](http://www.slimframework.com/). Navigate to that directory in your terminal and run the following to serve a web site from that directory:

```bash
$ composer install
$ php -S localhost:8000
```

Now you can visit [http://localhost:8000/](http://localhost:8000/) in your browser to view the output of each.

## Packages

### Ready for 5.5

* [Cache](https://github.com/mattstauffer/Torch/tree/master/components/cache)
* [Config](https://github.com/mattstauffer/Torch/tree/master/components/config)
* [Encryption](https://github.com/mattstauffer/Torch/tree/master/components/encryption)
* [Log](https://github.com/mattstauffer/Torch/tree/master/components/log)
* [Routing](https://github.com/mattstauffer/Torch/tree/master/components/routing)
* [Support](https://github.com/mattstauffer/Torch/tree/tree/components/support)
* [Translation](https://github.com/mattstauffer/Torch/tree/master/components/translation)
* [Events](https://github.com/mattstauffer/Torch/tree/master/components/events)
* [View](https://github.com/mattstauffer/Torch/tree/master/components/view)
* [Session](https://github.com/mattstauffer/Torch/tree/master/components/session)
* [Validation](https://github.com/mattstauffer/Torch/tree/5.1/components/validation)

### Need to be moved over from 5.1

* [Container](https://github.com/mattstauffer/Torch/tree/5.1/components/container)
* [Database](https://github.com/mattstauffer/Torch/tree/5.1/components/database)
* [Pagination](https://github.com/mattstauffer/Torch/tree/5.1/components/pagination)
* [Middleware](https://github.com/mattstauffer/Torch/tree/5.1/components/middleware)

### Need to be moved over from 4.2

* [Mail](https://github.com/mattstauffer/Torch/tree/master/4.2/mail) - Imported from 4.2 but needs to be tested/tweaked
* [Queue](https://github.com/mattstauffer/Torch/tree/master/4.2/queue) - Imported from 4.2 but needs to be tested/tweaked

## Other Packages

### Done

* [LaravelCollective/html](https://github.com/mattstauffer/Torch/tree/master/other-components/html)

## Contributing

A few important notes:

1. The imagined end user is a developer of _any_ Symfony-HttpFoundation-using project copying the route closure directly into a project, so try to avoid using any Slim conventions and use as little preparation code outside the closure as possible.
2. While some components would be _easier_ to implement with a Laravel-style Application instance and a fuller bootstrap, I'd prefer we implement as many as possible _without_ loading Laravel's Service Providers.
3. Some components will require a bootstrap, and I hope we can come up with a Best-Practice bootstrap and Laravel-style Application instance for loading Service Providers, etc.

## Contributing

The [4.2](https://github.com/mattstauffer/torch/tree/4.2) and [5.1](https://github.com/mattstauffer/torch/tree/5.1) branches are still going strong, but this 5.5 branch is brand-new. I would appreciate any and all contributions.

At this point, most contributions could be just copying the 5.1 or 4.2 component over, adding `composer.json` if it doesn't exist (4.2), tweaking the old code for the new folder structure, and making sure our old code still works.

## But my framework doesn't use Symfony's HttpFoundation!

Many of these components will still work. But a few of them require HttpFoundation. `¯\(°_o)/¯`
