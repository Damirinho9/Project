<?php
namespace Tests\Feature;
use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
class ExamWorkflowTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void { parent::setUp(); $this->seed(DatabaseSeeder::class); }
    private function user(string $login='user1'): User { return User::create(['login'=>$login,'password'=>Hash::make('secret1'),'fio'=>'Иванов Иван Иванович','phone'=>'+7(999)111-22-33','email'=>$login.'@example.test','role'=>'user']); }

    public function test_registration_validates_and_creates_user(): void
    {
        $r=$this->post('/register',['fio'=>'Петров Пётр Петрович','login'=>'petrov1','email'=>'petrov@example.test','phone'=>'+7(999)123-45-67','password'=>'secret1']);
        $r->assertRedirect('/reports/create'); $this->assertDatabaseHas('users',['login'=>'petrov1','role'=>'user']); $this->assertTrue(Hash::check('secret1',User::where('login','petrov1')->first()->password));
    }
    public function test_login_rejects_wrong_password(): void { $this->user(); $this->post('/login',['login'=>'user1','password'=>'bad'])->assertSessionHasErrors('login'); }
    public function test_guest_cannot_create_report(): void { $this->get('/reports/create')->assertRedirect('/login'); }
    public function test_user_can_create_report_and_only_sees_own(): void
    {
        $u1=$this->user('u111'); $u2=$this->user('u222'); $cat=Category::first();
        Report::create(['user_id'=>$u2->id,'category_id'=>$cat->id,'title'=>'Чужая заявка','description'=>'Чужое описание достаточно длинное','status'=>'new','date_incident'=>now()->toDateString(),'contact'=>'email']);
        $this->actingAs($u1)->post('/reports',['title'=>'Яма у школы','category_id'=>$cat->id,'description'=>'Большая яма рядом с главным входом','date_incident'=>now()->format('d.m.Y'),'contact'=>'sms'])->assertRedirect('/reports');
        $this->actingAs($u1)->get('/reports')->assertSee('Яма у школы')->assertDontSee('Чужая заявка');
    }
    public function test_regular_user_cannot_open_admin(): void { $this->actingAs($this->user())->get('/admin')->assertForbidden(); }
    public function test_admin_can_change_status(): void
    {
        $u=$this->user(); $cat=Category::first(); $report=Report::create(['user_id'=>$u->id,'category_id'=>$cat->id,'title'=>'Проблема','description'=>'Описание городской проблемы','status'=>'new','date_incident'=>now()->toDateString(),'contact'=>'email']);
        $admin=User::where('login','admin')->firstOrFail(); $this->actingAs($admin)->patch('/admin/reports/'.$report->id.'/status',['status'=>'resolved'])->assertRedirect(); $this->assertDatabaseHas('reports',['id'=>$report->id,'status'=>'resolved']);
    }
    public function test_review_only_for_resolved_own_report(): void
    {
        $u=$this->user(); $cat=Category::first(); $report=Report::create(['user_id'=>$u->id,'category_id'=>$cat->id,'title'=>'Проблема','description'=>'Описание городской проблемы','status'=>'new','date_incident'=>now()->toDateString(),'contact'=>'email']);
        $this->actingAs($u)->patch('/reports/'.$report->id.'/review',['review'=>'Хорошо'])->assertStatus(422);
        $report->update(['status'=>'resolved']); $this->actingAs($u)->patch('/reports/'.$report->id.'/review',['review'=>'Работы выполнены хорошо'])->assertRedirect(); $this->assertDatabaseHas('reports',['id'=>$report->id,'review'=>'Работы выполнены хорошо']);
    }
    public function test_admin_credentials_from_assignment_are_seeded(): void { $admin=User::where('login','admin')->firstOrFail(); $this->assertSame('admin',$admin->role); $this->assertTrue(Hash::check('adm123',$admin->password)); }
}
