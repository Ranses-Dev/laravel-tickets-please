<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Models\Ticket;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\User;
use App\Policies\V1\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;
    /**
     * Display a listing of the resource.
     */
    /**
     * Get all tickets
     *@group Managing Tickets
     *@queryPAram sort string Data field(s) to sort by. Separate multiple fields with commas. Denote descending sort with a minus sign. Example:sort=title,-createdAt
     *@queryParam filter[status] Filter by status code: A,C,H,X. No-example
     */
    public function index(TicketFilter $filters)
    {

        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

      /**
     * Create a ticket.
     */
    /**
     * Create a new tickets
    
     */
    public function store(StoreTicketRequest $request)
    {
            if (
                $this->isAble('store', Ticket::class)
            ) {
                return new TicketResource(Ticket::create($request->mappedAttributes()));
            }
            return $this->notAuthorized('You are not authorized to update that resource');

    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {

        if ($this->include('author')) {
            return TicketResource::collection($ticket->load('user')->paginate());
        }
        return new TicketResource($ticket);

    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {

        if ($this->isAble('update', $ticket)) {
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        }

        return $this->notAuthorized('You are not authorized to update that resource');

    }

    public function replace(ReplaceTicketRequest $request, Ticket $ticket)
    {
        if ($this->isAble('replace', $ticket)) {
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        }
        return $this->notAuthorized('You are not authorized to update that resource');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {

            if ($this->isAble('delete', $ticket)) {
                $ticket->delete();
                return $this->ok('Ticket successfully deleted');
            }
            return $this->notAuthorized('You are not authorized to delete that resource');


    }
}
