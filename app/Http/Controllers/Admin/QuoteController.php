<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserQuoteUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCommentRequest;
use App\Http\Requests\Admin\StoreOrDestroyLikeRequest;
use App\Http\Requests\Admin\StoreQuoteRequest;
use App\Http\Requests\Admin\UpdateQuoteRequest;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\QuoteComment;
use App\Models\QuoteLike;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
    public function store(StoreQuoteRequest $request, Movie $movie): JsonResponse
    {
        $quote = Quote::create([
            'movie_id' => $movie->id,
            'username' => $request->username,
            'body'     => ['en' => $request->quote_en, 'ka' => $request->quote_ka],
            'thumbnail' => $request->file('thumbnail')->store('thumbnails'),
            'user_thumbnail' => $request->user_thumbnail
        ]);

        $quote->likes = 0;
        $quote->comments = [];

        return response()->json([
            'message' => 'quote successfully created',
            'quote' => $quote
        ], 201);
    }

    public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
    {
        if (isset($request->thumbnail)) {
            $quote->update([
                'body' => ['en' => $request->quote_en, 'ka' => $request->quote_ka],
                'thumbnail' => $request->file('thumbnail')->store('thumbnails'),
                'user_thumbnail' => $request->user_thumbnail
            ]);
        } else {
            $quote->update([
                'body' => ['en' => $request->quote_en, 'ka' => $request->quote_ka],
                'user_thumbnail' => $request->user_thumbnail
            ]);
        }

        $quote->comments = QuoteComment::where('quote_id', $quote->id)->get();
        $quote->likes = count(QuoteLike::where('quote_id', $quote->id)->get());

        return response()->json([
            'message' => 'quote successfully updated',
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
            $this->quoteUpdated(Quote::findOrFail($quote->id), 'dislike', $request->userId);
            return response()->json(['message' => 'quote was successfully disliked'], 200);
        }

        QuoteLike::create([
            'user_id' => $request->userId,
            'quote_id' => $quote->id,
        ]);

       $this->quoteUpdated(Quote::findOrFail($quote->id), 'like', $request->userId);
        return response()->json(['message' => 'quote was successfully liked'], 200);
    }

    public function comment(StoreCommentRequest $request, Quote $quote): JsonResponse
    {
        $quoteComment = QuoteComment::create([
            'quote_id' => $quote->id,
            'username' => $request->username,
            'body' => $request->body,
            'thumbnail' => $request->thumbnail,
        ]);

        $this->quoteUpdated(Quote::findOrFail($quote->id), 'comment', $quoteComment->id);

        return response()->json([
            'message' => 'comment was successfull',
            'quoteComment' => $quoteComment,
        ], 201);
    }

    public function quoteUpdated(Quote $quote, String $action, Int $id)
    {
        UserQuoteUpdated::dispatch($quote, $action, $id);
    }
}
