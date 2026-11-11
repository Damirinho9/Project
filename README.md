# synergy-technology-practice

Учебные проекты по технологической практике.

## Структура

```text
synergy-technology-practice/
├── case_01_birthday/
├── case_02_counter/
├── case_03_django/
├── case_04_calculator/
├── tests/
├── requirements.txt
└── README.md
```

## Кейс №1 - дата рождения

Консольная программа на Python принимает дату рождения, определяет день недели, високосность года и текущий возраст, после чего выводит дату крупными цифрами из символов `*`.

Запуск:

```bash
cd case_01_birthday
python birthday.py
```

Тесты:

```bash
python -m unittest -v
```

## Кейс №2 - счетчик

Браузерное приложение на HTML, CSS и JavaScript. Значение меняется в диапазоне от -10 до 10, цвет результата зависит от знака, а на границах отключается соответствующая кнопка.

Запуск: открыть `case_02_counter/index.html` в браузере.

Тесты:

```bash
cd case_02_counter
node --test test_counter.js test_dom.js
```

## Кейс №3 - Django-приложение

Форма сохраняет введенное имя в SQLite и выводит персонализированное приветствие. Используются Django ORM, ModelForm, шаблоны, статические файлы и CSRF-защита.

Установка и запуск:

```bash
python -m pip install -r requirements.txt
cd case_03_django
python manage.py migrate
python manage.py runserver
```

Проверка:

```bash
python manage.py check
python manage.py makemigrations --check --dry-run
python manage.py test -v 2
```

## Кейс №4 - калькулятор

Браузерный калькулятор с операциями сложения, вычитания, умножения и деления. Предусмотрены проверка ввода и обработка деления на ноль.

Запуск: открыть `case_04_calculator/index.html` в браузере.

Тесты:

```bash
cd case_04_calculator
node --test test_calculator.js test_dom.js
```

## Общая проверка

Из корня репозитория:

```bash
python tests/run_all_tests.py
```

Контрольный прогон: кейс №1 - 10/10 тестов, счетчик - 9/9, калькулятор - 13/13. Django-проект прошел статическую проверку структуры и синтаксиса. Полный runtime-прогон Django выполняется после установки зависимости из `requirements.txt`.
