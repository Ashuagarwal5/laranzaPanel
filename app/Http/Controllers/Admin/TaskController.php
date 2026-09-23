<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\TaskRequest;
use App\Task;
use Illuminate\Http\Request;
use Sentinel;

class TaskController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(TaskRequest $request)
    {
        $user = Sentinel::getuser();
        
        
        $request->merge(['user_id' => $user->id]);
        $task = new Task();
        $task->user_id=$user->id;
        $task->task_description	=$request->get('task_description');
        $task->task_deadline=$request->get('task_deadline');
        $task->save();

        return $task->id;

    }

    /**
     * @param Task $task
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Task $task, Request $request)
    {
		//$input=$request->all();
		//Task::where('id',$task)->update(['task_description'=>$input['task_description']]);
		
        $request->merge(['user_id' => Sentinel::getUser()->id]);
        $task->update($request->except('_method', '_token'));
    }

    /**
     * Delete the given Driver.
     *
     * @param  Task $task
     */
    public function delete(Task $task)
    {
        $task->delete();
    }

    /**
     * Ajax Data
     * @return array;
     */
    public function data()
    {
        return Task::orderBy('finished', 'ASC')
            ->orderBy('task_deadline', 'DESC')
            ->get()
            ->toArray();

    }
}
