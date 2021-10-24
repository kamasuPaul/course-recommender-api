<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Log;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->resourceItem = CourseResource::class;
        $this->resourceCollection = CourseCollection::class;
        // $this->authorizeResource(Course::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::with('campus')->get();
        return response()->json($courses, 200);
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
            'code' => 'required|string|max:20',
            'alias_code' => 'required|string|max:20',
            'type' => Rule::in(['DAY', 'AFTERNOON', 'EVENING', 'EXTERNAL', 'EXECUTIVE']),
            'university_id' => 'required|exists:universities,id',
            'campus_id' => 'exists:campuses,id',
            //essential subjects are required
            // 'essential_subjects' => 'required|array',
            // 'essential_subjects.*' => 'required|exists:subjects,id',
            //relevant subjects are required
            // 'relevant_subjects' => 'required|array',
            // 'relevant_subjects.*' => 'required|exists:subjects,id',
            //other subjects are required
            // 'desirable_subjects' => 'required|array',
            // 'desirable_subjects.*' => 'required|exists:subjects,id',

        ]);
        //create new course and save it;
        $course = new Course();
        $course->name = $request->name;
        $course->code = $request->code;
        $course->alias_code = $request->alias_code;
        $course->campus_id = $request->campus_id;
        $course->university_id = $request->university_id;
        $course->type = $request->type;
        //save the essential, relevant and other subjects
        // $course->essential_subjects = json_encode($request->essential_subjects);
        // $course->relevant_subjects = json_encode($request->relevant_subjects);
        // $course->desirable_subjects = json_encode($request->desirable_subjects);
        $course->save();
        $course = Course::find($course->id);
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
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'code' => 'string|max:20',
            'type' => Rule::in(['DAY', 'AFTERNOON', 'EVENING', 'EXTERNAL', 'EXECUTIVE']),
            'university_id' => 'exists:universities,id',
            'campus_id' => 'exists:campuses,id',
            'essential_relationship' => Rule::in(['and','one_best_done','one','or_and','and/or', 'and_or', 'two_best_done']),
            'relevant_relationship' => Rule::in(['and','one_best_done','one','or_and','and/or', 'and_or', 'two_best_done']),
            //essential_required
            'essential_required_subjects' => 'array',
            'essential_required_subjects.*' => '',
            //essential_optional
            'essential_optional_subjects' => 'array',
            'essential_optional_subjects.*' => '',
            //relevant subjects are required
            'relevant_subjects' => 'array',
            'relevant_subjects.*' => '',
            //other subjects are required
            'desirable_subjects' => 'array',
            'desirable_subjects.*' => 'required|exists:subjects,id',

        ]);
        if($request->has('essential_relationship')) $course->essential_relationship = $request->essential_relationship;
        if($request->has('relevant_relationship')) $course->relevant_relationship = $request->relevant_relationship;

        //update the essential_required subject
        if($request->has('essential_required_subjects')){
            $course->essential_required_subjects = json_encode($request->essential_required_subjects);
        }
        //update the essential_optional_subjects
        if($request->has('essential_optional_subjects')){
            $course->essential_optional_subjects = json_encode($request->essential_optional_subjects);
        }
        if($request->has('relevant_subjects')){
            $course->relevant_subjects = json_encode($request->relevant_subjects);
        }
        if($request->has('desirable_subjects')){
            $course->desirable_subjects = json_encode($request->desirable_subjects);
        }
        $course->save();
        $course = Course::find($course->id);
        return response()->json($course, 200);
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

    /**
     * The course eligiblity algorithm.
     */
    public function getEligibleCourses(Request $request)
    {
        $validated = $request->validate(
            [
                'result' => 'required|exists:results,id',
                // 'user' => 'required|exists:users,id'
                'gender' => 'required|in:male,female'
            ]
        );
        $result = Result::with('result_subjects')->findOrFail($request->result);
        //get student subject list
        $subjects = $result->result_subjects->where('level', Result::A_LEVEL)->where('subject.subsidiary', false);
        //declare list of ineligble course
        $ineligble_courses = [];
        //declare list of eligble courses
        $eligble_courses = [];
        //Get all courses
        $courses = Course::all();
        //foreach course
        foreach ($courses as $course) {
            //get its essential_required subjects
            Log::debug($course->name);
            $essential_required = $course->getEssentialRequiredSubjects();
            Log::debug($essential_required);
            //foreach essential_required subject
            $not_elible = false;
            foreach ($essential_required as $subject) {
                //find subject in the student subject list
                $subject_found = $subjects->firstWhere('subject_id', $subject->id);
                //if not found, add course to ineligble courses list.
                if (!$subject_found) {
                    array_push($ineligble_courses, $course);
                    $not_elible = true;
                    break;
                }
            }

            //continue to next course in list
            if ($not_elible) continue;
            //now look at the essential_optional
            //get its essential_optional subjects
            $essential_optional = $course->getEssentialOptionalSubjects();
            //get no of required essential_optional subjects, using essential relationship
            $no_of_required_essential_optional = $course->get_no_of_required_essential_optional();
            //declare array of found subjects
            $found_subjects = [];
            //foreach subject in the student subject list
            foreach ($subjects as $subject) {
                //if found is equal or greater than required, break the loop
                if (count($found_subjects) >= $no_of_required_essential_optional) {
                    break;
                }
                //try and find it in the essential_optional subject list
                $found = $essential_optional->where('subject_id', $subject->id)->first();
                //add it to found subjects
                if($found) {
                    array_push($found_subjects, $found);
                }
            }
            //if found has equal or greater than required subjects, add course to eligble courses list
            if (count($found_subjects) >= $no_of_required_essential_optional) {
                array_push($eligble_courses, $course);
            }
        }
        //return eligble courses
        return response()->json(($eligble_courses), 200);
    }
}
