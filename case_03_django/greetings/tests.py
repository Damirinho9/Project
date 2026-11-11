from django.test import TestCase
from django.urls import reverse

from .forms import VisitorForm
from .models import Visitor

class VisitorFormTests(TestCase):
    def test_valid_name(self):
        form = VisitorForm({"name": "Максим"})
        self.assertTrue(form.is_valid())
        self.assertEqual(form.cleaned_data["name"], "Максим")

    def test_blank_name_is_rejected(self):
        form = VisitorForm({"name": "   "})
        self.assertFalse(form.is_valid())
        self.assertIn("Введите имя.", form.errors["name"])

class HomeViewTests(TestCase):
    def test_home_page_opens(self):
        response = self.client.get(reverse("greetings:home"))
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, "Добро пожаловать")
        self.assertContains(response, "csrfmiddlewaretoken")

    def test_valid_post_saves_name_and_shows_greeting(self):
        response = self.client.post(reverse("greetings:home"), {"name": "Максим"})
        self.assertEqual(response.status_code, 200)
        self.assertEqual(Visitor.objects.count(), 1)
        self.assertEqual(Visitor.objects.get().name, "Максим")
        self.assertContains(response, "Здравствуйте, Максим!")

    def test_empty_post_does_not_save(self):
        response = self.client.post(reverse("greetings:home"), {"name": ""})
        self.assertEqual(response.status_code, 200)
        self.assertEqual(Visitor.objects.count(), 0)
        self.assertContains(response, "Введите имя.")

    def test_spaces_only_do_not_save(self):
        response = self.client.post(reverse("greetings:home"), {"name": "   "})
        self.assertEqual(response.status_code, 200)
        self.assertEqual(Visitor.objects.count(), 0)
        self.assertContains(response, "Введите имя.")
