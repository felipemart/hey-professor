<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    public function store(): RedirectResponse
    {
        request()->validate([
            'question' => 'required|min:10',
        ]);

        $question           = new Question();
        $question->question = request('question');
        $question->save();

        return to_route('dashboard');
    }
}
