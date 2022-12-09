<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function getMovies(): JsonResponse
	{
        $movies = Movie::all()->isEmpty() ? [] : Movie::all();

        foreach($movies as $key=>$movie) {
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
