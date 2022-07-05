<?php

namespace Vanguard\Repositories\AsignUser;

interface AsignUserRepository
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function get_list_role($user_id, $role_id, $family_id);
    public function find_parent($parent_id);
}
