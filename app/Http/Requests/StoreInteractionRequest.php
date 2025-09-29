<?php
// app/Http/Requests/StoreInteractionRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInteractionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'interaction_type' => [
                'required',
                'string',
                Rule::in(['reply', 'helpful_vote', 'unhelpful_vote', 'report', 'share', 'bookmark'])
            ],
            'content' => 'nullable|string|max:2000|required_if:interaction_type,reply,report',
            'parent_interaction_id' => [
                'nullable',
                'integer',
                'exists:review_interactions,interaction_id',
                'required_if:interaction_type,reply'
            ],
            'context_data' => 'nullable|array'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'interaction_type.required' => 'Interaction type is required.',
            'interaction_type.in' => 'Invalid interaction type.',
            'content.required_if' => 'Content is required for replies and reports.',
            'content.max' => 'Content cannot exceed 2000 characters.',
            'parent_interaction_id.exists' => 'The parent interaction does not exist.',
            'parent_interaction_id.required_if' => 'Parent interaction is required for replies.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'interaction_type' => 'interaction type',
            'parent_interaction_id' => 'parent interaction',
            'context_data' => 'context data'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Custom validation: Ensure reply content is meaningful
            if ($this->interaction_type === 'reply' && $this->content) {
                $cleanContent = trim(strip_tags($this->content));
                if (strlen($cleanContent) < 3) {
                    $validator->errors()->add('content', 
                        'Reply must contain at least 3 characters of meaningful content.');
                }
            }

            // Custom validation: Ensure report has a valid reason
            if ($this->interaction_type === 'report' && $this->content) {
                $cleanContent = trim(strip_tags($this->content));
                if (strlen($cleanContent) < 10) {
                    $validator->errors()->add('content', 
                        'Report reason must be at least 10 characters long.');
                }
            }

            // Custom validation: Votes shouldn't have content
            if (in_array($this->interaction_type, ['helpful_vote', 'unhelpful_vote']) && $this->content) {
                $validator->errors()->add('content', 
                    'Votes cannot have content.');
            }

            // Custom validation: Only replies can have parent interactions
            if ($this->parent_interaction_id && $this->interaction_type !== 'reply') {
                $validator->errors()->add('parent_interaction_id', 
                    'Only replies can have parent interactions.');
            }
        });
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean up content
        if ($this->has('content') && $this->content) {
            $this->merge([
                'content' => trim($this->content)
            ]);
        }

        // Ensure parent_interaction_id is null for non-reply interactions
        if ($this->interaction_type !== 'reply') {
            $this->merge([
                'parent_interaction_id' => null
            ]);
        }
    }

    /**
     * Get data to be validated from the request.
     */
    public function validationData(): array
    {
        $data = parent::validationData();

        // Add some context data automatically
        if (!isset($data['context_data'])) {
            $data['context_data'] = [
                'user_agent' => $this->header('User-Agent'),
                'ip_address' => $this->ip(),
                'timestamp' => now()->toISOString()
            ];
        }

        return $data;
    }
}