<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class LoginRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() === null; }
    public function rules(): array { return ['login'=>['required','string'],'password'=>['required','string']]; }
}
