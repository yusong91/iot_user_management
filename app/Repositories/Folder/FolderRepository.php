<?php

namespace Vanguard\Repositories\Folder;

interface FolderRepository
{
    public function paginate($perPage, $search = null);
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

}
