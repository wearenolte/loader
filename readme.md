#Loader [![Build Status](https://travis-ci.org/moxie-lean/loader.svg?branch=master)](https://travis-ci.org/moxie-lean/loader)

> Allows to load files from directories with a more sugared syntax, and
> allowing the use of params passed to the fils.

# Benefits 

By using the Loader instead of the regular `get_template_part` or any other `wordpress` default
function you gain the follow benefits: 

- More clear sintax of what files and from where are loaded.
- Easy to reuse pieces of code by allowing load files with variables. What this means is that
you can define a `button.php` file that can be used in any part of your website and any time
you want to change something on the button markup you only change this on one single place (see example)

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
