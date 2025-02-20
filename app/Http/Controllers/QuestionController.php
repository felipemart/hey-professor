<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Question;
use Closure;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    public function store(): RedirectResponse
    {
        request()->validate([
            'question' => [
                'required',
                'min:10',
                function (string $attribute, string $value, Closure $fail) {
                    if (! str_ends_with($value, '?')) {
                        $fail('Tem certeza que quer enviar uma pergunta? esta pergunta não tem sem uma interrogação!');
                    }
                },
            ],
        ]);

        $question           = new Question();
        $question->question = request('question');
        $question->save();

        return to_route('dashboard');
    }
}
