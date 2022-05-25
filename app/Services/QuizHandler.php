<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizPassed;
use App\Models\Video;
use Config;
use Illuminate\Support\Facades\Auth;

class QuizHandler
{

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
     *
     * @param  Int  $order_id
     * @return Bool
     */
    private function doUpdateLevel($order_id): Bool
    {
        $order = Order::where('id', $order_id)
            ->where('user_id', Auth::id())
            ->first();

        $currentLevel = $order->level;
        $maxLevel = Config::get('constants.max_level'); 

        if ($currentLevel >= $maxLevel) {
            return false;
        }

        $videos = Video::where('seminar_id', $order->seminar_id)
            ->where('level', $currentLevel)
            ->with('quizzes')
            ->get();

        $quizzessIds = $this->composeQuizzesIds($videos);

        $passedQuizzesIds = QuizPassed::whereIn('quiz_id', $quizzessIds)
            ->where('user_id', Auth::id())
            ->pluck('quiz_id')
            ->toArray();

        sort($quizzessIds);
        sort($passedQuizzesIds);

        print_r($quizzessIds);
        print_r($passedQuizzesIds);

        return ($quizzessIds == $passedQuizzesIds) ?? false;

    }

    /**
     * Добавляем запись в таблицу, если квиз пройден
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
        Order::where('id', $order_id)
            ->where('user_id', Auth::id())
            ->increment('level', 1);
    }

    /**
     * Сборка массива всех Ids квизов данного уровня
     *
     * @param  Iterator  $videos
     * @return Array
     */
    private function composeQuizzesIds(Iterable $videos): Array
    {
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

}
