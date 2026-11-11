"""Статическая проверка кейса №3 без необходимости импортировать Django."""
from __future__ import annotations

import ast
from pathlib import Path

ROOT = Path(__file__).resolve().parent

REQUIRED = [
    "manage.py",
    "greeting_project/settings.py",
    "greeting_project/urls.py",
    "greetings/models.py",
    "greetings/forms.py",
    "greetings/views.py",
    "greetings/urls.py",
    "greetings/tests.py",
    "greetings/migrations/0001_initial.py",
    "greetings/templates/greetings/home.html",
    "greetings/static/greetings/style.css",
]


def check_python_syntax() -> None:
    for path in ROOT.rglob("*.py"):
        ast.parse(path.read_text(encoding="utf-8"), filename=str(path))


def check_required_files() -> None:
    missing = [item for item in REQUIRED if not (ROOT / item).exists()]
    if missing:
        raise AssertionError(f"Отсутствуют файлы: {missing}")


def check_requirements_in_code() -> None:
    model = (ROOT / "greetings/models.py").read_text(encoding="utf-8")
    form = (ROOT / "greetings/forms.py").read_text(encoding="utf-8")
    view = (ROOT / "greetings/views.py").read_text(encoding="utf-8")
    settings = (ROOT / "greeting_project/settings.py").read_text(encoding="utf-8")
    template = (ROOT / "greetings/templates/greetings/home.html").read_text(encoding="utf-8")

    assert 'name = models.CharField' in model
    assert 'fields = ["name"]' in form
    assert 'form.save()' in view
    assert 'django.db.backends.sqlite3' in settings
    assert '{% csrf_token %}' in template
    assert 'method="post"' in template
    assert '{{ greeting }}' in template


def main() -> None:
    check_required_files()
    check_python_syntax()
    check_requirements_in_code()
    print("Static verification: OK")


if __name__ == "__main__":
    main()
