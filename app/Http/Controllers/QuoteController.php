<?php

namespace App\Http\Controllers;

use App\Events\UserQuoteUpdated;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\QuoteComment;
use App\Models\QuoteLike;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function getQuotes(Int $movieId): JsonResponse
    {
        $quotes = [];
        $en = request()['lang'] === 'en';

        if (request()['search'] && request()['from']) {
            $quotes = $this->searchQuoteByMovieName($en);
        } elseif (request()['search']) {
            $quotes = $this->searchQuoteByName($en);
        } else {
            $quotes = Quote::where('movie_id', $movieId)->get();
        }

        foreach ($quotes as $key=>$quote) {
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

        foreach ($quotes as $key=>$quote) {
            $quotes[$key]->likes = count(QuoteLike::where('quote_id', $quote->id)->get());
            $quotes[$key]->comments = QuoteComment::where('quote_id', $quote->id)->get();
        }

        return response()->json([
            'message' => 'success',
            'quotes' => $quotes,
        ], 200);
    }

    protected function searchQuoteByMovieName(Bool $en): array
    {
        $quotes = [];

        if ($en) {
            $movies = Movie::whereRaw('LOWER(name->"$.en") like ?', '%'.strtolower(request()['search']).'%')->get();
            foreach ($movies as $key=>$movie) {
                array_push($quotes, Quote::where('movie_id', $movies[$key]->id)->get()[0]);
            }
        } else {
            $movies = Movie::whereRaw('LOWER(name->"$.ka") like ?', '%'.strtolower(request()['search']).'%')->get();
            foreach ($movies as $key=>$movie) {
                array_push($quotes, Quote::where('movie_id', $movies[$key]->id)->get()[0]);
            }
        }
        return $quotes;
    }

    public function quoteUpdated(Quote $quote)
    {
        UserQuoteUpdated::dispatch($quote);

        return response()->json(['message' => 'hello'], 200);
    }

    protected function searchQuoteByName(Bool $en): Collection
    {
        if ($en) {
            return Quote::whereRaw('LOWER(body->"$.en") like ?', '%'.strtolower(request()['search']).'%')->get();
        }
        return  Quote::whereRaw('LOWER(body->"$.ka") like ?', '%'.strtolower(request()['search']).'%')->get();
    }
}
