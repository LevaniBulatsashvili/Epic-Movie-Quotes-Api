<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCommentRequest;
use App\Http\Requests\Admin\StoreOrDestroyLikeRequest;
use App\Http\Requests\Admin\StoreQuoteRequest;
use App\Http\Requests\Admin\UpdateQuoteRequest;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\QuoteComment;
use App\Models\QuoteLike;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	public function store(StoreQuoteRequest $request, Movie $movie): JsonResponse
	{
		$quote = Quote::create([
			'movie_id' => $movie->id,
			'body'     => ['en' => $request->quote_en, 'ka' => $request->quote_ka],
		]);

        $quote->likes = 0;
        $quote->comments = [];

		return response()->json([
            'message' => 'quote successfully created',
            'quote' => $quote
        ], 200);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
	{
		$quote->update(['body' => ['en' => $request->quote_en, 'ka' => $request->quote_ka]]);

		return response()->json([
            'message' => 'quote successfully created',
            'quote' => $quote
        ], 200);
	}

	public function destroy(Quote $quote): JsonResponse
    {
		$quote->delete();

		return response()->json(['message' => 'quote was successfully deleted'], 200);
	}

    public function createOrDestroyLike(StoreOrDestroyLikeRequest $request, Quote $quote): JsonResponse
    {
        $quoteLike = QuoteLike::where([
            'user_id' => $request->userId,
            'quote_id' => $quote->id
        ]);

        if (count($quoteLike->get()) > 0) {
            $quoteLike->delete();
            return response()->json(['message' => 'quote was successfully disliked'], 200);
        }

        QuoteLike::create([
            'user_id' => $request->userId,
            'quote_id' => $quote->id,
        ]);

        return response()->json(['message' => 'quote was successfully liked'], 200);
    }

    public function comment(StoreCommentRequest $request, Quote $quote): JsonResponse
    {
        $quoteComment = QuoteComment::create([
            'quote_id' => $quote->id,
            'username' => $request->username,
            'body' => $request->body,
            // 'image' => $request->image,
        ]);

        return response()->json([
            'message' => 'comment was successfull',
            'quoteComment' => $quoteComment,
        ], 200);
    }
}
