<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;

/**
 * @internal
 */
final class BranchStorageSqLite implements IBranchStorage
{
    private \PDO $database;

    private string $expiration;

    private string $tableName = 'branch';


    public function __construct(?string $cachePath = null, string $expiration = '-24 hours')
    {
        $this->expiration = $expiration;
        $sqLiteStoragePath = ($cachePath ?? sys_get_temp_dir() . '/' . md5(get_class()) . '.sqlite');
        $this->database = new \PDO('sqlite:/' . $sqLiteStoragePath);
        $this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->createTable();
    }


    /**
     * @return mixed[][]
     */
    public function getBranchList(): array
    {
        $return = [];
        foreach ($this->database->query('SELECT `data` FROM ' . $this->tableName) as $row) {
            $return[] = json_decode($row['data'], true);
        }

        return $return;
    }


    /**
     * @return mixed[]
     */
    public function find(int $id): ?array
    {
        $statement = $this->database->prepare('SELECT `data` FROM ' . $this->tableName . ' WHERE id = ?');
        $statement->execute([$id]);

        if ($found = $statement->fetch()) {
            return json_decode($found['data'], true);
        }

        return null;
    }


    /**
     * @param mixed[] $branchList
     */
    public function setBranchList(array $branchList): void
    {
        $statement = $this->database->prepare('DROP TABLE ' . $this->tableName);
        $statement->execute([]);

        $this->createTable();

        $sql = sprintf('INSERT INTO %s (`id`, `data`, `created`) VALUES (?, ?, ?)', $this->tableName);
        $statement = $this->database->prepare($sql);
        foreach ($branchList as $item) {
            $statement->execute([$item['id'], \json_encode($item), date('Y-m-d H:i:s')]);
        }
    }


    public function isStorageValid(): bool
    {
        $sql = sprintf('SELECT `created` FROM %s ORDER BY date(`created`) DESC LIMIT 1', $this->tableName);
        $statement = $this->database->prepare($sql);
        $statement->execute();

        $found = $statement->fetch();
        if ($found) {
            $createdDateTime = new \DateTime($found['created']);
            $expiresDateTime = (new \DateTime())->modify($this->expiration);
            return $createdDateTime > $expiresDateTime;
        }
        return false;
    }


    private function createTable(): void
    {
        $sql = sprintf(
            'CREATE TABLE IF NOT EXISTS %s (`id` INTEGER, `data` TEXT, `created` DATETIME)',
            $this->tableName
        );
        $this->database->prepare($sql)
            ->execute();
    }
}
