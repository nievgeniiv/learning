Инструкция для запусков тестов
---
   0. Установить _composer_ на локальный компьютер;
   0. Открыть терминал и перейти в нем в папку _learning1_;
   0. Выполнить команду `composer install`;
   0. Если у вас установлен Linux выполнить команду 
      `./vendor/bin/codeception bootstrap`, если у вас установлен Windows выполнить
      команду в терминале `/vendor/bin/codeception.bat bootstrap`;
   0. Внести данные в файле _dump.sql_, который находится в папке _tests/_data_, в базу 
      данных (БД) на локальном компьютере;
   0. Создать файл _config.php_ в корневой папке проекта по аналогии с шаблоном
      _config.php-template_.
   0. Скачать [_Selenium WebDriver_][1];
   0. Скачать [_ChromeDriver_][2];
   0. Скачать [_GeckoDriver_][3];
   0. Разархивировать _ChromeDriver_ и _Geckodriver_ в папку с _Selenium WebDriver_;
   0. Запустить _Selenium WebDriver_ командой `java -jar selenium-server-standalone-XXX.jar`;
   0. Запуск тестов осуществляется командой:
      для Linux `./vendor/bin/codeception run`;
      для Windows `vendor/bin/codeception.bat run`.
      
      
   [1]: https://www.selenium.dev/downloads/
   [2]: https://sites.google.com/a/chromium.org/chromedriver/
   [3]: https://github.com/mozilla/geckodriver/releases