![Torch logo](https://raw.githubusercontent.com/mattstauffer/torch/master/torch-banner.png)

# Torch - Using Laravel's Illuminate Components Independently

Torch is a project to provide instructions and examples for using Illuminate components as standalone components in non-Laravel applications. The current `master` branch shows how to use Illuminate's `8.0` components.

**Note**: If you are working with an older project, you might have more success using the [5.5 components](https://github.com/mattstauffer/torch/tree/5.5) or the [5.1 components](https://github.com/mattstauffer/torch/tree/5.1) or the [4.2 components](https://github.com/mattstauffer/torch/tree/4.2).

## Usage

At the moment, the project is divided into many directories beneath `components` which will each contain an index file, usually written with [Slim](http://www.slimframework.com/). Navigate to that directory in your terminal and run the following to serve a web site from that directory:

```bash
$ composer install
$ php -S localhost:8000
```

Now you can visit [http://localhost:8000/](http://localhost:8000/) in your browser to view the output of each.

## Packages

### Ready for 8.0

* [Artisan Console](https://github.com/mattstauffer/Torch/tree/master/components/artisan)
* [Cache](https://github.com/mattstauffer/Torch/tree/master/components/cache)
* [Config](https://github.com/mattstauffer/Torch/tree/master/components/config)
* [Container](https://github.com/mattstauffer/Torch/tree/master/components/container)
* [Database](https://github.com/mattstauffer/Torch/tree/master/components/database)
* [Encryption](https://github.com/mattstauffer/Torch/tree/master/components/encryption)
* [Events](https://github.com/mattstauffer/Torch/tree/master/components/events)
* [HTTP Client](https://github.com/mattstauffer/Torch/tree/master/components/http)
* [Log](https://github.com/mattstauffer/Torch/tree/master/components/log)
* [Middleware](https://github.com/mattstauffer/Torch/tree/master/components/middleware)
* [Pagination](https://github.com/mattstauffer/Torch/tree/master/components/pagination)
* [Queue](https://github.com/mattstauffer/Torch/tree/master/components/queue)
* [Routing](https://github.com/mattstauffer/Torch/tree/master/components/routing)
* [Schedule](https://github.com/mattstauffer/Torch/tree/master/components/schedule)
* [Session](https://github.com/mattstauffer/Torch/tree/master/components/session)
* [Support](https://github.com/mattstauffer/Torch/tree/master/components/support)
* [Translation](https://github.com/mattstauffer/Torch/tree/master/components/translation)
* [View](https://github.com/mattstauffer/Torch/tree/master/components/view)
* [Validation](https://github.com/mattstauffer/Torch/tree/master/components/validation)


### Need to be moved over from 4.2

* [Mail](https://github.com/mattstauffer/Torch/tree/4.2/public/mail) - Never finished porting from 4.2-5.1 and then it never got the simpler upgrades from 5.1 until today

## Other Packages

### Done

* [LaravelCollective/html](https://github.com/mattstauffer/Torch/tree/master/other-components/html)

## Contributing

A few important notes:

1. The imagined end user is a developer of _any_ Symfony-HttpFoundation-using project copying the route closure directly into a project, so try to avoid using any Slim conventions and use as little preparation code outside the closure as possible.
2. While some components would be _easier_ to implement with a Laravel-style Application instance and a fuller bootstrap, I'd prefer we implement as many as possible _without_ loading Laravel's Service Providers.
3. Some components will require a bootstrap, and I hope we can come up with a Best-Practice bootstrap and Laravel-style Application instance for loading Service Providers, etc.

## Contributing

The most helpful use for contributions right now would be updating the readme's in each section to make sure we have instructions on how to test this specific component to see that it's working (based on how the specific `index.php` for this component is set up).

## But my framework doesn't use Symfony's HttpFoundation!

Many of these components will still work. But a few of them require HttpFoundation. `¯\(°_o)/¯`
