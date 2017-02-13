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
    data on-the-fly, or for more advanced tricks like instrumentation,
  * Wrapper: Declare user-defined protocols that will naturally be
    handled by the PHP standard library (like `fopen`,
    `stream_get_contents` etc.),
  * Interfaces: One interface per capability a stream can offer.

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
offers certain capabilities. The interfaces are declared in the
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
what capabilities its stream will provide or not.

Finally, the highest interface is `Stream`, defining the `getStream`
method, that's all. That's the most undefined stream. All capabilities
must extend this interface.

### Define a concrete stream

The main `Hoa\Stream\Stream` class is abstract. Two method
implementations are left to the user: `_open` and : `_close`,
respectively to open a particular stream, and to close this particular
stream, for instance:

```php
class BasicFile extends Hoa\Stream\Stream
{
    protected function &_open($streamName, Hoa\Stream\Context $context = null)
    {
        if (null === $context) {
            $out = fopen($streamName, 'rb');
        } else {
            $out = fopen($streamName, 'rb', false, $context->getContext());
        }

        return $out;
    }

    protected function _close()
    {
        return fclose($this->getStream());
    }
}
```

Then, the most common usage will be:

```php
$file = new BasicFile('/path/to/file');
```

That's all. This stream has not capability yet. Let's implement the
`In` capability:

```php
class BasicFile extends Hoa\Stream\Stream implements Hoa\Stream\IStream\In
{
    // …

    public function read($length)
    {
        return fread($this->getStream(), max(1, $length));
    }

    // …
}
```

Other methods are left as an exercise to the reader. Thus, we are now able to:

```php
$chunk = $file->read(42);
```

The `Stream` capability is already implemented by the `Hoa\Stream\Stream` class.

### Contextual streams

A context is represented by the `Hoa\Stream\Context` class. It
represents a set of options and parameters for the stream. For
instance, for the `http://` stream wrapper, we have the following
options and parameters:

To use them, first let's define the context:

```php
$contextId = 'my_http_context';
$context   = Hoa\Stream\Context::getInstance($contextId);
$context->setOptions([
    // …
]);
```

And thus, we can ask a stream to use this context based on the chosen
context ID, like this:

```php
$basicFile = new BasicFile('/path/to/file', $contextId);
```

For the stream implementer, the `getOptions` and `getParameters`
methods on the `Hoa\Stream\Context` class will be useful to
respectivelly retrieve the options and the parameters, and acts
according to them.

### Events, listeners, and notifications

A stream has some events, and several listeners. So far, listeners
mostly represent “stream notifications” (details hereinafter).

2 events are registered: `hoa://Event/Stream/<streamName>` and
`hoa://Event/Stream/<streamName>:close-before`. Thus, for instance, to
execute a function before the stream `/path/to/file` is closed, one
will write:

```php
Hoa\Event\Event::getEvent('hoa://Event/Stream//path/to/file:close-before')->attach(
    function (Hoa\Event\Bucket $bucket) {
        // do something!
    }
);
```

Remember that a stream is not necessarily a file. It can be a socket,
a WebSocket, a stringbuffer, any stream you have defined…
Consequently, this event can be used in very different manner for
various scenario, like logging things, closing related resources,
firing another events… There is no rule. The observed stream is still
opened, and can theoritically still be used.

This event is fired when calling the `Hoa\Stream\Stream::close`
method.

About listeners: To register a listener, we must create an instance of
our stream without opening it. This action is called “deferred
opening”. We can control the opening time with the third argument of
the default `Hoa\Stream\Stream` constructor, like:

```php
$file = new BasicFile('/path/to/file', null, true);
// do something
$file->open();
```

Passing `null` as a second argument means: No context. Note that we
must manually call the `open` method to open the stream. Between the
stream instanciation and the stream opening, we can attach new
listeners.

Depending of the stream implementation, different listeners will be
fired. The term “listener” is the one used everywhere in Hoa, but PHP
—in the context of stream— refers to them as notifications. Let's take
an example with an HTTP stream:

```php
$basic = new BasicFile(
    'https://hoa-project.net/', // stream name
    null,                       // context ID
    true                        // defere opening
);
$basic->on(
    'connect',
    function (Hoa\Event\Bucket $bucket) {
        echo 'Connected', "\n";
    }
);
$basic->on(
    'redirect',
    function (Hoa\Event\Bucket $bucket) {
        echo 'Redirection to ', $bucket->getData()['message'], "\n";
    }
);
$basic->on(
    'mimetype',
    function (Hoa\Event\Bucket $bucket) {
        echo 'MIME-Type is ' . $bucket->getData()['message'], "\n";
    }
);
$basic->on(
    'size',
    function (Hoa\Event\Bucket $bucket) {
        echo 'Size is ' . $bucket->getData()['max'], "\n";
    }
);
$basic->on(
    'progress',
    function (Hoa\Event\Bucket $bucket) {
        echo 'Progressed, ' . $bucket->getData()['transferred'], ' bytes downloaded', "\n";
    }
);

// Then open.
$basic->open();
```

You might see something like this:

```
Connected
MIME-Type is text/html; charset=UTF-8
Redirection to /En/
Connected
MIME-Type is text/html; charset=UTF-8
Progressed, … bytes downloaded
Progressed, … bytes downloaded
```

The exhaustive list of listeners is the following:

  * `authrequire`, when the authentication is required,
  * `authresult`, when the result of the authentication is known,
  * `complete`, when the stream is complete (meaning can vary a lot here),
  * `connect`, when the stream is connected (meaning can vary a lot here),
  * `failure`, when something unexpected occured,
  * `mimetype`, when the MIME-type of the stream is known,
  * `progress`, when there is significant progression,
  * `redirect`, when the stream is redirected to another stream,
  * `resolve`, when the stream is resolved (meaning can vary a lot here),
  * `size`, when the size of the stream is known.

All listener bucket data is an array containing the following pairs:

  * `code`, one of the `STREAM_NOTIFY_*` constant, which is basically
    the listener name
    (see [the documentation](http://php.net/stream.constants)),
  * `severity`, one of the `STREAM_NOTIFY_SEVERITY_*` constant:
    * `STREAM_NOTIFY_SEVERITY_INFO`, normal, non-error related,
      notification,
    * `STREAM_NOTIFY_SEVERITY_WARN`, non critical error condition,
      processing may continue,
    * `STREAM_NOTIFY_SEVERITY_ERR`, a critical error occurred,
      processing cannot continue.
  * `message`, a string containing most useful information,
  * `transferred`, amount of bytes already transferred,
  * `max`, total number of bytes to transfer.

This is possible for the stream implementer to add more
listeners. Please, take a look at
[the `Hoa\Event` library](https://central.hoa-project.net/Resource/Library/Event). Not
all listeners will be fired by all kind of streams.

### Wrappers

### Filters

### Other operations

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
