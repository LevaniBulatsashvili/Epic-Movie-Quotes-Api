<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function getMovies(Int $userId): JsonResponse
    {
        if (request()['search']) {
            if (request()['lang'] === 'en') {
                $movies = Movie::where('user_id', $userId)->whereRaw('LOWER(name->"$.en") like ?', '%'.strtolower(request()['search']).'%')->get();
            } else {
                $movies = Movie::where('user_id', $userId)->whereRaw('LOWER(name->"$.ka") like ?', '%'.strtolower(request()['search']).'%')->get();
            }
        } else {
            $movies = Movie::where('user_id', $userId)->get();
        }


        foreach ($movies as $key=>$movie) {
            $movies[$key]->quotes = count(Quote::where('movie_id', $movie->id)->get());
        }

        return response()->json([
            'message' => 'success',
            'movies' => $movies,
        ], 200);
    }

    public function getMovie(Movie $movie): JsonResponse
    {
        return response()->json([
            'message' => 'success',
            'movie' => $movie,
            'genres' => Genre::where('movie_id', $movie->id)->get(),
        ], 200);
    }
}
