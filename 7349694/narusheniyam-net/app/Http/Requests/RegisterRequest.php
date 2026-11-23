<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() === null; }
    public function rules(): array
    {
        return [
            'fio' => ['required','string','max:180','regex:/^[А-Яа-яЁё]+(?:-[А-Яа-яЁё]+)*\s+[А-Яа-яЁё]+(?:-[А-Яа-яЁё]+)*\s+[А-Яа-яЁё]+(?:-[А-Яа-яЁё]+)*$/u'],
            'login' => ['required','string','min:4','max:50','regex:/^[A-Za-z0-9]+$/','unique:users,login'],
            'email' => ['required','email:rfc','max:255','unique:users,email'],
            'password' => ['required','string','min:6','max:255'],
            'phone' => ['required','regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/'],
        ];
    }
    public function messages(): array
    {
        return [
            'fio.regex'=>'ФИО должно состоять ровно из трёх слов кириллицей; допустимы дефисы.',
            'login.regex'=>'Логин может содержать только латинские буквы и цифры.',
            'login.unique'=>'Этот логин уже занят.','email.unique'=>'Этот email уже используется.',
            'phone.regex'=>'Телефон должен быть в формате +7(XXX)XXX-XX-XX.',
        ];
    }
}
