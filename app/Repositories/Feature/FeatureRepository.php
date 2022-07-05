<?php

namespace Vanguard\Repositories\Feature;

interface FeatureRepository
{
    public function paginate($perPage, $search = null, $project_id = null, $category = null);
    public function all();
    public function find($id);
    public function findByProjectId($project_id);
    public function create($data, $json);
    public function update($id, array $data);
    public function delete($project_id, $id);
    
}
