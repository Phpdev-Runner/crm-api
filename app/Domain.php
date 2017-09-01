<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use SoftDeletes;

    #region PROPERTIES
    protected $table = 'domains';
    protected $dates = ['deleted_at'];
    protected $fillable = [
            'lead_id',
            'value'
        ];
    #endregion

    #region MAIN METHODS

    public static function checkDomainDuplicates(array $domains)
    {
        $duplicateDomains = self::whereIn('value',$domains)->get()->toArray();

        $returnData = [];

        if(count($duplicateDomains)>1){
            foreach ($duplicateDomains AS $key=>$domain){
                $returnData[] = $domain['value'];
            }
            $returnData = implode(', ',$returnData);
            return $returnData;
        }else{
            return false;
        }
    }

    public static function deletePreviouslyAddedDomains(int $leadID)
    {
        $deletedQTY = self::where('lead_id','=',$leadID)->delete();
        return $deletedQTY;
    }

    #endregion

    #region RELATION METHODS
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    #endregion

    #region SERVICE METHODS
    #endregion
}
