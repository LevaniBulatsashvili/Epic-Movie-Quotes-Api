<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteComment;
use App\Models\QuoteLike;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function getQuotes(Int $movieId): JsonResponse
	{
        $quotes = Quote::where('movie_id', $movieId)->get();

        foreach($quotes as $key=>$quote) {
            $quotes[$key]->likes = count(QuoteLike::where('quote_id', $quote->id)->get());
            $quotes[$key]->comments = QuoteComment::where('quote_id', $quote->id)->get();
        }

		return response()->json([
            'message' => 'success',
            'quotes' => $quotes
        ], 200);
	}

    public function getQuote(Quote $quote): JsonResponse
	{
        $quote->likes = count(QuoteLike::where('quote_id', $quote->id)->get());
        $quote->comments = QuoteComment::where('quote_id', $quote->id)->get();

		return response()->json([
            'message' => 'success',
            'quote' => $quote,
        ], 200);
	}

    public function getRecentQuotes(): JsonResponse
	{
       $quotes = Quote::latest('id')->paginate(3);

       foreach($quotes as $key=>$quote) {
            $quotes[$key]->likes = count(QuoteLike::where('quote_id', $quote->id)->get());
            $quotes[$key]->comments = QuoteComment::where('quote_id', $quote->id)->get();
        }

		return response()->json([
            'message' => 'success',
            'quotes' => $quotes,
        ], 200);
	}
}
