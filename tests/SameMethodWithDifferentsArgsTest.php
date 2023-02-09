<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class SameMethodWithDifferentsArgsTest extends TestCase
{

    function testSameMethodWithDifferentsArgs()
    {
        $competition = new Competition('Competition 2023');
        $athlete1 = new Athlete('John');
        $athlete2 = new Athlete('Mary');
        $competition->athletes[] = $athlete1;
        $competition->athletes[] = $athlete2;

        $mockedCompetitionRepository = $this->createMock(CompetitionRepository::class);

        $mockedCompetitionRepository
            ->expects($this->once())
            ->method('findPosition')
            ->with($this->identicalTo($competition), $this->identicalTo($athlete1))
            ->willReturn(6);

        $mockedCompetitionRepository
            ->expects($this->once())
            ->method('findPosition')
            ->with($this->identicalTo($competition), $this->identicalTo($athlete2))
            ->willReturn(10);

        $processor = new CompetitionProcessor($mockedCompetitionRepository);

        $this->assertLessThan(10, $processor->checkAthlete($competition, $athlete1));
        $this->assertLessThan(10, $processor->checkAthlete($competition, $athlete2));
    }


}


class CompetitionProcessor
{

    private CompetitionRepository $repository;

    public function __construct(CompetitionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getRepository(): CompetitionRepository
    {
        return $this->repository;
    }

    public function checkAthlete(Competition $competition, Athlete $athlete): bool
    {
        $position = $this->repository->findPosition($competition, $athlete);
        return $position < 11;
    }

}

class CompetitionRepository
{

    public function findPosition(Competition $competition, Athlete $athlete): int
    {
        return 0;
    }

}

class Competition
{

    public string $description;

    public $athletes;

    public function __construct(string $description)
    {
        $this->description = $description;
    }

}

class Athlete
{

    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

}
