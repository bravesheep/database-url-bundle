<?php

namespace spec\Bravesheep\DatabaseUrlBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BravesheepDatabaseUrlExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bravesheep\DatabaseUrlBundle\BravesheepDatabaseUrlExtension');
    }

    function it_should_set_parameters_if_the_url_parameter_exists(ContainerBuilder $container)
    {
        $container->setParameter('database_driver', 'pdo_pgsql')->shouldBeCalled();
        $container->setParameter('database_host', 'localhost')->shouldBeCalled();
        $container->setParameter('database_user', 'some')->shouldBeCalled();
        $container->setParameter('database_password', 'example')->shouldBeCalled();
        $container->setParameter('database_port', 123)->shouldBeCalled();
        $container->setParameter('database_path', null)->shouldBeCalled();
        $container->setParameter('database_memory', false)->shouldBeCalled();
        $container->setParameter('database_name', 'database')->shouldBeCalled();
        $settings = ['url' => 'postgres://some:example@localhost:123/database', 'prefix' => 'database_'];
        $this->load(['bravesheep_database_url' => ['urls' => ['default' => $settings]]], $container);
    }

    function it_should_not_change_parameters_if_there_are_no_urls(ContainerBuilder $container)
    {
        $container->setParameter(Argument::any(), Argument::any())->shouldNotBeCalled();
        $this->load([], $container);
    }

    function it_should_set_parameters_for_sqlite_databases(ContainerBuilder $container)
    {
        $container->setParameter('database_driver', 'pdo_sqlite')->shouldBeCalled();
        $container->setParameter('database_host', null)->shouldBeCalled();
        $container->setParameter('database_user', null)->shouldBeCalled();
        $container->setParameter('database_password', null)->shouldBeCalled();
        $container->setParameter('database_port', null)->shouldBeCalled();
        $container->setParameter('database_path', '%kernel.cache_dir%/cache/data')->shouldBeCalled();
        $container->setParameter('database_memory', false)->shouldBeCalled();
        $container->setParameter('database_name', null)->shouldBeCalled();
        $settings = ['url' => 'sqlite:///%kernel.cache_dir%/cache/data?relative', 'prefix' => 'database_'];
        $this->load(['bravesheep_database_url' => ['urls' => [$settings]]], $container);
    }

    function it_should_set_multiple_sets_of_parameters_for_multiple_settings(ContainerBuilder $container)
    {
        $container->setParameter('database_driver', 'pdo_pgsql')->shouldBeCalled();
        $container->setParameter('database_host', 'localhost')->shouldBeCalled();
        $container->setParameter('database_user', 'some')->shouldBeCalled();
        $container->setParameter('database_password', 'example')->shouldBeCalled();
        $container->setParameter('database_port', 123)->shouldBeCalled();
        $container->setParameter('database_path', null)->shouldBeCalled();
        $container->setParameter('database_memory', false)->shouldBeCalled();
        $container->setParameter('database_name', 'database')->shouldBeCalled();

        $container->setParameter('mysql_driver', 'pdo_mysql')->shouldBeCalled();
        $container->setParameter('mysql_host', 'remote')->shouldBeCalled();
        $container->setParameter('mysql_user', null)->shouldBeCalled();
        $container->setParameter('mysql_password', null)->shouldBeCalled();
        $container->setParameter('mysql_port', 2345)->shouldBeCalled();
        $container->setParameter('mysql_path', null)->shouldBeCalled();
        $container->setParameter('mysql_memory', false)->shouldBeCalled();
        $container->setParameter('mysql_name', 'db')->shouldBeCalled();

        $first = ['url' => 'postgres://some:example@localhost:123/database', 'prefix' => 'database_'];
        $second = ['url' => 'mysql://remote:2345/db', 'prefix' => 'mysql_'];
        $this->load(['bravesheep_database_url' => ['urls' => [$first, $second]]], $container);
    }
}
