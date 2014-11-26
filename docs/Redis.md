Redis Support for Memory Pools
==============================
To use Redis as a memory pool, a Redis 2.6.12 server is required. This library uses Predis for Redis support.

The constructor will pass parameters transparently through to the Predis Client constructor, with the exception of a
'options' value, which is extracted and sent to the Client constructors second argument.
