# Список студентов | Students list

Учебная задача по реализации сайта регистрации абитуриентов с соответствующими баллами ЕГЭ.

## Требования к установке

* PHP 8.1+
* Composer
* Apache с настроенным DocumentRoot на папку /public (пример настройки ниже)

## Установка

Перейдите в папку, в которой хотите поместить проект, и клонируйте репозиторий:

```sh
$ git clone https://github.com/olegeliseev/Students.git
```

Выделите виртуальный хост под этот проект и настройте его следующим образом:

```apacheconf
<VirtualHost *:80>
    DocumentRoot "/path/to/students/public"
    ServerName students.loc
    
  <Directory /path/to/students/public>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
  </Directory>
</VirtualHost>
```

Перейдите в папку проекта и выполните:

```sh
$ composer install
```

Создайте базу данных и импортируйте SQL дамп, находящийся в корневой папке.

Скопируйте содержимое `config.php.example` в новый файл `config.php` (папка `src`):

```sh
$ cp config.php.example config.php
```

Измените параметры в конфиге так, чтобы они соответствовали параметрам созданной вами базы данных.

## Функциональность проекта

* Регистрация нового абитуриента
* Зарегистрированный абитуриент может просматривать свой профиль и редактировать информацию о себе
* Просмотр списка всех зарегистрированных абитуриентов
* Сортировка по любому столбцу списка
* Поиск по любому столбцу списка

## Скриншоты
![main](https://github.com/olegeliseev/Students/assets/66223707/5afce160-7e9f-4d6d-8a66-aac0e358e63c)
![search](https://github.com/olegeliseev/Students/assets/66223707/a16027f2-b032-4e3b-8917-8588552798d7)
![register](https://github.com/olegeliseev/Students/assets/66223707/771f71f8-1a0a-455a-acd4-ea6f2cf441c2)
![login](https://github.com/olegeliseev/Students/assets/66223707/3993c9e2-50a7-4848-9638-bcb385fed6ec)


