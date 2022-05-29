<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Quiz;
use App\Models\QuizPassed;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{

    protected $service;

    public function __construct(QuizService $service, Order $order) {
        $this->service = $service;
        parent::__construct($order);
    }
    
    public function quiz($id) {

        $quiz = Quiz::where('id', $id)
                        ->with('video', 'questions')
                        ->first();

        if(!$this->isContentAvailable($quiz->seminar_id)) {
            return view('dashboard.error', ['error' => 'Этот семинар вам недоступен.']); 
        }

        // Соответствует ли текущий уровень пользователя, уровню доступа видео
        if ($this->currentUserLevel < $quiz->video->level) {
            return view('dashboard.error', ['error' => 'Это опрос вам недоступен.']);
        }
        
        $questions = $this->service->composeQuestions($quiz->questions, $quiz->seminar_id);

        return view('dashboard.quiz', ['quiz' => $quiz, 'questions' => $questions]);

    }

    /**
     * Проверка ответов
     *
     * @return Redirect
     */
    public function postAnswers(Request $request)
    {                
        $response = $this->service->checkAnswers($request->all());

        return redirect()
                ->route('showQuiz', ['id' => $request->input('quiz_id')])
                ->with(array_key_first($response), current($response));
    }
    
}
