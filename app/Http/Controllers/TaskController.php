<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        return view('task.index');
    }

    public function fetchtask()
    {
        $tasks = Task::all();

        foreach ($tasks as $task) {

            $task->status = ($task->status == 'pending')?'':'Done';

            if ($task->status != 'Done') {
                $checbox = '<button type="checkbox" value='.$task->id.' class="form-check-input btn-sm"></button>';
            }
            else{
                $checbox = '';
            }
            $task->checbox = $checbox;
        }

        return response()->json([
            'listtasks'=>$tasks,
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'task'=> 'required|max:191|unique:tasks',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }
        else
        {
            $task = new Task;
            $task->task = $request->input('task');
            $task->save();
            return response()->json([
                'status'=>200,
                'message'=>'Task Added Successfully.'
            ]);
        }

    }


    public function destroy($id)
    {
        $student = Task::find($id);
        if($student)
        {
            $student->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Task Deleted Successfully.'
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No TaskTask Found.'
            ]);
        }
    }

    public function checkedupdate($id)
    {
        $task = Task::find($id);
        if($task)
        {
            $task->status = 'completed';
            $task->update();
            return response()->json([
                'status'=>200,
                'message'=>'Checked Successfully.'
            ]);
        }

    }
}
