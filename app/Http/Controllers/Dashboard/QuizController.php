<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Quiz;
use App\Models\QuizPassed;
use App\Services\QuizHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    
    public function quiz($id, Order $order) {

        $quiz = Quiz::where('id', $id)
                        ->with('video', 'questions')
                        ->first();

        $_order = $order->isSeminarAvailable($quiz->video->seminar_id, Auth::id());

        if (!$_order) {
            return view('dashboard.error', ['error' => 'Этот семинар вам недоступен.']);
        }

        /// Текущий уровень ученика в контексте заказанного семинара
        $currentLevel = $_order->level;

        if ($currentLevel < $quiz->video->level) {
            return view('dashboard.error', ['error' => 'Это опрос вам недоступен.']);
        }
        
        $questions = $this->composeQuestions($quiz->questions, $_order->id);

        return view('dashboard.quiz', ['quiz' => $quiz, 'questions' => $questions]);

    }

    /**
     * Сборка массива ответов квиза. 
     * $order_id - нужен для обновления level при проверке ответов
     *
     * @param  Iterable  $questions
     * @param  Int  $order_id
     * @return Array
     */
    private function composeQuestions(Iterable $questions, $order_id): Array
    {
        $newQuestions = array();
        $i = 0;
        foreach($questions as $question) {
            $quests = array($question->v1, $question->v2, $question->v3, $question->v4);
            shuffle($quests);
            $newQuestions[$i]["id"] =  $question->id;
            $newQuestions[$i]["question"] = $question->question;
            $newQuestions[$i]["answers"] = $quests;
            $newQuestions[$i]["order_id"] = $order_id;
            $i++;   
        }
        shuffle($newQuestions);
        return $newQuestions;
    }


    /**
     * Постинг ответов на проверку
     *
     * @param  Request $request
     * @param  QuizHandler $handler
     * @return Response
     */
    public function postAnswers(Request $request, QuizHandler $handler)
    {
                
        $response = $handler->checkAnswers($request->all());

        return redirect()
                ->route('showQuiz', ['id' => $request->input('quiz_id')])
                ->with(array_key_first($response), current($response));
    }
    
}
