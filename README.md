# Illuminate Non-Laravel

Showing a simplest-use example for using each v4.2 Illuminate component in non-Laravel applications.

## Usage
At the moment, the project is divided into many directories beneath public which will each contain an index file, usually written with [Slim](http://www.slimframework.com/). Navigate to that directory in your terminal and run the following to serve a web site from that directory:

```bash
$ php -S localhost:8000
```

Now you can visit [http://localhost:8000/](http://localhost:8000/) in your browser to view the output of each.

## Packages

### Done/In Progress
 * [Database](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/public/database)
 * [Support](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/public/support)
 * [Cache](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/public/cache)
 * [Config](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/public/config)
 * [IoC Container](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/public/container)
 * [Routing](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/public/routing)
 * [Translation](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/public/translation)
 * [Encryption](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/public/encryption)
 * [Queue](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/public/queue)
 * [Mail](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/public/mail)
 * [Session (File Driver)](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/public/session)

### Planned
 * Pagination
 * HTTP
 * Validation
 * Artisan
 * Events

## Contributing
A few important notes:

 1. The imagined end user is a CodeIgniter developer copying the route closure directly into a project, so try to avoid using any Slim conventions and use as little code outside the closure as possible.
 2. While some components would be *easier* to implement with a Laravel-style Application instance and a fuller bootstrap, I'd prefer we implement as many as possible *without* loading Laravel's Service Providers.
 3. Some components will require a bootstrap, and I hope we can come up with a Best-Practice bootstrap and Laravel-style Application instance for loading Service Providers, etc.

## Thanks
Thanks for explicit contributions from Jan Hartigan, Jeremy Vaught, Kayla Daniels, and anyone who's submitted PRs, and Mohammad Gufran's blog posts on Config and Routing, and the work done by Phil Sturgeon and Dan Horrigan towards this general direction. Also, of course, Taylor "Baller" Otwell.

## Related
See other related projects:

* [CodeIgniter Service Level](https://github.com/jeremyvaught/CodeIgniter-Service-Level) by [@jeremyvaught](https://github.com/jeremyvaught)
* [Slim Services](https://github.com/itsgoingd/slim-services) by [@itsgoingd](https://github.com/itsgoingd)
* [Session Gear](https://github.com/phpgearbox/session) by [@phpgearbox](https://github.com/phpgearbox)

## Why 4.2?
A lot of the refactoring of Illuminate components in Laravel 4.3 made it difficult to implement them in any framework that doesn't use Symfony's HttpRequest object, so the cost of implementing goes up significantly. It might be worth building a branch of this projec that targets using 5.0 components and limiting it to only work on frameworks that use HttpRequest.
