#Loader [![Build Status](https://travis-ci.org/moxie-lean/loader.svg?branch=master)](https://travis-ci.org/moxie-lean/loader)

> Allows to load files from directories with a more sugared syntax, and
> allowing the use of params passed to the file.

# Benefits 

By using the Loader package, instead of the regular `get_template_part` or 
any other `WordPress` default function to load partials or files between templates
you have the follow benefits: 

- More clear sintax of what files and from where are loaded.
- Allow to send variables between files loaded.

# Requirements

Make sure you have at least the following in order to use this library.

- PHP 5.6+
- [composer](https://getcomposer.org/):

# Installation.

```bash
composer require moxie-lean/loader
```

# Usage

```php
<?php
// File: index.php
use Lean\Load;

$params = [
  'title' => get_the_title(),
  'url' => get_the_permalink(),
];
Load::partials( 'single' $params );
```

The function accepts two arguments:

- `$file`, in the example above `single` this is the file name wanted to load
the extension is optional, in this case we want to load the file `single.php` from
the `partials` directory, you can create alias for directories (see alias for more information)
to use a different name for that directory.

- `$args`, An associative array with the values that we wanted to pass to the loaded file, this can
be any number of elements in the array as long as it's a valid associative array. Those values are available
on the loaded file via the `$args` variable and can be used as follows:

```php
<?php
// File: partials/single.php
?>
<a href="<?php echo esc_url( $args['url'] ); ?>">
  <?php echo esc_html( $args['title'] ); ?>
</a>
```

# Use case 

Let's imagine we have a directory of files like this: 

```php
- header.php
- footer.php
- index.php
- functions.php
- style.css
|- partials
|- |- global
|- |- |- button.php
```

And we want to reuse `button.php` in `header.php` and `footer.php`.

1. Download the library via `composer` by typing in your terminal: 


2. Register the directories where to look for the alias, we edit `functions.php`, and 
we add a new path where to look for:

```php
add_filter( 'loader_directories', function( $directories ){
  $directories[] = get_template_directory() . '/partials'
  return $directories;
});
```

# Filters

There are a coupple of filters that you can use in order to extend the default functionalitty of the loader.

# Register directories where to look for file.

```php
add_filter( 'loader_directories', function( $directories ){
  $directories[] = get_template_directory();
  return $directories;
});
```

That will search files on the root directory of your theme.


# Register alias

The alias are used to search inside of directories more easily for
example:  

```php
add_filter('loader_alias', function( $alias ){
  $alias['partial'] = 'partials';
  return $alias;
});
```

Which give us a sintax like this: 

```php
Load::partial( 'button' );
```

From a file located in:

`get_template_directory() . '/partials/button.php'`,

# Road Map

- Work the same as `get_template_part` so it works in childs and parent theme.
- Set default root as the current theme in order to be a little easy to set up.
