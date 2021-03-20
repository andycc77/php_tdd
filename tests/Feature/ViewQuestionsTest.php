<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Question;

class ViewQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function user_can_view_questions()
    {
        //0. 拋出異常
        $this->withoutExceptionHandling();

        //1.假設 /questions 路由存在
        //2. 訪問鏈結 /questions
        $test = $this->get('/questions');
        //3. 正常返回 200
        $test->assertStatus(200);
    }

    /** @test */
    public function user_can_view_a_published_question()
    {
        $question = factory(Question::class)->create(['published_at' => Carbon::parse('-1 week')]);

        $this->get('/questions/' . $question->id)
            ->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
    }

    /** @test */
    public function user_cannot_view_unpublished_question()
    {
        $question = factory(Question::class)->create(['published_at' => null]);

        $this->withExceptionHandling()
            ->get('/questions/' . $question->id)
            ->assertStatus(404);
    }
}
