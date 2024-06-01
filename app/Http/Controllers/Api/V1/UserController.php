<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Models\User;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Policies\V1\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class UserController extends ApiController
{
    protected $policy = UserPolicy::class;
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {

        return UserResource::collection(
            User::filter($filters)->paginate()
        );
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {

        try {
            $this->isAble('store', User::class);
            return new UserResource(User::create($request->mappedAttributes()));
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create that resource', 401);
        }
    }

    public function replace(ReplaceUserRequest $request, $user_id)
    {

        try {
            $user = User::findOrFail($user_id);
            $this->isAble('replace', $user);
            $user->update($request->mappedAttributes());
            return new UserResource($user);
        } catch (ModelNotFoundException $exception) {
            return $this->error('User cannot be found.', 404);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

        if ($this->include('tickets')) {
            return new UserResource($user->load('tickets'));
        }

        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $this->isAble('update', $user);
            $user->update($request->mappedAttributes());
            return new  UserResource($user);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update that resource', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $this->isAble('delete', $user);
            $user->delete();
            return $this->ok('User successfully deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('User cannot be found.', 404);
        }
    }
}
