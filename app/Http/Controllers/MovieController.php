<?php

namespace App\Http\Controllers;

use App\Movie;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movie_user = User::find(Auth::id());
        $counter = 0;
        $list = [];

        // Structure the response including link table attribute
        foreach ($movie_user->movies as $movie) {
            $counter++;
            $list[$counter]['id'] = $movie->id;
            $list[$counter]['name'] = $movie->name;
            $list[$counter]['detail'] = $movie->detail;
            $list[$counter]['year'] = $movie->year;

            if ($movie->pivot->watched == 0){
                $watched = 'No';
            } else {
                $watched = 'Yes';
            }

            $list[$counter]['watched'] = $watched;
        }

        return $list;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
            'year' => 'required',
            'watched' => 'required'
        ]);

        // Bind request data to Model
        $mov = new Movie();
        $mov->name = $request->name;
        $mov->detail = $request->detail;
        $mov->year = $request->year;

        // Save the Model and link table record
        $mov->save();
        $mov->users()->attach(Auth::id());

        // If the movie is watched, update the link table attribute
        if ($request->watched == 1) {
            $user = User::find(Auth::id());
            $user->movies()->updateExistingPivot($mov->id, ['watched' => $request->watched]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
            'year' => 'required',
            'watched' => 'required'
        ]);

        // Save the Model and link table record
        $movie->name = $request->name;
        $movie->detail = $request->detail;
        $movie->year = $request->year;
        $movie->save();

        // If the movie is watched, update the link table attribute
        if ($request->watched == 1) {
            $watched = 1;
        } else {
            $watched = 0;
        }

        $user = User::find(Auth::id());
        $user->movies()->updateExistingPivot($movie->id, ['watched' => $watched]);
    }

    /**
     * @param Movie $movie
     * @throws \Exception
     */
    public function destroy(Movie $movie)
    {
        $movie->users()->detach($movie->id);
        $movie->delete();
    }
}
