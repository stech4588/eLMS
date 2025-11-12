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

        // Ensure we have questions and answers loaded to avoid N+1
        $quiz->load('questions.answers');

        $score = 0;
        $userAnswers = $validated['answers']; // expected format: [question_id => answer_id]
        $totalQuestions = $quiz->questions->count();

        foreach ($quiz->questions as $question) {
            $selectedAnswerId = (int) ($userAnswers[$question->id] ?? 0);
            if ($selectedAnswerId === 0) {
                continue;
            }
            $selected = $question->answers->firstWhere('id', $selectedAnswerId);
            if ($selected && (bool) $selected->is_correct) {
                $score++;
            }
        }

        $attempt = $quiz->attempts()->create([
            'user_id' => Auth::id(),
            'score' => $score,
        ]);

        foreach ($validated['answers'] as $questionId => $answerId) {
            $question = $quiz->questions->firstWhere('id', (int) $questionId);
            if ($question) {
                $attempt->answers()->create([
                    'question_id' => $question->id,
                    'answer_id' => (int) $answerId,
                ]);
            }
        }

        $points = $score * 10; // 10 points for each correct answer
        Auth::user()->increment('points', $points);

        // Load attempt details for frontend review if needed
        $attempt->load('quiz.questions.answers', 'answers');

        $resultPayload = [
            'attempt_id' => $attempt->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'total_questions' => $totalQuestions,
            'points_awarded' => $points,
            'attempt' => $attempt,
        ];

        if ($request->wantsJson() || $request->boolean('return_json')) {
            return response()->json($resultPayload);
        }

        return back()->with('quizResult', $resultPayload);
    }

    public function result(QuizAttempt $attempt)
    {
        $attempt->load('quiz.questions.answers', 'answers');

        return Inertia::render('Quiz/Result', [
            'attempt' => $attempt,
        ]);
    }
}
