<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;


class BranchStorageSqLite implements IBranchStorage
{
	private \PDO $database;

	private string $expiry;

	private string $tableName = 'branch';


	public function __construct(?string $databasePath = null, string $expiration = '-24 hours')
	{
		$this->expiry = $expiration;
		$this->database = new \PDO('sqlite:/' . ($databasePath ?? sys_get_temp_dir() . '/' . md5(__CLASS__) . '.sqlite'));
		$this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->createTable();
	}


	/**
	 * @return mixed
	 */
	public function getBranchList(): array
	{
		$query = 'SELECT `data` FROM ' . $this->tableName;
		$return = [];
		foreach ($this->database->query($query) as $row) {
			$return[] = json_decode($row['data'], true);
		}

		return $return;
	}


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
	 * @param $branchList
	 */
	public function setBranchList(array $branchList): void
	{
		$statement = $this->database->prepare('DROP TABLE ' . $this->tableName);
		$statement->execute([]);

		$this->createTable();

		$statement = $this->database->prepare('INSERT INTO ' . $this->tableName . ' (`id`, `data`, `created`) VALUES (?, ?, ?)');
		foreach ($branchList as $item) {
			$statement->execute([$item['id'], \json_encode($item), date('Y-m-d H:i:s')]);
		}
	}


	public function isStorageValid(): bool
	{
		$statement = $this->database->prepare('SELECT `created` FROM ' . $this->tableName . ' ORDER BY date(`created`) DESC LIMIT 1');
		$statement->execute();
		$found = $statement->fetch();

		return $found && (new \DateTime($found['created'])) > (new \DateTime)->modify($this->expiry);
	}


	private function createTable(): void
	{
		$statement = $this->database->prepare('CREATE TABLE IF NOT EXISTS ' . $this->tableName . ' (`id` INTEGER, `data` TEXT, `created` DATETIME)');
		$statement->execute();
	}
}
