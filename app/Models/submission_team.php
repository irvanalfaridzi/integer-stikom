<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\MasterModel;

class submission_team extends Model
{
    protected $table= 'submission_team';
    protected $gruarderd= ['id'];

    protected $fillable = [
        'submission_id',
        'team_id',
        'document',
    ];

    public function team()
    {
        return $this->belongsTo(team::class);
    }

    public function submission()
    {
        return $this->belongsTo(submission::class);
    }
}
