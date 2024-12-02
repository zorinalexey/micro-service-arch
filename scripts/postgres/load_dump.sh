#!/bin/bash

if [ "$#" -ne 1 ]; then
    echo "Использование: $0 <имя файла дампа без .sql>"
    exit 1
fi

export PGPASSWORD=$DB_PASSWORD
DATABASE_NAME=$POSTGRES_DB
USER_NAME=$POSTGRES_USER
OUTPUT_DIR="/dump"
FILE_NAME=$1
DUMP_FILE="$OUTPUT_DIR/$FILE_NAME.sql"

if [ ! -f "$DUMP_FILE" ]; then
    echo "Файл дампа $DUMP_FILE не найден!"
    exit 1
fi

psql -U "$USER_NAME" -c "DROP DATABASE IF EXISTS $DATABASE_NAME;"
psql -U "$USER_NAME" -c "CREATE DATABASE $DATABASE_NAME;"

psql -U "$USER_NAME" -d "$DATABASE_NAME" < "$DUMP_FILE"

echo "Восстановление базы данных $DATABASE_NAME из дампа $DUMP_FILE завершено."