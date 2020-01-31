<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

use ReflectionException;

final class App
{
    use AuthorizationTrait;

    private Journal $journal;

    public function __construct(Journal $journal)
    {
        $this->journal = $journal;
    }

    /**
     * @throws ReflectionException
     */
    public function addEntry(string $content)
    {
        $this->checkLogin();

        /**
         * @var Entry $entry ;
         */
        $entry = new Entry($content);
        $this->journal->save($entry);
    }

    public function getEntries(): array
    {
        $this->checkLogin();
        return $this->journal->readAll();
    }
}
