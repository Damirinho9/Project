from django.shortcuts import render
from .forms import VisitorForm

def home(request):
    greeting = None

    if request.method == "POST":
        form = VisitorForm(request.POST)
        if form.is_valid():
            visitor = form.save()
            greeting = f"Здравствуйте, {visitor.name}!"
            form = VisitorForm()
    else:
        form = VisitorForm()

    return render(
        request,
        "greetings/home.html",
        {"form": form, "greeting": greeting},
    )
