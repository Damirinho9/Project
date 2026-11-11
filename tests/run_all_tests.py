from __future__ import annotations

import importlib.util
import subprocess
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent


def run(title: str, command: list[str], cwd: Path | None = None) -> bool:
    print(f"\n=== {title} ===")
    result = subprocess.run(command, cwd=cwd or ROOT)
    if result.returncode == 0:
        print(f"[OK] {title}")
        return True
    print(f"[FAIL] {title} (код {result.returncode})")
    return False


def main() -> int:
    checks: list[bool] = []

    checks.append(
        run(
            "Кейс 1 - Python unit tests",
            [sys.executable, "-m", "unittest", "-v"],
            ROOT / "case_01_birthday",
        )
    )
    checks.append(
        run(
            "Кейс 2 - JavaScript unit + DOM tests",
            ["node", "--test", "test_counter.js", "test_dom.js"],
            ROOT / "case_02_counter",
        )
    )
    checks.append(
        run(
            "Кейс 3 - статическая проверка Django-проекта",
            [sys.executable, "verify_structure.py"],
            ROOT / "case_03_django",
        )
    )

    if importlib.util.find_spec("django") is not None:
        checks.append(
            run(
                "Кейс 3 - Django system check",
                [sys.executable, "manage.py", "check"],
                ROOT / "case_03_django",
            )
        )
        checks.append(
            run(
                "Кейс 3 - проверка миграций",
                [sys.executable, "manage.py", "makemigrations", "--check", "--dry-run"],
                ROOT / "case_03_django",
            )
        )
        checks.append(
            run(
                "Кейс 3 - Django tests",
                [sys.executable, "manage.py", "test", "-v", "2"],
                ROOT / "case_03_django",
            )
        )
    else:
        print("\n[SKIP] Полные Django runtime-тесты: пакет Django не установлен.")
        print("       Установите зависимости: python -m pip install -r requirements.txt")

    checks.append(
        run(
            "Кейс 4 - JavaScript unit + DOM tests",
            ["node", "--test", "test_calculator.js", "test_dom.js"],
            ROOT / "case_04_calculator",
        )
    )

    print("\n=== ИТОГ ===")
    if all(checks):
        print("Все доступные проверки завершены успешно.")
        return 0
    print("Есть ошибки. См. вывод выше.")
    return 1


if __name__ == "__main__":
    raise SystemExit(main())
