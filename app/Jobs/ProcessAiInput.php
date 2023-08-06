<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessAiInput implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private mixed $application;

    /**
     * Create a new job instance.
     *
     * @param string $content
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        //get all the questions from the applucation
        $questions = $this->application->content;

        //loop through the questions
        foreach($questions as $key => $question) {
            $request = Http::post('https://cdapi.goom.ai/api/v2/detect/ai_content', [
                'content' => $question,
            ]);

            if ($request->successful()) {
                //get the ai_statistics json object from $application and add the new data to it including the question id as the key and the value as the response ai_percentage
                $ai_statistics = $this->application->ai_statistics;
                $ai_statistics[$key] = $request->json()['ai_percentage'];
                $this->application->ai_statistics = $ai_statistics;
                $this->application->save();
            } else {
                $this->fail('Failed to get AI response from AI API for application ' . $this->application->id . ' with question: ' . $question);
            }
        }
    }
}
