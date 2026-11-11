from __future__ import annotations

import calendar
from datetime import date

WEEKDAYS_RU = (
    "понедельник", "вторник", "среда", "четверг",
    "пятница", "суббота", "воскресенье",
)

DIGITS = {
    "0": (" *** ", "*   *", "*   *", "*   *", " *** "),
    "1": ("  *  ", " **  ", "  *  ", "  *  ", " *** "),
    "2": (" *** ", "*   *", "   * ", "  *  ", "*****"),
    "3": ("**** ", "    *", " *** ", "    *", "**** "),
    "4": ("*   *", "*   *", "*****", "    *", "    *"),
    "5": ("*****", "*    ", "**** ", "    *", "**** "),
    "6": (" *** ", "*    ", "**** ", "*   *", " *** "),
    "7": ("*****", "    *", "   * ", "  *  ", "  *  "),
    "8": (" *** ", "*   *", " *** ", "*   *", " *** "),
    "9": (" *** ", "*   *", " ****", "    *", " *** "),
}

def make_date(day: int, month: int, year: int) -> date:
    return date(year, month, day)

def weekday_name(value: date) -> str:
    return WEEKDAYS_RU[value.weekday()]

def is_leap_year(year: int) -> bool:
    return calendar.isleap(year)

def calculate_age(birth_date: date, today: date | None = None) -> int:
    current_date = today or date.today()
    if birth_date > current_date:
        raise ValueError("Дата рождения не может быть позже текущей даты.")
    birthday_has_passed = (current_date.month, current_date.day) >= (birth_date.month, birth_date.day)
    return current_date.year - birth_date.year - (not birthday_has_passed)

def format_date(value: date) -> str:
    return value.strftime("%d %m %Y")

def render_star_date(value: date) -> str:
    text = format_date(value)
    rows = []
    for row_index in range(5):
        parts = []
        for char in text:
            parts.append("   " if char == " " else DIGITS[char][row_index])
        rows.append(" ".join(parts).rstrip())
    return "\n".join(rows)

def read_integer(prompt: str) -> int:
    while True:
        try:
            return int(input(prompt).strip())
        except ValueError:
            print("Ошибка: введите целое число.")

def read_birth_date() -> date:
    while True:
        day = read_integer("Введите день рождения: ")
        month = read_integer("Введите месяц рождения: ")
        year = read_integer("Введите год рождения: ")
        try:
            birth_date = make_date(day, month, year)
            if birth_date > date.today():
                print("Ошибка: дата рождения не может быть в будущем. Повторите ввод.\n")
                continue
            return birth_date
        except ValueError:
            print("Ошибка: такой календарной даты не существует. Повторите ввод.\n")

def main() -> None:
    print("Программа обработки даты рождения")
    print("-" * 34)
    birth_date = read_birth_date()
    print(f"\nДата рождения: {format_date(birth_date)}")
    print(f"День недели: {weekday_name(birth_date)}")
    print("Год високосный: " + ("да" if is_leap_year(birth_date.year) else "нет"))
    print(f"Возраст: {calculate_age(birth_date)}")
    print("\nДата рождения крупными цифрами:\n")
    print(render_star_date(birth_date))

if __name__ == "__main__":
    main()
