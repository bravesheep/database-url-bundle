<?php

namespace Bravesheep\DatabaseUrlBundle;

class DatabaseUrlResolver 
{
    private static $scheme_drivers = [
        'postgres' => 'pdo_pgsql',
        'postgresql' => 'pdo_pgsql',
        'pgsql' => 'pdo_pgsql',
        'pdo_pgsql' => 'pdo_pgsql',
        'mysql' => 'pdo_mysql',
        'pdo_mysql' => 'pdo_mysql',
        'sqlite' => 'pdo_sqlite',
        'pdo_sqlite' => 'pdo_sqlite',
        'mssql' => 'pdo_sqlsrv',
        'pdo_mssql' => 'pdo_sqlsrv',
    ];

    public function resolve($url)
    {
        // some special cases for sqlite urls
        if (strpos($url, 'sqlite:///') === 0) {
            $parts = parse_url('sqlite://host/' . substr($url, 10));
        } else if (strpos($url, 'pdo_sqlite:///') === 0) {
            $parts = parse_url('pdo_sqlite://host/' . substr($url, 14));
        } else {
            $parts = parse_url($url);
        }

        if (false === $parts) {
            throw new \LogicException("Invalid url '{$url}'.");
        }

        $parameters = [];
        if (!isset($parts['scheme'])) {
            throw new \LogicException("Unkown scheme in '{$url}'.");
        }

        if (!isset(self::$scheme_drivers[$parts['scheme']])) {
            throw new \LogicException("Unknown database scheme '{$parts['scheme']}'");
        }

        $parameters['driver'] = self::$scheme_drivers[$parts['scheme']];

        if ($parameters['driver'] === 'pdo_sqlite') {
            if ($url === 'pdo_sqlite://:memory:' || $url === 'sqlite://:memory:') {
                $parameters['path'] = ':memory:';
            } else {
                $parameters['path'] = isset($parts['path']) ? $parts['path'] : ':memory:';
            }

            if ($parameters['path'] === ':memory:') {
                $parameters['path'] = null;
                $parameters['memory'] = true;
            } else {
                $parameters['memory'] = false;
            }

            if (isset($parts['query'])) {
                parse_str($parts['query'], $query);
                if (isset($query['relative'])) {
                    $parameters['path'] = substr($parameters['path'], 1);
                }
            }

            $parameters['host'] = null;
            $parameters['port'] = null;
            $parameters['user'] = null;
            $parameters['password'] = null;
            $parameters['name'] = null;
        } else {
            $parameters['host'] = isset($parts['host']) ? $parts['host'] : null;
            $parameters['port'] = isset($parts['port']) ? $parts['port'] : null;
            $parameters['user'] = isset($parts['user']) ? $parts['user'] : null;
            $parameters['password'] = isset($parts['pass']) ? $parts['pass'] : null;
            $parameters['name'] = isset($parts['path']) ? substr($parts['path'], 1) : null;
            $parameters['path'] = null;
            $parameters['memory'] = false;
        }
        return $parameters;
    }
} 
