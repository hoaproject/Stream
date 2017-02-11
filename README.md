<p align="center">
  <img src="https://static.hoa-project.net/Image/Hoa.svg" alt="Hoa" width="250px" />
</p>

---

<p align="center">
  <a href="https://travis-ci.org/hoaproject/stream"><img src="https://img.shields.io/travis/hoaproject/stream/master.svg" alt="Build status" /></a>
  <a href="https://coveralls.io/github/hoaproject/stream?branch=master"><img src="https://img.shields.io/coveralls/hoaproject/stream/master.svg" alt="Code coverage" /></a>
  <a href="https://packagist.org/packages/hoa/stream"><img src="https://img.shields.io/packagist/dt/hoa/stream.svg" alt="Packagist" /></a>
  <a href="https://hoa-project.net/LICENSE"><img src="https://img.shields.io/packagist/l/hoa/stream.svg" alt="License" /></a>
</p>
<p align="center">
  Hoa is a <strong>modular</strong>, <strong>extensible</strong> and
  <strong>structured</strong> set of PHP libraries.<br />
  Moreover, Hoa aims at being a bridge between industrial and research worlds.
</p>

# Hoa\Stream

[![Help on IRC](https://img.shields.io/badge/help-%23hoaproject-ff0066.svg)](https://webchat.freenode.net/?channels=#hoaproject)
[![Help on Gitter](https://img.shields.io/badge/help-gitter-ff0066.svg)](https://gitter.im/hoaproject/central)
[![Documentation](https://img.shields.io/badge/documentation-hack_book-ff0066.svg)](https://central.hoa-project.net/Documentation/Library/Stream)
[![Board](https://img.shields.io/badge/organisation-board-ff0066.svg)](https://waffle.io/hoaproject/stream)

This library is a high-level abstraction over PHP streams. It includes:

  * Stream manipulations: Open, close, auto-close, timeout, blocking
    mode, buffer size, metadata etc.,
  * Stream notifications: Depending of the stream wrapper, the
    supported listeners are the following: `authrequire`,
    `authresult`, `complete`, `connect`, `failure`, `mimetype`,
    `progress`, `redirect`, `resolve`, and `size`,
  * Context: Allow to pass options and parameters to the stream
    wrappers, for instance HTTP headers,
  * Filter: A function that sits between the source and the
    destination of a stream, useful for instance to encrypt/decrypt a
    data on-the-fly, or for more advanced tricks,
  * Wrapper: Declare user-defined protocol that will be naturally
    handled by the PHP standard library (like `stream_get_contents`
    etc.),
  * Interfaces: One interface per capability a stream offers.

This library is the foundation of several others, e.g.
[`Hoa\File`](https://central.hoa-project.net/Resource/Library/File) or
[`Hoa\Socket`](https://central.hoa-project.net/Resource/Library/Socket)
(and so
[`Hoa\Websocket`](https://central.hoa-project.net/Resource/Library/Websocket)).

[Learn more](https://central.hoa-project.net/Documentation/Library/Stream).

## Installation

With [Composer](https://getcomposer.org/), to include this library into
your dependencies, you need to
require [`hoa/stream`](https://packagist.org/packages/hoa/stream):

```sh
$ composer require hoa/stream '~1.0'
```

For more installation procedures, please read [the Source
page](https://hoa-project.net/Source.html).

## Testing

Before running the test suites, the development dependencies must be installed:

```sh
$ composer install
```

Then, to run all the test suites:

```sh
$ vendor/bin/hoa test:run
```

For more information, please read the [contributor
guide](https://hoa-project.net/Literature/Contributor/Guide.html).

## Quick usage

As a quick overview, we propose to …

### Interfaces, aka stream capabilities

This library defines several interfaces representing important stream
capabilities. This is very useful when designing a function, or a
library, working with streams. It ensures the stream is typed and
offers certain capability. The interfaces are declared in the
`Hoa\Stream\IStream` namespace:

  * `In`, to read from a stream, provides `read`, `readInteger`,
    `readLine`, `readAll`, `eof` etc.,
  * `Out`, to write onto a stream, provides `write`, `writeArray`,
    `writeLine`, `truncate` etc.,
  * `Bufferable`, for streams with at least one internal buffer,
    provides `newBuffer`, `flush`, `getBufferLevel` etc.,
  * `Touchable`, for “touchable” streams, provides `touch`, `copy`,
    `move`, `delete`, `changeGroup` etc.,
  * `Lockable`, to lock a stream, provides `lock` and several
    constants representing different kind of locks, like
    `LOCK_SHARED`, `LOCK_EXCLUSIVE`, `LOCK_NO_BLOCK` etc.,
  * `Pathable`, for path-based stream, provides `getBasename` and
    `getDirname`,
  * `Pointable`, to move the internal pointer of the stream if any,
    provides `rewind`, `seek` and `tell`,
  * `Statable`, to get statistics about a stream, provides `getSize`,
    `getStatistics`, `getATime`, `getCTime`, `isReadable` etc.,
  * `Structural`, for a structural stream, i.e. a stream acting like a
    tree, provides `selectRoot`, `selectAnyElements`,
    `selectElements`, `selectAdjacentSiblingElement`, `querySelector`
    etc.

Thus, if one only need to read from a stream, it will type the stream
with `Hoa\Stream\IStream\In`. It also allows an implementer to choose
one capability its stream will provide or not.

Finally, the highest interface is `Stream`, defining the `getStream`
method, that's all. That's the most undefined stream.

## Documentation

The
[hack book of `Hoa\Stream`](https://central.hoa-project.net/Documentation/Library/Stream) contains
detailed information about how to use this library and how it works.

To generate the documentation locally, execute the following commands:

```sh
$ composer require --dev hoa/devtools
$ vendor/bin/hoa devtools:documentation --open
```

More documentation can be found on the project's website:
[hoa-project.net](https://hoa-project.net/).

## Getting help

There are mainly two ways to get help:

  * On the [`#hoaproject`](https://webchat.freenode.net/?channels=#hoaproject)
    IRC channel,
  * On the forum at [users.hoa-project.net](https://users.hoa-project.net).

## Contribution

Do you want to contribute? Thanks! A detailed [contributor
guide](https://hoa-project.net/Literature/Contributor/Guide.html) explains
everything you need to know.

## License

Hoa is under the New BSD License (BSD-3-Clause). Please, see
[`LICENSE`](https://hoa-project.net/LICENSE) for details.

## Related projects

The following projects are using this library:

  * [Marvirc](https://github.com/Hywan/Marvirc), A dead simple,
    extremely modular and blazing fast IRC bot,
  * [WellCommerce](http://wellcommerce.org/), Modern e-commerce engine
    built on top of Symfony 3 full-stack framework.
