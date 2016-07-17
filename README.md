# EventLog
The EventLog project, extracted from DQLServer, along with its tests.

The following adapters are available.
- MySQL 5.7 (for microtime)
- SQLite

They also come in standard PDO or Laravel flavours.

### Usage:
Using the EventLog is pretty easy, you just instantiate it with the right EventRepository adapter for your situation.
if you're using a Dependency Injection system, then easiest way to do it is to map the interface 'EventRepository' to the adapter of your choice (stored in the Adapter folder).
If you're using Laravel (which we are), you can just use the Laravel adapter, and the DI system will take care of everything else.

### Laravel Setup:
Here's some sample code using a standard DI system and Laravels providers.

First setup the provider and point the event repo interface at the right adapter
```php
$this->app->singleton(
    \EventSourced\EventLog\EventRepository::class, 
    \EventSourced\EventLog\Adapter\Laravel\MySQL\EventRepository::class
);
```

The instantiate the EventLog
```php
$event_log = $this->app->make(\EventSourced\EventLog\EventLog::class);
```

