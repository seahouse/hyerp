<?php

namespace App\Models\Basic;

use App\Models\Sales\Project_hxold;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sales\Salesorder_hxold;

class Biddinginformation extends Model
{
    protected $fillable = [
        'number',
        'year',
        'digital_number',
        'closed',
        'remark',
        'sohead_id',
        'biddingprojectid',
    ];

    public function biddinginformationitems()
    {
        return $this->hasMany(Biddinginformationitem::class);
    }

    public function biddinginformationeditems()
    {
        return $this->hasMany(Biddinginformationeditem::class);
    }

    public function sohead()
    {
        return $this->hasOne(Salesorder_hxold::class, 'id', 'sohead_id');
    }

    public function biddingproject()
    {
        if (null !== $this->sohead) {
            $project_id = $this->sohead->project_id;
            return Project_hxold::find($project_id);
        } else
            return null;
    }
    public function biddinginformationfieldtypes()
    {
        return $this->hasMany(Biddinginformationfieldtype::class);
    }
}
