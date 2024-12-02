#!/bin/bash

export PGPASSWORD=$DB_PASSWORD
DATABASE_NAME=$POSTGRES_DB
USER_NAME=$POSTGRES_USER
OUTPUT_DIR="/dump"
TIMESTAMP=$(date +"%Y-%m-%d_%H-%M-%S")
DUMP_FILE="$OUTPUT_DIR/${DATABASE_NAME}_dump_$TIMESTAMP.sql"

echo "Создание дампа в $OUTPUT_DIR - $DUMP_FILE"
echo "Пользователь: $USER_NAME"
echo "База данных: $DATABASE_NAME"
echo "Пароль: $DB_PASSWORD"

pg_dump -U "$USER_NAME" "$DATABASE_NAME" > "$DUMP_FILE"

echo "Дамп базы данных $DATABASE_NAME сохранен в $DUMP_FILE"