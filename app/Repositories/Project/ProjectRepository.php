<?php

namespace Vanguard\Repositories\Project;

interface ProjectRepository
{
    public function paginate($perPage, $search = null);
    public function all();
    public function find($id);
    public function create(array $data, $json);
    public function update($id, array $data);
    public function delete($id);

    

}
