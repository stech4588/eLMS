<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Log;

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
        Log::info('Quiz attempt started.', ['quiz_id' => $quiz->id, 'user_id' => Auth::id()]);
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer|exists:answers,id',
        ]);

        $score = 0;
        $userAnswers = $validated['answers'];

        foreach ($quiz->questions as $question) {
            Log::info('Processing question.', ['question_id' => $question->id]);
            $correctAnswer = $question->answers()->where('is_correct', true)->first();
            
            if (!$correctAnswer) {
                Log::error('No correct answer found for question.', ['question_id' => $question->id]);
                // Depending on your application's rules, you might want to handle this differently.
                // For now, we'll just log it and continue, which means the user won't get a point.
                continue;
            }

            if (in_array($correctAnswer->id, $userAnswers)) {
                $score++;
            }
        }

        Log::info('Quiz score calculated.', ['score' => $score]);

        $attempt = $quiz->attempts()->create([
            'user_id' => Auth::id(),
            'score' => $score,
        ]);

        foreach ($validated['answers'] as $questionId => $answerId) {
            $question = $quiz->questions()->find($questionId);
            if ($question) {
                $attempt->answers()->create([
                    'question_id' => $question->id,
                    'answer_id' => $answerId,
                ]);
            }
        }

        $points = $score * 10; // 10 points for each correct answer
        Auth::user()->increment('points', $points);

        Log::info('Quiz attempt finished successfully.', ['attempt_id' => $attempt->id]);
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
