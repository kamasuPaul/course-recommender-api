<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];
    //cast the essential_subjects, relevant_subjects and other_subjects to array
    protected $casts = [
        'essential_subjects' => 'array',
        'relevant_subjects' => 'array',
        'desirable_subjects' => 'array'
    ];
    private $essential_relationship = "and";
    private $relevant_relationship = "one_best_done";
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //function to return essential_required_subjects
    public function getEssentialRequiredSubjects()
    {
        // $subjects = Subject::whereIn('id', $this->essential_subjects)->get();
        $subjects = Subject::whereIn('id',[85,56])->get();
        return $subjects;
    }
    //function to return essential_optional subjects
    public function getEssentialOptionalSubjects()
    {
        return  collect([]);
    }
    //function to return relevant subjects
    public function getRelevantSubjects()
    {
        // return Subject::whereIn('id', $this->relevant_subjects)->get();
        $subjects = Subject::whereIn('id',[87,61,86,72])->get();
        return $subjects;
    }
    public function get_no_of_required_essential_optional(){
        $relationship = $this->essential_relationship;
        switch($relationship){
            case "and": return 0;
            case "and_or": return 1;
            case "two_best_done": return 2;
            case "one_best_done": return 1;
            case "and/or":1;
        }
    }
}
