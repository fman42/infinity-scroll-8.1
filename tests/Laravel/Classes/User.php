<?php

namespace Tests\Laravel\Classes;

use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use PDO;

class User
{
    const COUNT = 100;

    private static ?Connection $CONNECTION = null;
    public static function query(): Builder
    {
        if (self::$CONNECTION) {
            return self::$CONNECTION->table('users');
        }
        $pdo = new PDO('sqlite::memory:');
        self::$CONNECTION = new Connection($pdo);
        self::__migrate(self::$CONNECTION);
        self::__insert(self::$CONNECTION);
        return self::$CONNECTION->table('users');
    }

    private static function __migrate(Connection $connection) {
        $connection->unprepared("
            CREATE TABLE `users` (  
                `id` TEXT PRIMARY KEY NOT NULL,  
                `name` TEXT NOT NULL,
                `created_at` TEXT NOT NULL
            );"
        );
    }

    private static function __insert(Connection $connection)
    {
        $now = new Carbon();
        for ($i = 0; $i < self::COUNT; $i++) {
            [$id, $name, $created] = [md5($i), Str::random(5) . '_' . $i, $now . ''];

            $connection->insert("INSERT INTO `users` (`id`, `name`, `created_at`) VALUES ('$id','$name','$created');");

            $now->addSeconds(rand(10000, 60000));
        }
    }
}