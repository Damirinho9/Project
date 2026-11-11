import unittest
from datetime import date
from birthday import calculate_age, format_date, is_leap_year, make_date, render_star_date, weekday_name

class BirthdayTests(unittest.TestCase):
    def test_weekday(self):
        self.assertEqual(weekday_name(date(2000, 1, 10)), "понедельник")

    def test_leap_year(self):
        self.assertTrue(is_leap_year(2000))
        self.assertTrue(is_leap_year(2024))
        self.assertFalse(is_leap_year(1900))
        self.assertFalse(is_leap_year(2023))

    def test_age_after_birthday(self):
        self.assertEqual(calculate_age(date(2000, 1, 10), date(2026, 11, 11)), 26)

    def test_age_before_birthday(self):
        self.assertEqual(calculate_age(date(2000, 12, 10), date(2026, 11, 11)), 25)

    def test_future_birth_date(self):
        with self.assertRaises(ValueError):
            calculate_age(date(2030, 1, 1), date(2026, 11, 11))

    def test_valid_leap_day(self):
        self.assertEqual(make_date(29, 2, 2024), date(2024, 2, 29))

    def test_invalid_leap_day(self):
        with self.assertRaises(ValueError):
            make_date(29, 2, 2023)

    def test_invalid_calendar_date(self):
        with self.assertRaises(ValueError):
            make_date(31, 4, 2020)

    def test_date_format(self):
        self.assertEqual(format_date(date(2000, 1, 10)), "10 01 2000")

    def test_star_output_has_five_rows(self):
        output = render_star_date(date(2000, 1, 10))
        self.assertEqual(len(output.splitlines()), 5)
        self.assertIn("*", output)

if __name__ == "__main__":
    unittest.main()
