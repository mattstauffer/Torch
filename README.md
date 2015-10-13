# Illuminate Non-Laravel

Showing a simplest-use example for using each v5.1 Illuminate component in non-Laravel applications.

**Note**: This is *just* getting started. If you want to use these components today, you might want to check out our [archive of the 4.2 components](https://github.com/mattstauffer/illuminatenonlaravel/tree/4.2); most of them will work just fine on your project.

## Usage
At the moment, the project is divided into many directories beneath `components` which will each contain an index file, usually written with [Slim](http://www.slimframework.com/). Navigate to that directory in your terminal and run the following to serve a web site from that directory:

```bash
$ composer install
$ php -S localhost:8000
```

Now you can visit [http://localhost:8000/](http://localhost:8000/) in your browser to view the output of each.

## Packages

### Done
 * [Database](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/components/database)
 * [Support](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/components/support)
 * [Config](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/components/config)
 * [Encryption](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/components/encryption)
 * [Translation](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/components/translation)
 * [Validation](https://github.com/mattstauffer/IlluminateNonLaravel/tree/master/components/validation)
 * [Session](https://github.com/mattstauffer/IlluminateNonLaravel/tree/components/session)

### In Progress
 * [Mail](https://github.com/mattstauffer/IlluminateNonLaravel/tree/components/mail) - Imported from 4.2 but needs to be tested/tweaked

### Planned
 * Cache
 * IoC
 * Routing
 * Queue
 * Pagination
 * Artisan
 * Events
 * More?

## Contributing
A few important notes:

 1. The imagined end user is a developer of *any* Symfony-HttpFoundation-using project copying the route closure directly into a project, so try to avoid using any Slim conventions and use as little preparation code outside the closure as possible.
 2. While some components would be *easier* to implement with a Laravel-style Application instance and a fuller bootstrap, I'd prefer we implement as many as possible *without* loading Laravel's Service Providers.
 3. Some components will require a bootstrap, and I hope we can come up with a Best-Practice bootstrap and Laravel-style Application instance for loading Service Providers, etc.

## Contributing
The [4.2](https://github.com/mattstauffer/illuminatenonlaravel/tree/4.2) branch is still going strong, but this 5.1 branch is brand-new. I would appreciate any and all contributions.

At this point, most contributions could be just copying the 4.2 component over, adding `composer.json`, tweaking the old code for the new folder structure, and making sure our old code still works.

## But my framework doesn't use Symfony's HttpFoundation!
That's tough. If you're using CodeIgniter, and it's a new project, honestly, it's time to upgrade. ¯\(°_o)/¯ But if it's a pre-existing project, and we all have those, the majority of the 4.2 components will still work with CodeIgniter and other older projects.
