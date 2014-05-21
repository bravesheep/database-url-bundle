<?php

namespace spec\Bravesheep\DatabaseUrlBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DatabaseUrlResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bravesheep\DatabaseUrlBundle\DatabaseUrlResolver');
    }

    function it_should_not_resolve_invalid_urls()
    {
        $this->shouldThrow('LogicException')->duringResolve('bravesheep');
    }

    function it_should_resolve_postgres_urls()
    {
        $resolved = $this->resolve('postgres://user:pass@host:123/database');
        $resolved->shouldBeArray();
        $resolved->shouldHaveCount(8);
        $resolved['host']->shouldBe('host');
        $resolved['user']->shouldBe('user');
        $resolved['password']->shouldBe('pass');
        $resolved['port']->shouldBe(123);
        $resolved['name']->shouldBe('database');
        $resolved['path']->shouldBe(null);
        $resolved['memory']->shouldBe(false);
        $resolved['driver']->shouldBe('pdo_pgsql');
    }

    function it_should_resolve_mysql_urls()
    {
        $resolved = $this->resolve('mysql://localhost/database');
        $resolved->shouldBeArray();
        $resolved->shouldHaveCount(8);
        $resolved['host']->shouldBe('localhost');
        $resolved['user']->shouldBe(null);
        $resolved['password']->shouldBe(null);
        $resolved['port']->shouldBe(null);
        $resolved['name']->shouldBe('database');
        $resolved['path']->shouldBe(null);
        $resolved['memory']->shouldBe(false);
        $resolved['driver']->shouldBe('pdo_mysql');
    }

    function it_should_resolve_an_in_memory_sqlite_database()
    {
        $resolved = $this->resolve('sqlite://:memory:');
        $resolved->shouldBeArray();
        $resolved->shouldHaveCount(8);
        $resolved['host']->shouldBe(null);
        $resolved['user']->shouldBe(null);
        $resolved['password']->shouldBe(null);
        $resolved['port']->shouldBe(null);
        $resolved['name']->shouldBe(null);
        $resolved['path']->shouldBe(null);
        $resolved['memory']->shouldBe(true);
        $resolved['driver']->shouldBe('pdo_sqlite');
    }

    function it_should_resolve_a_sqlite_absolute_database_path()
    {
        $resolved = $this->resolve('sqlite:///path/to/sqlite.db');
        $resolved->shouldBeArray();
        $resolved->shouldHaveCount(8);
        $resolved['host']->shouldBe(null);
        $resolved['user']->shouldBe(null);
        $resolved['password']->shouldBe(null);
        $resolved['port']->shouldBe(null);
        $resolved['name']->shouldBe(null);
        $resolved['path']->shouldBe('/path/to/sqlite.db');
        $resolved['memory']->shouldBe(false);
        $resolved['driver']->shouldBe('pdo_sqlite');
    }

    function it_should_resolve_a_sqlite_relative_database_path()
    {
        $resolved = $this->resolve('sqlite:///%kernel.cache_dir%/path/to/sqlite.db?relative');
        $resolved->shouldBeArray();
        $resolved->shouldHaveCount(8);
        $resolved['host']->shouldBe(null);
        $resolved['user']->shouldBe(null);
        $resolved['password']->shouldBe(null);
        $resolved['port']->shouldBe(null);
        $resolved['name']->shouldBe(null);
        $resolved['path']->shouldBe('%kernel.cache_dir%/path/to/sqlite.db');
        $resolved['memory']->shouldBe(false);
        $resolved['driver']->shouldBe('pdo_sqlite');
    }
}
