<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 8.9.17
 * Time: 2:12
 */

namespace Salamek\Zasilkovna\Model;

/**
 * Class BranchStorageFile
 * @package Salamek\Zasilkovna\Model
 */
class BranchStorageFile implements IBranchStorage
{
    /** @var array */
    private $branchList;

    /** @var string */
    private $filePath;

    /** @var bool */
    private $storageValid = false;

    public function __construct($filePath = null)
    {
        if (is_null($filePath))
        {
            $filePath = sys_get_temp_dir().'/'.md5(__CLASS__);
        }

        $this->filePath = $filePath;

        if (file_exists($this->filePath))
        {
            $this->branchList = json_decode(file_get_contents($this->filePath), true);
            if (!empty($this->branchList))
            {
                $this->storageValid = true;
            }
        }
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

        file_put_contents($this->filePath, json_encode($branchList));
        $this->storageValid = true;
    }

    /**
     * @return bool
     */
    public function isStorageValid()
    {
        return $this->storageValid;
    }
}