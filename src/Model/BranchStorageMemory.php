<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 8.9.17
 * Time: 2:12
 */

namespace Salamek\Zasilkovna\Model;


class BranchStorageMemory implements IBranchStorage
{
    private $branchList;

    public function __construct()
    {
        user_error('BranchStorageMemory is extremely slow and SHOULD NOT be used in production!', E_USER_NOTICE);
    }

    /**
     * @return mixed
     */
    public function getBranchList()
    {
        return $this->branchList;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        foreach($this->branchList AS $item)
        {
            if ($item['id'] == $id)
            {
                return $item;
                break;
            }
        }
    }

    /**
     * @param $branchList
     */
    public function setBranchList($branchList)
    {
        $this->branchList = $branchList;
    }

    /**
     * @return bool
     */
    public function isStorageValid()
    {
        return !empty($this->branchList);
    }
}