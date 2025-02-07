<?php

namespace LaravelCorrelationId\Tests\Helpers;

use Illuminate\Contracts\Queue\Job;
use Mockery\MockInterface;

trait JobMocker
{
    public function mockJob(mixed $command, bool $isEncrypted): Job
    {
        /** @var MockInterface|Job $jobMock */
        $jobMock           = $this->mock(Job::class);
        $serializedCommand = serialize($command);

        $jobMock->shouldReceive('resolveName')
            ->once()
            ->andReturn($command);

        $jobMock->shouldReceive('payload')
            ->once()
            ->andReturn([
                'data' => [
                    'command' => $isEncrypted ? encrypt($serializedCommand) : $serializedCommand,
                ],
            ]);

        return $jobMock;
    }
}
