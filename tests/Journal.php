<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

use Exception;
use midorikocak\nanodb\DatabaseInterface;
use ReflectionException;

use function array_map;
use function key;
use function reset;

class Journal
{
    private DatabaseInterface $db;

    private $tableName = 'entries';

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    /**
     * @return Entry
     * @throws Exception
     */
    public function read(string $id)
    {
        $data = $this->db->select($this->tableName)->where('id', $id)->fetch();
        if (!$data) {
            throw new Exception('not found');
        }
        return Entry::fromArray($data);
    }

    public function readAll(array $constraints = [], array $columns = ['*']): array
    {
        $db = $this->db->select($this->tableName, $columns);

        if (!empty($constraints)) {
            $value = reset($constraints);
            $key = key($constraints);
            $db->where($key, $value);

            unset($constraints[key($constraints)]);

            foreach ($constraints as $key => $value) {
                $db->and($key, $value);
            }
        }

        $db->execute();
        return array_map(fn($data) => Entry::fromArray($data), $db->fetchAll());
    }

    /**
     * @param Entry $entry
     * @return Entry
     * @throws ReflectionException
     */
    public function save($entry)
    {
        if ($entry->getId()) {
            $id = $entry->getId();
            $entryData = $entry->toArray();
            unset($entryData['id']);
            $this->db->update($this->tableName, $entryData)->where('id', $id)->execute();
            return $entry;
        }

        $this->db->insert($this->tableName, $entry->toArray())->execute();

        $lastInsertId = $this->db->lastInsertId();
        $entry->setId($lastInsertId);
        return $entry;
    }

    /**
     * @param Entry $entry
     */
    public function remove($entry): int
    {
        $id = $entry->getId();
        $this->db->delete('users')->where('id', $id)->execute();
        return $this->db->rowCount();
    }
}
