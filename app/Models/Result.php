<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $guarded = [];
    //declare O and A level constants
    const O_LEVEL = 'O';
    const A_LEVEL = 'A';
    //declare grade constants
    const GRADE_A = 'A';
    const GRADE_B = 'B';
    const GRADE_C = 'C';
    const GRADE_D = 'D';
    const GRADE_E = 'E';
    const GRADE_F = 'F';
    const GRADE_O = 'O';
    const GRADE_U = 'U';
    const GRADE_P = 'P';
    const GRADE_1 = '1';
    const GRADE_0 = '0';
    //group grades into array constant
    const GRADES = [
        self::GRADE_A,
        self::GRADE_B,
        self::GRADE_C,
        self::GRADE_D,
        self::GRADE_E,
        self::GRADE_F,
        self::GRADE_O,
        self::GRADE_U,
        self::GRADE_P,
        self::GRADE_1,
        self::GRADE_0
    ];
    const O_GRADES = [ 'D1', 'D2', 'C3', 'C4', 'C5', 'C6', 'P7', 'P8', 'F9', 'X' ];

    public function result_subjects()
    {
        return $this->hasMany(ResultSubject::class, 'result_id', 'id');
    }
    public function getOLevelWeight(){
        $oLevelWeight = ResultSubject::where('result_id', $this->id)->where('level', Result::O_LEVEL)->sum('score');
        return $oLevelWeight;
    }

    /**
     * Get score from grade and level
     */
    public static function getScore($grade, $level)
    {
        if ($level == self::A_LEVEL) {
            switch ($grade) {
                case self::GRADE_A:
                    return 6;
                case self::GRADE_B:
                    return 5;
                case self::GRADE_C:
                    return 4;
                case self::GRADE_D:
                    return 3;
                case self::GRADE_E:
                    return 2;
                case self::GRADE_O:
                    return 1;
                case self::GRADE_F:
                    return 0;
                case self::GRADE_U:
                    return 0;
                case self::GRADE_1:
                    return 1;
                case self::GRADE_0:
                    return 0;
                default:
                    return 0;
            }
        }
        if ($level == self::O_LEVEL) {
            if(in_array($grade,['D1', 'D2'] )){
                return 0.3;
            }
            if(in_array($grade,['C3', 'C4', 'C5', 'C6'] )){
                return 0.2;
            }
            if(in_array($grade,['P7', 'P8'] )){
                return 0.1;
            }
            return 0.0;
            }
    }
}
