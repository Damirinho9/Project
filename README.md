# synergy-technology-practice

Четыре проекта, выполненные по заданиям технологической практики.

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

## Кейс 1 - дата рождения

Python-программа запрашивает дату рождения, определяет день недели, проверяет високосность года, рассчитывает возраст и печатает дату символами `*`.

```bash
cd case_01_birthday
python birthday.py
python -m unittest -v
```

## Кейс 2 - счетчик

HTML/CSS/JavaScript-счетчик работает в диапазоне от -10 до 10. Цвет поля зависит от знака числа. На значениях -10 и 10 соответствующая кнопка блокируется.

Открыть `case_02_counter/index.html` в браузере.

```bash
cd case_02_counter
node --test test_counter.js test_dom.js
```

## Кейс 3 - Django

Форма сохраняет имя в SQLite и выводит персональное приветствие. Используются модель `Visitor`, `ModelForm`, ORM, шаблон, CSS и CSRF-токен.

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

## Кейс 4 - калькулятор

HTML/CSS/JavaScript-калькулятор выполняет сложение, вычитание, умножение и деление. Пустой ввод, текст вместо числа и деление на ноль обрабатываются как ошибки.

Открыть `case_04_calculator/index.html` в браузере.

```bash
cd case_04_calculator
node --test test_calculator.js test_dom.js
```

## Общая проверка

```bash
python tests/run_all_tests.py
```

Контрольный прогон 11.11.2026: кейс 1 - 10/10 тестов, счетчик - 9/9, калькулятор - 13/13. Структура и синтаксис Django-проекта проверены отдельно. Полные Django-тесты запускаются после установки зависимости из `requirements.txt`.
