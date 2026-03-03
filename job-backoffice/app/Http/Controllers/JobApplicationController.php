<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Http\Requests\JobApplicationUpdateRequest;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         //Active
        $query = JobApplication::latest();

        //Archived
        if($request->input('archived') == 'true'){
            $query->onlyTrashed();
        };

        $jobApplications = $query->paginate(10)->onEachSide(1);
        return view('job-application.index',compact('jobApplications'));
    }

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobApplication = JobApplication::findOrFail($id);
        return view('job-application.show',compact('jobApplication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jobApplication = JobApplication::findOrFail($id);
        return view('job-application.edit',compact('jobApplication'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobApplicationUpdateRequest $request, string $id)
    {
        $jobApplication = JobApplication::findOrFail($id);
        $jobApplication->update([
            'status'=>$request->input('status'),
        ]);

          if($request->query('redirectToList') == 'false'){
                return redirect()->route('job-applications.show',$id)->with('success','Applicant status updated successfully!');
        }

        return redirect()->route('job-applications.index')->with('success','Applicant status updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
