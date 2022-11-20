<a name="readme-top"></a>

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/rsidash">
    <img src="https://static.tildacdn.com/tild6339-3938-4562-a564-666339393665/logo.svg" alt="Logo" width="200" height="200">
  </a>

<h3 align="center">Kolesa Upgrade PHP Web-service</h3>

  <p align="center">
    Веб-сервис для отправки сообщений через API телеграм бота
  </p>
</div>

<!-- TABLE OF CONTENTS -->
<details>
  <summary>Содержимое</summary>
  <ol>
    <li>
      <a href="#о-проекте">О проекте</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#начало-работы">Начало работы</a>
      <ul>
        <li><a href="#предустановка">Предустановка</a></li>
        <li><a href="#установка">Установка</a></li>
      </ul>
    </li>
    <li><a href="#применение">Применение</a></li>
    <li><a href="#контакты">Контакты</a></li>
    <li><a href="#особые-благодарности">Особые благодарности</a></li>
  </ol>
</details>

<!-- ABOUT THE PROJECT -->
### О проекте

Веб-сервис, который можно использовать для отправки сообщений через API телеграм бота. Сервис имеет возможность просмотра истории сообщений, а также отправку неотправленных уведомлений

<p align="right">(<a href="#readme-top">back to top</a>)</p>


### Built With

* Slim Framework
* Doctrine
* MySQL

<p align="right">(<a href="#readme-top">back to top</a>)</p>


<!-- GETTING STARTED -->
### Начало работы

Здесь описаны иснтрукции по локальной настройке проекта.
Для получения локальной копии выполните эти шаги.

### Предустановка

Убедитесь, что у Вас установлены php, mysql и composer

### Установка

1. Склонируйте репозиторий
   ```sh
   git clone git@github.com:lesmiras/kolesa-upgrade-team1-form.git
   ```
2. Установите зависимости
   ```sh
   composer install
   ```
   Список зависимостей:
* "slim/slim": "^4.11",
* "slim/psr7": "^1.6",
* "slim/http": "^1.3",
* "slim/twig-view": "^3.3",
* "vlucas/phpdotenv": "^5.5",
* "doctrine/orm": "^2.13",
* "symfony/cache": "^6.0",
* "doctrine/migrations": "^3.5",
* "ext-pdo": "*",
* "ext-json": "*",
* "guzzlehttp/guzzle": "^7.5"
* "phpunit/phpunit": "^9.5"
3. Файл переменных среды окружения .env
   Переименуйте файл .env.example в .env:
   ```js
   mv .env.example .env
   ```
   Заполните файл своими переменными
4. Запустите локальный веб-сервер
   ```sh
   php -S localhost:8000
   ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- USAGE EXAMPLES -->
### Применение
По маршруту "/" Вас приветствует домашняя страница веб-сервиса, передав GET-параметр name, можно установить имя в форме приветствия:
![image](https://user-images.githubusercontent.com/61940196/202906242-127709c4-e12a-41cc-8727-242847be58c7.png)
При нажатии на кнопку "Отправка сообщений" Вы попадаете на страницу "Сервис отправки сообщений":
![image](https://user-images.githubusercontent.com/61940196/202906294-4f2d9b75-af55-4639-b9ea-c1cfc3cb6d37.png)
На данной странице Вы можете отправить Ваше сообщение, которое будет передано через API телеграм бота. Перед отправкой удостоверьтесь, что Ваше сообщение проходит валидацию данных:
![image](https://user-images.githubusercontent.com/61940196/202906354-02ff64e1-d60b-4d47-bb0e-541673fb21b6.png)
В случае успешной отправки сообщения выйдет соответствующее уведомление:
![image](https://user-images.githubusercontent.com/61940196/202906418-987dbed5-ee14-443e-9b0c-8977cf6a25a2.png)
При проблемах работы сервиса телеграм бота, Вы увидите сообщение с ошибкой:
![image](https://user-images.githubusercontent.com/61940196/202906455-8b3241dd-ddee-4a98-bed9-477ec8977fe2.png)
При нажатии на кнопку "История сообщений" Вы попадаете на соответствующую страницу:
![image](https://user-images.githubusercontent.com/61940196/202906481-7dd83d4e-764f-4abb-b70e-f206c499ef0f.png)
Сервис имеет возможность отправки всех сообщений, которые были недоставлены по какой либо причине. Для этого необходимо нажать кнопку "Отправить неотправленные".
Для удобства на странице расположены элементы управления для отображения Отправленных/Неотправленных сообщений и сортировке сообщений по дате.
Также, Вы можете перейти на нужную Вам страницу с помощью меню в заголовке страницы

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- CONTACT -->
### Контакты

Роман Сидаш - [@rsidash](https://github.com/rsidash) - [linkedin](https://www.linkedin.com/in/roman-sidash-3b29a91a3/) - r.sidash93@gmail.com

Project Link: [https://github.com/lesmiras/kolesa-upgrade-team1-form](https://github.com/lesmiras/kolesa-upgrade-team1-form)

<p align="right">(<a href="#readme-top">back to top</a>)</p>


<!-- ACKNOWLEDGMENTS -->
### Особые благодарности

* []() https://github.com/yelamanfazyl
* []() https://github.com/lesmiras
* []() Всей команде Kolesa Upgrade :)

<p align="right">(<a href="#readme-top">back to top</a>)</p>