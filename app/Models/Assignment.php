<?php

namespace App\Models;

use App\Models\Traits\Assignment\Relationships;
use App\Models\Traits\Assignment\Repository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory, Relationships, Repository;

    protected $guarded = ['id'];

    public function createFakeAssignment()
    {

    }

    public static function rules(): array
    {
        return [
            'name' => 'required|min:3|max:150',
            'description' => 'required|min:1|max:500',
            'stat_id' => 'required',
            'category_id' => 'required'
        ];
    }

    public static function feedback(): array
    {
        return [
            'required' => 'The :attribute is mandatory',
            'name.min' => 'Minimum 3 characters',
            'name.max' => 'Maximum 150 characters',
            'description.min' => 'Minimum 1 characters',
            'description.max' => 'Maximum 500 characters',
            'stat_id.required' => 'The status is mandatory',
            'category_id.required' => 'The category is mandatory'
        ];
    }

    public static function calculatePercentageAssignments(int $user_id)
    {
        $finished = 0;
        $inProgress = 0;
        $created = 0;
        $total = 0;

        $assignments = Assignment::where('user_id', auth()->user()->id)->with('category', 'stat')->get();
        foreach ($assignments as $assignment){
            $total++;
            switch ($assignment->stat->name){
                case 'Finished':
                    $finished++;
                    break;
                case 'In progress':
                    $inProgress++;
                    break;
                case 'Created':
                    $created++;
                    break;
            }
        }

        return [$finished, $inProgress, $created, $total];
    }
}
