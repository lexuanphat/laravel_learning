<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository
{
    public function pluck()
    {

        // return Company::latest('name')->pluck('name', 'id');
        $data = [];
        $companies = Company::latest('name')->get();
        foreach ($companies as $company) {
            $data[$company->id] = $company->name . ' (' . $company->contacts()->count() . ') ';
        }
        return $data;
    }
}
