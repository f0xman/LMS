<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizPassed;
use App\Models\Video;
use Config;
use Illuminate\Support\Facades\Auth;

class QuizService
{

    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Сборка массива ответов квиза. 
     * order_id - нужен для обновления level при проверке ответов
     *
     * @param  Iterable  $questions
     * @param  Int $seminar_id
     * @return Array
     */
    public function composeQuestions(Iterable $questions, Int $seminar_id): Array
    {
        $newQuestions = array();
        $i = 0;
        foreach($questions as $question) {
            $quests = array($question->v1, $question->v2, $question->v3, $question->v4);
            shuffle($quests);
            $newQuestions[$i]["id"] =  $question->id;
            $newQuestions[$i]["question"] = $question->question;
            $newQuestions[$i]["answers"] = $quests;
            $newQuestions[$i]["order_id"] = $this->order->getOrderIdBySeminarId($seminar_id, Auth::id());
            $i++;   
        }
        shuffle($newQuestions);
        return $newQuestions;
    }

    /**
     * Сравнение массивов.
     * 1 - варианты ответов пользователя.
     * 2 - правильные варианты.
     *
     * @param  Int  $order_id
     * @return Array
     */
    public function checkAnswers(array $request): Array
    {
        $quizId = $request['quiz_id'];
        $orderId = $request['order_id'];
        $correctAnswers = $this->composeRightAnswers($quizId);
        $checkingAnswers = $request['answers'];
        $result = array('error' => 'Тест не пройден. Есть неверные ответы.');

        sort($correctAnswers);
        sort($checkingAnswers);

        if ($correctAnswers == $checkingAnswers) {

            $this->updateQuiz($quizId);

            if ($this->doUpdateLevel($orderId)) {
                $this->updateLevel($orderId);
            } 

            $result = array('success' => 'Тест пройден. Отличный результат.');
        }

        return $result;
    }

    /**
     * Сборка массива правильных ответов квиза для сравнения с результатом.
     *
     * @param  Int  $quiz_id
     * @return Array
     */
    private function composeRightAnswers($quiz_id): Array
    {
        return Question::where('quiz_id', $quiz_id)->pluck('v1')->toArray();
    }

    /**
     * Проверка, нужно ли обновлять уровень пользователя в заказе
     * Логика: 
     * 1. Собираем все, доступные для текущего уровня пользователя, видео и привязанные к этим видео квизы
     * 2. Формируем, из полученных квизов, массив id всех этих квизов
     * 3. Формируем массив id всех пройденных квизов, на основе массива выше (2.)
     * 4. Сравним массивы. Если идентичны, значит пользователь прошел все квизы своего уровня, в рамках семинара | true
     *
     * @param  Int  $order_id
     * @return Bool
     */
    private function doUpdateLevel($order_id): Bool
    {
        $order = $this->order::where('id', $order_id)
            ->where('user_id', Auth::id())
            ->first();

        $currentLevel = $order->level;
        $maxLevel = Config::get('constants.max_level'); 

        if ($currentLevel >= $maxLevel) {
            return false;
        }

        $availableQuizzessIds = $this->composeAvailableQuizzesIds($order->seminar_id, $currentLevel);
 
        $passedQuizzesIds = $this->composePassedQuizzesIds($availableQuizzessIds); 

        sort($availableQuizzessIds);
        sort($passedQuizzesIds);

        //print_r($availableQuizzessIds);
        //print_r($passedQuizzesIds);

        return ($availableQuizzessIds == $passedQuizzesIds) ?? false;

    }

    /**
     * Выберем все доступные квизы для уровня юзера, в рамках данного семинара
     * Сборка массива всех Ids квизов данного уровня в рамках семинара
     *
     * @return Array
     */
    private function composeAvailableQuizzesIds(Int $seminarId, Int $currentLevel): Array
    {        
        
        $videos = Video::where('seminar_id', $seminarId)
                        ->where('level', $currentLevel)
                        ->with('quizzes')
                        ->get();
        
        $quizzesIds = array();

        foreach ($videos as $video) {

            if(!empty($video->quizzes)) {

                foreach($video->quizzes as $quiz) {
                    if ($quiz->id > 0) {
                        $quizzesIds[] = $quiz->id;
                    }
                } 
            }            
        }
        return $quizzesIds;
    }

    /**
     * На основе массива availableQuizzessIds
     * сформируем массив IDS всех пройденных пользователем квизов, в рамках данного семинара  
     *
     * @return Array
     */
    private function composePassedQuizzesIds(Array $availableQuizzessIds): Array
    {                
        return QuizPassed::whereIn('quiz_id', $availableQuizzessIds)
                            ->where('user_id', Auth::id())
                            ->pluck('quiz_id')
                            ->toArray();
    }

    /**
     * Добавляем/обновляем запись в таблице, если квиз пройден
     *
     * @param  Int  $quiz_id
     * @return Void
     */
    private function updateQuiz(Int $quiz_id): Void
    {
        QuizPassed::firstOrCreate([
            'quiz_id' => $quiz_id,
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Обновление уровня пользователя в заказе
     *
     * @param  Int  $order_id
     * @return Void
     */
    private function updateLevel(Int $order_id): Void
    {
        $this->order::where('id', $order_id)
            ->where('user_id', Auth::id())
            ->increment('level', 1);
    }


}
