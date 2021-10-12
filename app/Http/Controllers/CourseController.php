<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses,code',
            'university_id' => 'required|exists:universities,id',
            //essential subjects are required
            'essential_subjects' => 'required|array',
            'essential_subjects.*' => 'required|exists:subjects,id',
            //relevant subjects are required
            'relevant_subjects' => 'required|array',
            'relevant_subjects.*' => 'required|exists:subjects,id',
            //other subjects are required
            'desirable_subjects' => 'required|array',
            'desirable_subjects.*' => 'required|exists:subjects,id',

        ]);
        //create new course and save it;
        $course = new Course();
        $course->name = $request->name;
        $course->code = $request->code;
        $course->university_id = $request->university_id;
        //save the essential, relevant and other subjects
        $course->essential_subjects = json_encode($request->essential_subjects);
        $course->relevant_subjects = json_encode($request->relevant_subjects);
        $course->desirable_subjects = json_encode($request->desirable_subjects);
        $course->save();
        // $course = Course::find($course->id);
        return response()->json($course, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }
}
