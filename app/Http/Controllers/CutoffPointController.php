<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CutoffPoint;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CutoffPointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = min($request->input('per_page', 200), 200);
        $cutoff_points = QueryBuilder::for(CutoffPoint::class)
            ->allowedFilters([
                AllowedFilter::exact('course_id'),
                'year', 'scheme'
            ])
            ->paginate($per_page);
        return response()->json($cutoff_points, 200);
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
            'male_points' => 'required|numeric',
            'female_points' => 'required|numeric',
            'average_points' => 'required|numeric',
            'year' => 'required|integer',
            'intake_name' => 'required|string',
            'scheme' => ['required', Rule::in(['private', 'government'])],
            'course_id' => 'required_without:course_code|exists:courses,id',
            'course_code' => 'required_without:course_id|exists:courses,alias_code',

        ]);

        $cutoff_point = new CutoffPoint();
        $cutoff_point->male_points = $request->male_points;
        $cutoff_point->female_points = $request->female_points;
        $cutoff_point->average_points = $request->average_points;
        $cutoff_point->year = $request->year;
        $cutoff_point->intake_name = $request->intake_name;
        $cutoff_point->scheme = $request->scheme;

        if ($request->course_id) {
            $course = Course::find($request->course_id);
        } else {
            $course = Course::where('alias_code', $request->course_code)->first();
        }
        $cutoff_point->course_id = $course->id;
        $cutoff_point->save();
        return response()->json($cutoff_point, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CutoffPoint $point)
    {
        //delete cutoff point 
        $point->delete();
        return response()->json("successfully deleted", 200);
    }
}
