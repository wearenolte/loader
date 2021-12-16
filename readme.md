# Loader  

[![Build Status](https://travis-ci.org/moxie-lean/loader.svg?branch=master)](https://travis-ci.org/moxie-lean/loader) 

> Allows to load files from directories with a more sugared syntax, and
> allowing the use of params passed to the file.

# Benefits 

By using the Loader package, instead of the regular `get_template_part` or 
any other `WordPress` default function to load partials or files between templates
you have the follow benefits: 

- More clear sintax of what files and from where are loaded.
- Allow to send variables between files loaded.
- Multiple set of arguments to the partials. 
- Keep things DRY.

# Requirements

Make sure you have at least the following in order to use this library.

- PHP 7.4 or PHP 8.0
- [composer](https://getcomposer.org/):

# Installation

```bash
composer require moxie-lean/loader
```

# Usage

You need to make sure the `autoload.php` file from composer is included so you can use the functions
from other packages.

```php
// functions.php
include_once( get_stylesheet_directory()  . '/vendor/autoload.php' );
```

```php
<?php
// File: index.php
use Lean\Load;

$args = [
  'title' => get_the_title(),
  'url' => get_the_permalink(),
  'target' => '_blank'
];
Load::partials( 'single', $args );
```

The function accepts at least two arguments:

- `$file`, in the example above `single`. This is the filename wanted to load.
The extension is optional, in this case we want to load the file `single.php` from
the `partials` directory, you can create an alias for directories 
([see alias for more information](#register-an-alias)) to use a different name for that directory.

- `...$args`, an associative array with the values that we wanted to pass to the 
loaded file, the array can have any number of elements as long as it's a 
valid associative array. Those values are available on the loaded file via 
the `$args` variable and can be used as follows:

You can send as many set of arguments as you want, at the end all
sets are merged into a single one with `wp_parse_args` to create a single set. 

```php
<?php 
// File: partials/single.php 
// All loaded files have an $args variable that is used to store all the params
// passed from where the Load function was used.
?>
<a href="<?php echo esc_url( $args['url'] ); ?>" target="<?php echo esc_attr( $args['target'] ); ?>">
  <?php echo esc_html( $args['title'] ); ?>
</a>
```

### Multiple set of arguments.

```php
<?php 
use Lean\Load;

$set_1 = [
  'a' => 1,
  'b' => 5,
  'c' => 3
];
$set_2 = [
  'a' => 10,
  'd' => 3
];
$set_3 = [
  'd' => 10,
  'c' => 2,
  'r' => 3,
];
// You can have as many sets as you want.
Load::partials( 'single', $set_1, $set_2, $set_3 ) ?>
```

## Tips

### Set default values

You can easily set default values to always make sure you have the expected arguments
on the partial or to have values that migth be optional like: 

```php
<?php
// File: partials/single.php

// The following lines creates an array with default values. If those values 
// are not specified when the file is loaded this values are going to be used instead.
$defaults = [
  'url' => '',
  'title' => '',
  'target' => '_self',
]
// Update $args with the initial $args mixed with the $default values.
$args = wp_parse_args( $args, $defaults );
?>
<a href="<?php echo esc_url( $args['url'] ); ?>" target="<?php echo esc_attr( $args['target'] ); ?>">
  <?php echo esc_html( $args['title'] ); ?>
</a>
```

### Don't render if you don't have an expected value.

In some cases you are expecting a required value and if that value is not present
you don't want to render that specifc component, in those situations is better to 
avoid the render of the component, in order to do that you can return from the template
at any point to avoid the following lines to be executed, for example: 

```php
<?php
// File: partials/single.php

// The following lines creates an array with default values. If those values 
// are not specified when the file is loaded this values are going to be used instead.
$defaults = [
  'url' => '',
  'title' => '',
  'target' => '_self',
]
// Update $args with the initial $args mixed with the $default values.
$args = wp_parse_args( $args, $defaults );

// Don't render if the title or url are empty.
if ( empty( $args['title'] || empty( $args['url'] ) ) ) {
  return; 
}
?>
<a href="<?php echo esc_url( $args['url'] ); ?>" target="<?php echo esc_attr( $args['target'] ); ?>">
  <?php echo esc_html( $args['title'] ); ?>
</a>
```

# Filters

There are a coupple of filters that you can use in order to extend the default 
functionalitty of the loader. You can place all the filters on `functions.php` of 
your theme or any file of your plugin.

## Register directories where to look for files.

By default the loader is going to look in the root of the theme but if you have a 
structure of files such as: 

```
index.php
functions.php
|- views
|-|- partials
|-|-|- single.php
|-|-|- button.php
```

To load files from `views` directory you can use:

```php
<?php 
use Lean\Load;

$arguments = [];
Load::views( 'partials/single', $arguments ); 
Load::views( 'partials/button', $arguments ); 
?>
```

Or if you want to avoid typing `partials/` every time you can include a new directory
into the search path, with the `loader_directories` filter, such as:

```php
add_filter( 'loader_directories', function( $directories ){
  $directories[] = get_template_directory() . '/views';
  return $directories;
});
```

Whith this change you now can write something like:

```php
<?php 
use Lean\Load;

$arguments = [];
Load::partials( 'single', $arguments ); 
Load::partials( 'button', $arguments ); 
?>
````


## Register an alias

Alias are used if you want to access a directory in a different name such as if you want
to use `Load::blocks` instead of `Load::partials` you can rename the directory but to
avoid that you can easily just create an alias to call a directory in a different way,
with the `loader_alias` filter, as you can see in the following example:

```php
add_filter('loader_alias', function( $alias ){
  $alias['partials'] = 'blocks';
  return $alias;
});
```

You only need to specify the `key` into the `$alias` variable that you want to
create an alias and assign to `$alias[ key ]` the value with the alias that you 
want to create.

Which give us a sintax like this: 

```php
<?php
use Lean\Load;
$arguments = [];
Load::blocks( 'single', $arguments );
?>
```

# Road Map

- Work the same as `get_template_part` so it works in childs and parent theme.
- Set default root as the current theme in order to be a little easy to set up.
