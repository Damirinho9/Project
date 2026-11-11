# Кейс 3. Django-приложение

Главная страница содержит форму имени. После отправки имя сохраняется в SQLite и выводится персональное приветствие.

В проекте используются:

- модель `Visitor` с полем `name`;
- `ModelForm`;
- сохранение через Django ORM;
- проверка пустого имени;
- `{% csrf_token %}`;
- HTML-шаблон и CSS.

## Установка и запуск

Из корня репозитория:

```bash
python -m venv .venv
python -m pip install -r requirements.txt
```

Затем:

```bash
cd case_03_django
python manage.py migrate
python manage.py runserver
```

Адрес: `http://127.0.0.1:8000/`

## Тесты

```bash
python manage.py check
python manage.py makemigrations --check --dry-run
python manage.py test -v 2
```
