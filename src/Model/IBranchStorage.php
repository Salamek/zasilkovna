<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 8.9.17
 * Time: 1:45
 */

namespace Salamek\Zasilkovna\Model;


interface IBranchStorage
{
    /**
     * @return array
     */
    public function getBranchList();

    /**
     * @param $id
     * @return array
     */
    public function find($id);

    /**
     * @param $branchList
     * @return void
     */
    public function setBranchList($branchList);

    /**
     * @return boolean
     */
    public function isStorageValid();
}