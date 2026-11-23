<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Report extends Model
{
    protected $fillable = ['user_id','category_id','title','description','status','date_incident','contact','review'];
    protected function casts(): array { return ['date_incident'=>'date']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function getStatusLabelAttribute(): string { return ['new'=>'Новое','resolved'=>'Решено','rejected'=>'Отклонено'][$this->status] ?? $this->status; }
}
