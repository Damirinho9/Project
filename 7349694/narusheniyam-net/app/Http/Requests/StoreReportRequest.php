<?php
namespace App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreReportRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array
    {
        return [
            'title'=>['required','string','min:5','max:180'],
            'category_id'=>['required','integer','exists:categories,id'],
            'description'=>['required','string','min:10','max:5000'],
            'date_incident'=>[
                'required','regex:/^\d{2}\.\d{2}\.\d{4}$/',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    try {
                        $date = Carbon::createFromFormat('d.m.Y', $value)->startOfDay();
                        if ($date->format('d.m.Y') !== $value) $fail('Введите реальную дату в формате ДД.ММ.ГГГГ.');
                        elseif ($date->isFuture()) $fail('Дата нарушения не может быть в будущем.');
                    } catch (\Throwable) { $fail('Введите дату в формате ДД.ММ.ГГГГ.'); }
                },
            ],
            'contact'=>['required',Rule::in(['email','sms'])],
        ];
    }
    public function messages(): array { return ['category_id.exists'=>'Выберите существующую категорию.','contact.in'=>'Выберите Email или SMS на телефон.']; }
}
