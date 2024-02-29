# Дисклеймер
### Я хотел добавить docker(laravel sail) но по техническим причинам не могу этого сделать

# Требования
```bash
PHP ^8.1.x
laravel ^9.x
sqlite3
```
# Установка
### Клонируем репозиторий
```bash
git clone https://github.com/eyekeen/kvik_dil.git

cd kvik_dil
```
### Устанавливаем зависимости и копируем файл с .env.example
```bash
composer install
cp .env.example .env
```
### Выполняем миграции
```
php artisan migrate
```
### Запуск 
```bash
php artisan key:generate
php artisan serve
```

# API

- GET | HEAD /api/tasks<br>
Возвращает список задач из базы данных, применяя опциональные фильтры на основе указанных GET параметров. Принимает три параметра фильтрации: status, start_date и end_date
- POST /api/tasks<br>
Создает задачу в базе данных. Принимает след. POST параметры: name, description, status, start_date и end_date. Все параметры кроме name опциональные
- GET | HEAD /api/tasks/{id}<br>
Возвращает задачу с переданным id или код 404 если её не существуюет
- PUT | PATCH /api/tasks/{id}
Обноляет задачу с переданным id или код 404 если её не существуюет
- DELETE /api/tasks/{id}
Удаляет задачу с переданным id или код 404 если её не существуюет

