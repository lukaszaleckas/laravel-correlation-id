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
use LaravelCorrelationId\Tests\Helpers\JobMocker;
use LaravelCorrelationId\Tests\Stubs\CommandWithTraitStub;
use Mockery\MockInterface;

class SetCorrelationIdTest extends AbstractTest
{
    use WithFaker;
    use JobMocker;

    public function testListensToJobProcessingEvent(): void
    {
        Event::fake(JobProcessing::class);

        Event::assertListening(
            JobProcessing::class,
            SetCorrelationId::class
        );
    }

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
     * @dataProvider getCorrelationIdJobTestDataProvider
     */
    public function testSetsCorrelationIdWhenJobsHaveTrait(bool $isJobEncrypted): void
    {
        $correlationId = $this->faker->uuid;
        $command       = new CommandWithTraitStub();

        $command->setCorrelationId($correlationId);

        $this->runListener(
            $this->mockJob($command, $isJobEncrypted)
        );

        $this->assertSame(
            $correlationId,
            App::make(CorrelationIdService::class)->getCurrentCorrelationId()
        );
    }

    public static function getCorrelationIdJobTestDataProvider(): array
    {
        return  [
            'Encrypted'     => [true],
            'Not encrypted' => [false],
        ];
    }

    private function runListener(Job $jobMock): void
    {
        $event = new JobProcessing(
            $this->faker->word,
            $jobMock,
        );

        $listener = App::make(SetCorrelationId::class);
        $listener->handle($event);
    }
}
