<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMovieRequest;
use App\Http\Requests\Admin\UpdateMovieRequest;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function store(StoreMovieRequest $request): JsonResponse
    {
        $movie = Movie::create([
            'user_id' => $request->user_id,
            'name' => ['en' => $request->name_en, 'ka' => $request->name_ka],
            'director' => ['en' => $request->director_en, 'ka' => $request->director_ka],
            'description' => ['en' => $request->description_en, 'ka' => $request->description_ka],
            'thumbnail' => $request->file('thumbnail')->store('thumbnails')
        ]);

        $movie->quotes = 0;

        $genres = json_decode(json_encode(json_decode($request->genres)), true);

        foreach ($genres as $genre) {
            Genre::create([
                'movie_id' => $movie->id,
                'genre'     => ['en' => $genre['en'], 'ka' => $genre['ka']],
            ]);
        }

        return response()->json([
            'message' => 'success',
            'movie' => $movie,
            'genres' => Genre::where('movie_id', $movie->id)->get()
        ], 201);
    }

    public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
    {
        if (isset($request->thumbnail)) {
            $movie->update([
                'name' => ['en' => $request->name_en, 'ka' => $request->name_ka],
                'director' => ['en' => $request->director_en, 'ka' => $request->director_ka],
                'description' => ['en' => $request->description_en, 'ka' => $request->description_ka],
                'thumbnail' => $request->file('thumbnail')->store('thumbnails'),
            ]);
        } else {
            $movie->update([
                'name' => ['en' => $request->name_en, 'ka' => $request->name_ka],
                'director' => ['en' => $request->director_en, 'ka' => $request->director_ka],
                'description' => ['en' => $request->description_en, 'ka' => $request->description_ka],
            ]);
        }

        Genre::where('movie_id', $movie->id)->delete();

        $genres = json_decode(json_encode(json_decode($request->genres)), true);

        foreach ($genres as $genre) {
            Genre::create([
                'movie_id' => $movie->id,
                'genre'     => ['en' => $genre['en'], 'ka' => $genre['ka']],
            ]);
        }

        return response()->json([
            'message' => 'success',
            'movie' => $movie,
            'genres' => Genre::where('movie_id', $movie->id)->get()
        ], 200);
    }

    public function destroy(Movie $movie): JsonResponse
    {
        $movie->delete();

        return response()->json(['message' => 'movie was successfully deleted'], 200);
    }
}
