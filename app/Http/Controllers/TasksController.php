<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TasksRequest;
use App\Http\Requests\TasksUpdateRequest;
use App\Http\Requests\UpdateStatusTaskRequest;
use App\Http\Resources\TasksResource;
use App\Models\Tasks;
use Carbon\Carbon;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  TasksResource::collection(
            Tasks::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TasksRequest $request)
    {
        $path = null;
        if ($request->file('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('uploads');
        }

        $task = Tasks::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'attachment' => $path,
            'status' => $request->status,
            'date_done' => $request->date_done
        ]);

        return new TasksResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json(["error" => '404 Not Found'], 404);
        }
        return new TasksResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TasksUpdateRequest $request, string $id)
    {
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json(["error" => '404 Not Found'], 404);
        }

        $task->update($request->all());
        return new TasksResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json(["error" => '404 Not Found'], 404);
        }
        $task->delete();
        return response()->json([], 204);
    }

    public function updateStatus(UpdateStatusTaskRequest $request, $id)
    {
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json(["error" => '404 Not Found'], 404);
        }

        $task->status = $request->input('status');

        if ($request->input('status') == 'done') {
            $currentDate = Carbon::now();
            $task->date_done =  $currentDate;
        }

        $task->save();
        return response()->json([], 204);
    }
}
