<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\ResultSubject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Log;

class ResultController extends Controller
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
            'o_level_subjects' => 'required|array|min:8|max:16',
            'o_level_subjects.*.subject_id' => 'exists:subjects,id',
            'o_level_subjects.*.grade' => Rule::in(['D','C', 'F', 'U']),	
            'a_level_subjects'=>'required|array|min:5|max:6',
            'a_level_subjects.*.subject_id' => 'exists:subjects,id',
            'a_level_subjects.*.grade' => Rule::in(Result::GRADES),
        ]);
        $result = new Result();
        $result->user_id = auth()->user()->id;
        $result->save();
        //loop through the o level subjects and for each subject create a result
        foreach ($validatedData['o_level_subjects'] as $subject) {
            //log subject
            Log::debug($subject);
            $result_subject = new ResultSubject();
            $result_subject->result_id = $result->id;
            $result_subject->level = Result::O_LEVEL;
            $result_subject->subject_id = $subject['subject_id'];
            $result_subject->grade = $subject['grade'];
            $result_subject->score = Result::getScore($subject['grade'],Result::O_LEVEL);
            $result_subject->save();
        }
        //loop through the a level subjects and for each subject create a result
        foreach ($validatedData['a_level_subjects'] as $subject) {
            $result_subject = new ResultSubject();
            $result_subject->result_id = $result->id;
            $result_subject->level = Result::A_LEVEL;
            $result_subject->subject_id = $subject['subject_id'];
            $result_subject->grade = $subject['grade'];
            $result_subject->score = Result::getScore($subject['grade'],Result::A_LEVEL);
            $result_subject->save();
        }
        //return the combined Results
        $o_level_subjects = ResultSubject::where('result_id',$result->id)->level(Result::O_LEVEL)->get();
        $a_level_subjects = ResultSubject::where('result_id',$result->id)->level(Result::A_LEVEL)->get();
        $result->o_level_subjects = $o_level_subjects;
        $result->a_level_subjects = $a_level_subjects;
        return response()->json($result,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function show(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function destroy(Result $result)
    {
        //
    }
}
