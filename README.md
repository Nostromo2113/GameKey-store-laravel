# GameKey Store - Laravel REST API Dashboard
### Статус проекта: в разработке. Рабочее демо
Административная панель для управления магазином ключей активации игр с REST API интерфейсом.

Установка для ознакомления с проектом не требуется. Доступ к приложению доступен по ссылке:
 - Рабочая ссылка приложения <https://game-key-store-dashboard-front.vercel.app/>
 - Репозиторий интерфейса приложения <https://github.com/Nostromo2113/game_key_store_dashboard_front>

## Техстек  
- PHP 8.3, Laravel 11, MySQL  
- **Пакеты**: `tymon/jwt-auth`, Laravel Telescope  

## Интерфейс

### Магазин
![Магазин](https://i.imgur.com/PCLnq21.jpeg)
![Продукт в магазине](https://i.imgur.com/d8QVMZD.jpeg)
### Работа с заказом 
![Заказ](https://i.imgur.com/6TGkfJA.jpeg)
![Заказ](https://i.imgur.com/SvwgjGI.jpeg)

## Основные возможности

### Аутентификация и пользователи
- Регистрация новых пользователей
- Авторизация по JWT (tymon/jwt-auth)
- Восстановление пароля через email
- Смена пароля в личном кабинете
- **Управление пользователями (админ):**
  - Добавление/удаление
  - Редактирование данных
  - Загрузка и смена аватаров

### Магазин
- Просмотр каталога игр
- Система корзины
- Оформление заказов
- **Отправка ключей на email** (после фиктивной оплаты)
- **Комментарии к продуктам:**
  - Добавление/редактирование/удаление (автор или админ)

### Админ-панель
- **Управление продуктами:**
  - Просмотр/добавление/редактирование/удаление
  - Загрузка и изменение изображений
- **Управление ключами активации:**
  - Добавление/удаление ключей
  - Автоматический подбор ключей при изменении заказа
- **Управление заказами:**
  - Просмотр всех заказов
  - Редактирование (до завершения)
  - Пакетное обновление ключей

## Особенности реализации
### Система управления ключами
- **Жизненный цикл**:  
  `Свободные` → `Зарезервированные` → `Использованные` (soft delete)
- **Безопасные операции**:  
  Транзакции + массовые обновления через `OrderActivationKeyManager`
- **Автоматизация**:  
  - Динамический подбор при изменении заказа  
  - Проверка доступности перед резервированием
- **Архитектура**:  
  Репозиторий (`ActivationKeyRepositoryInterface`) + DI в сервисах

### Оптимизации
- **Очереди (Queues):**
  - Все письма с ключами отправляются через очереди
- **Минимальные запросы к БД:** пакетные операции с ключами
- **Eager Loading:** предотвращение N+1 проблем
- **Telescope:** мониторинг запросов и производительности

## Что отрабатывалось
Проект демонстрирует:
- Работу с REST API (JWT, CRUD)
- Бизнес-логику (управление ключами)
- Оптимизацию запросов (eager loading, транзакции)
- Асинхронные задачи (очереди)
- Безопасность (валидация, транзакции)

## Что в планах
- Вынести запросы на чтение в репозитории
- Добавить тесты
- Настроить передачу параметров через DTO
- Расширить возможности фильтрации и сорировки

## API
base url:  
 - `/api/admin`

## Authentication
Все маршруты кроме auth требуют JWT-токен в заголовке:
`Authorization: Bearer {token}`

## Маршруты

### Аутентификация
| Метод | Путь | Описание |
|-------|------|----------|
| POST | `/auth/login` | Вход в систему |
| POST | `/auth/logout` | Выход из системы |
| POST | `/auth/refresh` | Обновление токена |
| POST | `/auth/me` | Информация о текущем пользователе |
| POST | `/password/reset` | Сброс пароля |
| POST | `/password/change` | Изменение пароля |
| POST | `/registration` | Регистрация нового пользователя |

### Пользователи (требуется авторизация)
| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/users` | Список всех пользователей |
| POST | `/users` | Создание пользователя |
| GET | `/users/{user}` | Получение пользователя |
| PATCH | `/users/{user}` | Обновление пользователя |
| DELETE | `/users/{user}` | Удаление пользователя |
| GET | `/users/{user}/orders` | Список заказов пользователя |
| POST | `/users/{user}/orders` | Создание заказа для пользователя |
| DELETE | `/users/{user}/orders/{order}` | Удаление заказа пользователя |
| GET | `/users/{user}/cart` | Получение корзины пользователя |

### Категории (требуется авторизация)
| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/categories` | Список категорий |
| POST | `/categories` | Создание категории |
| PATCH | `/categories/{category}` | Обновление категории |
| DELETE | `/categories/{category}` | Удаление категории |

### Жанры (требуется авторизация)
| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/genres` | Список жанров |
| POST | `/genres` | Создание жанра |
| PATCH | `/genres/{genre}` | Обновление жанра |
| DELETE | `/genres/{genre}` | Удаление жанра |

### Продукты (требуется авторизация)
| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/products` | Список продуктов |
| POST | `/products` | Создание продукта |
| GET | `/products/{product}` | Получение продукта |
| PATCH | `/products/{product}` | Обновление продукта |
| DELETE | `/products/{product}` | Удаление продукта |
| GET | `/products/{product}/activation_keys` | Ключи активации продукта |
| POST | `/products/{product}/activation_keys` | Добавление ключей активации |
| GET | `/products/{product}/comments` | Комментарии продукта |
| POST | `/products/{product}/comments` | Добавление комментария |

### Комментарии (требуется авторизация)
| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/comments/{comment}` | Получение комментария |
| PATCH | `/comments/{comment}` | Обновление комментария |
| DELETE | `/comments/{comment}` | Удаление комментария |

### Ключи активации (требуется авторизация)
| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/activation_keys` | Список ключей активации |
| DELETE | `/activation_keys/{key}` | Удаление ключа активации |

### Корзина (требуется авторизация)
| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/cart` | Получение корзины |
| POST | `/cart/{cart}/products` | Добавление товара в корзину |
| PATCH | `/cart/{cart}/products/{product}` | Обновление товара в корзине |
| DELETE | `/cart/{cart}/products/{product}` | Удаление товара из корзины |

### Заказы (требуется авторизация)
| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/orders` | Список заказов |
| GET | `/orders/number/{order_number}` | Получение заказа по номеру |
| GET | `/orders/{order}` | Получение заказа |
| PATCH | `/orders/{order}` | Обновление заказа |
| PATCH | `/orders/{order}/products` | Пакетное обновление товаров в заказе |

### Статистика (требуется авторизация)
| Метод | Путь | Описание |
|-------|------|----------|
| GET | `/stats` | Получение статистики |








## Установка и запуск
при необходимости

```bash
# 1. Клонирование репозитория
git clone https://github.com/Nostromo2113/GameKey-store-laravel.git
cd repo

# 2. Установка зависимостей
composer install

# 3. Настройка окружения
cp .env.example .env

# 4. Генерация ключей
php artisan key:generate
php artisan jwt:secret

# 5. Настройка хранилища
php artisan storage:link

# 6. Запуск миграций
php artisan migrate

# 7. Запуск обработчика очередей (в отдельном терминале)
php artisan queue:work

# 8. Запуск сервера разработки
php artisan serve

