<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 8.9.17
 * Time: 1:08
 */

namespace Salamek\Zasilkovna;


use Salamek\Zasilkovna\Model\IBranchStorage;

class Branch
{
    /** @var string */
    private $jsonEndpoint = 'https://www.zasilkovna.cz/api/v3/%s/branch.json';

    /** @var string */
    private $apiKey;

    /** @var IBranchStorage */
    private $branchStorage;

    /**
     * Branch constructor.
     * @param $apiKey
     * @param IBranchStorage $branchStorage
     */
    public function __construct($apiKey, IBranchStorage $branchStorage)
    {
        $this->apiKey = $apiKey;
        $this->branchStorage = $branchStorage;

        $this->jsonEndpoint = sprintf($this->jsonEndpoint, $this->apiKey);

        $this->initializeStorage();
    }

    /**
     * @throws \Exception
     */
    public function initializeStorage($force = false)
    {
        if (!$this->branchStorage->isStorageValid() || $force)
        {
            $result = file_get_contents($this->jsonEndpoint);
            if (!$result)
            {
                throw new \Exception('Failed to open JSON endpoint');
            }

            $data = json_decode($result, true);
            if (!$data || !array_key_exists('data', $data))
            {
                throw new \Exception('Failed to decode JSON');
            }
            
            $this->branchStorage->setBranchList($data['data']);
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getBranchList()
    {
        return $this->branchStorage->getBranchList();
    }

    /**
     * @param $id
     * @return object
     */
    public function find($id)
    {
        return $this->branchStorage->find($id);
    }
}
