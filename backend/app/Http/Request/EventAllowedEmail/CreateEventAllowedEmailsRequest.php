<?php

namespace HiEvents\Http\Request\EventAllowedEmail;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventAllowedEmailsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'emails' => ['required', 'array', 'min:1'],
            'emails.*' => ['required', 'email', 'max:255'],
        ];
    }
}
