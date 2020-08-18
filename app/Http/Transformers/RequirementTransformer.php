<?php
namespace App\Http\Transformers;

class RequirementTransformer extends Transformer{
    
    public function transform($requirement)
    {
        return [
            'name' => $requirement['name'],
            'description' => $requirement['description']
        ];
    }
    
}