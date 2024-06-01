<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Filters\V1\AuthorFilter;
use App\Models\User;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Log;

class AuthorController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {

        return UserResource::collection(
            User::select('users.*')
            ->join('tickets','users.id','=','tickets.user_id')
            ->filter($filters)
            ->distinct()
            ->paginate()
        );
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $author)
    {
        /*if ($this->include('tickets'))
            return UserResource::collection($user->load('tickets'));*/
        if ($this->include('tickets')) {
            $author->load('tickets');
        }

        return new UserResource($author);
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
