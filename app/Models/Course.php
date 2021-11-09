<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Log;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];
    //cast the essential_subjects, relevant_subjects and other_subjects to array
    protected $casts = [
        'essential_optional_subjects' => 'array',
        'essential_required_subjects' => 'array',
        'relevant_subjects' => 'array',
        'desirable_subjects' => 'array'
    ];
    protected $with = ['campus'];
    public $appends = ['essential_required','essential_optional'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
    public function getEssentialRequiredAttribute(){
        return $this->getEssentialRequiredSubjects();
    }
    public function getEssentialOptionalAttribute(){
        return $this->getEssentialOptionalSubjects();
    }
    //function to return essential_required_subjects
    public function getEssentialRequiredSubjects()
    {
        $list = $this->essential_required_subjects;
        $list = $list == null ? [] : $list;

        // $subjects = Subject::whereIn('id', $this->essential_subjects)->get();
        $subjects = Subject::whereIn('id',$list)->get();
        return $subjects;
    }
    //function to return essential_optional subjects
    public function getEssentialOptionalSubjects()
    {
        $list = $this->essential_optional_subjects;
        $list = $list == null ? [] : $list;
        // Log::debug($this->name);
        // // Log::debug($this->essential_optional_subjects);
        // // Log::debug(gettype($this->essential_optional_subjects));
        // Log::debug(gettype($list));
        // Log::debug($list);
        $subjects = Subject::whereIn('id', $list)->get();

        return  $subjects;
    }
    //function to return relevant subjects
    public function getRelevantSubjects()
    {
        // return Subject::whereIn('id', $this->relevant_subjects)->get();
        $subjects = Subject::whereIn('id', [87, 61, 86, 72])->get();
        return $subjects;
    }
    public function getEssentialSubjects(){
        //1.Get the relationship of the essential subjects
        $relationship = $this->essential_relationship;
        Log::debug($relationship);
        //2.Get the essential subjects
        $essential_subjects = [];
        if($relationship == "and"){
            Log::debug("and");
            $essential_subjects = $this->getEssentialRequiredSubjects();
        }
        if($relationship == "one"){
            Log::debug("one");
            $essential_subjects = $this->getEssentialRequiredSubjects();
        }
        if($relationship == "two_best_done"){
            Log::debug("two_best_done");
            $essential_subjects = $this->getEssentialOptionalSubjects();
        }
        if($relationship == "one_best_done"){
            Log::debug("one_best_done");
            $essential_subjects = $this->getEssentialOptionalSubjects();
        }
        if($relationship == "and/or"){
            Log::debug("and/or");
            $list1 = $this->getEssentialRequiredSubjects();
            $list2 = $this->getEssentialOptionalSubjects();
            //add list1 to list2
            $essential_subjects = $list1->merge($list2);
        }
        if($relationship == "and_or"){
            Log::debug("and_or");
            $list1 = $this->getEssentialRequiredSubjects();
            $list2 = $this->getEssentialOptionalSubjects();
            //add list1 to list2
            $essential_subjects = $list1->merge($list2);
        }
        return collect($essential_subjects);
    }
    public function get_no_of_required_essential_optional()
    {
        $relationship = $this->essential_relationship;
        switch ($relationship) {
            case "and":
                return 0;
                break;
            case "and_or":
                return 1;
                break;
            case "or_and":
                return 1;
                break;
            case "two_best_done":
                return 2;
                break;
            case "one_best_done":
                return 1;
                break;
            case "and/or":
                1;
                break;
            case "one":
                1;
                break;
                
        }
    }
}
