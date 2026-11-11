from django.db import models

class Visitor(models.Model):
    name = models.CharField("Имя", max_length=100)

    def __str__(self):
        return self.name
