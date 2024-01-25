<?php

namespace LaravelCorrelationId\Tests\Feature\Listeners;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use LaravelCorrelationId\CorrelationIdService;
use LaravelCorrelationId\Listeners\SetCorrelationId;
use LaravelCorrelationId\Tests\AbstractTest;
use LaravelCorrelationId\Tests\Stubs\CommandWithTraitStub;
use Mockery\MockInterface;

class SetCorrelationIdTest extends AbstractTest
{
    use WithFaker;

    /**
     * @return void
     */
    public function testListensToJobProcessingEvent(): void
    {
        Event::fake(JobProcessing::class);

        Event::assertListening(
            JobProcessing::class,
            SetCorrelationId::class
        );
    }

    /**
     * @return void
     */
    public function testReturnsEarlyWhenJobsDontHaveTrait(): void
    {
        /** @var MockInterface|Job $jobMock */
        $jobMock = $this->mock(Job::class);
        $command = new class {
            // ...
        };

        $jobMock->shouldReceive('resolveName')
            ->once()
            ->andReturn($command);

        $this->runListener($jobMock);
    }

    /**
     * @return void
     */
    public function testSetsCorrelationIdWhenJobsHaveTrait()
    {
        /** @var MockInterface|Job $jobMock */
        $jobMock       = $this->mock(Job::class);
        $correlationId = $this->faker->uuid;
        $command       = new CommandWithTraitStub();

        $command->setCorrelationId($correlationId);

        $jobMock->shouldReceive('resolveName')
            ->once()
            ->andReturn($command);

        $jobMock->shouldReceive('payload')
            ->once()
            ->andReturn([
                'data' => [
                    'command' => serialize($command),
                ],
            ]);

        $this->runListener($jobMock);

        $this->assertSame(
            $correlationId,
            App::make(CorrelationIdService::class)->getCurrentCorrelationId()
        );
    }

    /**
     * @param MockInterface|Job $jobMock
     * @return void
     */
    private function runListener(MockInterface|Job $jobMock): void
    {
        $event = new JobProcessing(
            $this->faker->word,
            $jobMock,
        );

        $listener = App::make(SetCorrelationId::class);
        $listener->handle($event);
    }
}
