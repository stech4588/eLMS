<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizAttempt;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $quiz->load('questions.answers');

        return Inertia::render('Quiz/Take', [
            'quiz' => $quiz,
        ]);
    }

    public function storeAttempt(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer|exists:answers,id',
        ]);

        $score = 0;
        $userAnswers = $validated['answers'];

        foreach ($quiz->questions as $question) {
            $correctAnswer = $question->answers()->where('is_correct', true)->first();
            if (in_array($correctAnswer->id, $userAnswers)) {
                $score++;
            }
        }

        $attempt = $quiz->attempts()->create([
            'user_id' => Auth::id(),
            'score' => $score,
        ]);

        foreach ($userAnswers as $questionId => $answerId) {
            $attempt->answers()->create([
                'question_id' => $questionId,
                'answer_id' => $answerId,
            ]);
        }

        $points = $score * 10; // 10 points for each correct answer
        Auth::user()->increment('points', $points);

        return redirect()->route('quiz.result', ['attempt' => $attempt->id]);
    }

    public function result(QuizAttempt $attempt)
    {
        $attempt->load('quiz.questions.answers', 'answers');

        return Inertia::render('Quiz/Result', [
            'attempt' => $attempt,
        ]);
    }
}
