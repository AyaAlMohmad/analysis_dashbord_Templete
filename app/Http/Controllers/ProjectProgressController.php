<?php

namespace App\Http\Controllers;

use App\Models\ProjectProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectProgressController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $progresses = ProjectProgress::with('user')->get();
        return view('project_progress.index', compact('progresses'));
    }

    /**
     * create
     */
    public function create()
    {

        return view('project_progress.create');
    }

    /**
     * store
     */
    public function store(Request $request)
    {
        $request->validate([
            'site' => 'required|in:dhahran,bashaer',
            'progress_percentage' => 'required|numeric|min:0|max:100',
        ]);

        ProjectProgress::create([
            'user_id' => auth()->id(),
            'site' => $request->site,
            'progress_percentage' => $request->progress_percentage,
        ]);

        return redirect()->route('admin.project-progress.index')->with('success', __('project_progress.saved'));
    }


    /**
     * edit
     */
    public function edit($id)
    {
        $progress = ProjectProgress::findOrFail($id);

        return view('project_progress.edit', compact('progress'));
    }

    /**
     * update
     */
    public function update(Request $request, $id)
    {
        $progress = ProjectProgress::findOrFail($id);

        $request->validate([
            'progress_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $progress->update([
            'user_id'=>auth()->id(),
            'progress_percentage' => $request->progress_percentage,
        ]);

        return redirect()->route('admin.project-progress.index')->with('success', __('project_progress.updated'));
    }

    /**
      * destroy
     */
    public function destroy($id)
    {
        $progress = ProjectProgress::findOrFail($id);
        $progress->delete();

        return redirect()->route('admin.project-progress.index')->with('success', __('project_progress.deleted'));
    }
}
