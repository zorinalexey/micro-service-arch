<?php

namespace App\Core\SqlBuilder;

use App\Core\SqlBuilder\CRUD\Delete;
use App\Core\SqlBuilder\CRUD\Insert;
use App\Core\SqlBuilder\CRUD\Select;
use App\Core\SqlBuilder\CRUD\Update;

final class SqlBuilder
{
    public static function select (array|null $columns = null): Select
    {
        return new Select($columns);
    }
    
    public static function insert (): Insert
    {
        return new Insert();
    }
    
    public static function update (): Update
    {
        return new Update();
    }
    
    public static function delete (): Delete
    {
        return new Delete();
    }
}