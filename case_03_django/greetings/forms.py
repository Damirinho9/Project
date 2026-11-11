from django import forms
from .models import Visitor

class VisitorForm(forms.ModelForm):
    class Meta:
        model = Visitor
        fields = ["name"]
        error_messages = {"name": {"required": "Введите имя."}}
        widgets = {
            "name": forms.TextInput(
                attrs={
                    "placeholder": "Введите ваше имя",
                    "autocomplete": "name",
                    "maxlength": "100",
                    "autofocus": True,
                }
            )
        }

    def clean_name(self):
        name = self.cleaned_data["name"].strip()
        if not name:
            raise forms.ValidationError("Введите имя.")
        return name
