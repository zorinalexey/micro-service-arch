<?php

namespace App\Commands\DB;

use App\Core\Console\Command;
use PDO;
use PDOException;

final class DumpCreateCommand extends Command
{
    
    protected function handle (): bool
    {
        try {
            // Создаем новое PDO соединение
            $pdo = db()->getConnection();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Открываем файл для записи дампа
            $dumpFile = '/dump/database_dump.sql';
            $handle = fopen($dumpFile, 'w');
            
            // Получаем список таблиц в базе данных
            $tables = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'")->fetchAll(PDO::FETCH_COLUMN);
            
            // Шаг 2: Извлечение схемы таблиц
            foreach ($tables as $table) {
                // Получаем DDL (структуру таблицы)
                $createTableSql = $pdo->query("SELECT table_schema, table_name, column_name, data_type, is_nullable
                                         FROM information_schema.columns
                                         WHERE table_name = '$table'
                                         ORDER BY ordinal_position")->fetchAll(PDO::FETCH_ASSOC);
                
                $ddl = "CREATE TABLE $table (\n";
                foreach ($createTableSql as $column) {
                    $nullable = $column['is_nullable'] == 'YES' ? 'NULL' : 'NOT NULL';
                    $ddl .= "    " . $column['column_name'] . " " . strtoupper($column['data_type']) . " $nullable,\n";
                }
                $ddl = rtrim($ddl, ",\n") . "\n);\n\n";
                fwrite($handle, $ddl);
                
                // Шаг 3: Извлечение данных
                $data = $pdo->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($data as $row) {
                    $values = array_map(function ($value) use ($pdo){
                        return "'" . $pdo->quote($value) . "'";
                    }, $row);
                    $insertSql = "INSERT INTO $table VALUES (" . implode(',', $values) . ");\n";
                    fwrite($handle, $insertSql);
                }
            }
            
            fclose($handle);
            echo "Дамп базы данных успешно создан в файле $dumpFile.";
            
            return true;
        } catch (PDOException $e) {
            echo "Ошибка подключения: " . $e->getMessage();
        }
        
        return false;
    }
    
}