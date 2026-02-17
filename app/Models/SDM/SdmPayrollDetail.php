<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Model;

class SdmPayrollDetail extends Model
{
    protected $guarded = ['id'];

    public function payroll()
    {
        return $this->belongsTo(SdmPayroll::class, 'sdm_payroll_id');
    }
}
