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
        // $file = $request->file('thumbnail');
        // $file->move(public_path().'/assets', $file->getFilename());
		$movie = Movie::create([
            'name' => ['en' => $request->name_en, 'ka' => $request->name_ka],
            'director' => ['en' => $request->director_en, 'ka' => $request->director_ka],
            'description' => ['en' => $request->description_en, 'ka' => $request->description_ka],
            // 'image' => $file->getFilename()
        ]);

        $movie->quotes = 0;

        foreach($request->genres as $genre) {
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
        // isset($request->thumbnail)
		if (false)
		{
			$request->thumbnail = $request->file('thumbnail')->store('thumbnail');
			$movie->update([
                'name' => ['en' => $request->name_en, 'ka' => $request->name_ka],
                'director' => ['en' => $request->director_en, 'ka' => $request->director_ka],
                'description' => ['en' => $request->description_en, 'ka' => $request->description_ka],
                // 'thumbnail' => $request->thumbnail
            ]);
		}
		else
		{
			$movie->update([
                'name' => ['en' => $request->name_en, 'ka' => $request->name_ka],
                'director' => ['en' => $request->director_en, 'ka' => $request->director_ka],
                'description' => ['en' => $request->description_en, 'ka' => $request->description_ka],
                // 'thumbnail' => $request->thumbnail
            ]);

            Genre::where('movie_id', $movie->id)->delete();

            foreach($request->genres as $genre) {
                Genre::create([
                    'movie_id' => $movie->id,
			        'genre'     => ['en' => $genre['en'], 'ka' => $genre['ka']],
                ]);
            }
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
