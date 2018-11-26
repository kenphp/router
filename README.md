## Ken-Router
A simple PHP Router for web environment and console environment.
This library is part of KenPHP Project, but can be used independently.

## Features
- Static Route Patterns
- Dynamic Route Patterns
- Named parameters
- Optional parameters
- Regex-based route patterns
- Subrouting
- Supports web application routing
- Supports console application routing
- Before and After route middleware
- Custom handler when a route is not found

## What it does ?
- Store route patterns, handlers, and middlewares information
- Resolve request to matching patterns

## What it doesn't ?
- Parse route path from $_SERVER or any other means. You must provide the route path and method to `Router::resolve` method.
- Execute middlewares and handlers. It only returns an array containing matched route handlers, middlewares, and parameters found in the request.

## Requirements
- PHP 5.6 or greater

## Installation
The easiest way to install is using Composer
```
$ composer require kenphp/ken-router
```

## Examples
- [Usage example for web application](examples/index.php)
