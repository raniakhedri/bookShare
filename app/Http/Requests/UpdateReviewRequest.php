<?php
// app/Http/Requests/UpdateReviewRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled in the controller via policies
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'overall_rating' => 'sometimes|required|numeric|between:1,5',
            'content_rating' => 'nullable|numeric|between:1,5',
            'condition_rating' => 'nullable|numeric|between:1,5',
            'recommendation_level' => 'nullable|numeric|between:1,5',
            'difficulty_level' => 'nullable|numeric|between:1,5',
            'review_title' => 'nullable|string|max:200',
            'review_text' => 'sometimes|required|string|min:10|max:5000',
            'reading_context' => 'nullable|string|max:500',
            'is_spoiler' => 'boolean',
            'content_warnings' => 'nullable|string|max:200',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'overall_rating.required' => 'Please provide an overall rating for this book.',
            'overall_rating.between' => 'Rating must be between 1 and 5 stars.',
            'content_rating.between' => 'Content rating must be between 1 and 5 stars.',
            'condition_rating.between' => 'Condition rating must be between 1 and 5 stars.',
            'recommendation_level.between' => 'Recommendation level must be between 1 and 5 stars.',
            'difficulty_level.between' => 'Difficulty level must be between 1 and 5 stars.',
            'review_title.max' => 'Review title cannot exceed 200 characters.',
            'review_text.required' => 'Please provide a review description.',
            'review_text.min' => 'Review must be at least 10 characters long.',
            'review_text.max' => 'Review cannot exceed 5000 characters.',
            'reading_context.max' => 'Reading context cannot exceed 500 characters.',
            'content_warnings.max' => 'Content warnings cannot exceed 200 characters.',
            'photos.max' => 'You can upload a maximum of 5 photos.',
            'photos.*.image' => 'Each file must be a valid image.',
            'photos.*.mimes' => 'Images must be in JPEG, PNG, JPG, or GIF format.',
            'photos.*.max' => 'Each image must be smaller than 2MB.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure is_spoiler is boolean
        if ($this->has('is_spoiler')) {
            $this->merge([
                'is_spoiler' => filter_var($this->is_spoiler, FILTER_VALIDATE_BOOLEAN)
            ]);
        }

        // Clean up rating values
        foreach (['overall_rating', 'content_rating', 'condition_rating', 'recommendation_level', 'difficulty_level'] as $rating) {
            if ($this->has($rating) && $this->$rating !== null) {
                $this->merge([
                    $rating => round((float) $this->$rating, 1)
                ]);
            }
        }
    }
}