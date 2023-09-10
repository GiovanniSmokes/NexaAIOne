<?php

namespace App\AIEndPoints;


class OpenAITranscriptionService {
    public const REQUEST_SCHEMA = [
            [
                "name" => "file",
                "type" => "file",
                "required" => true,
                "desc" => "The audio file object (not file name) to transcribe, in one of these formats: flac, mp3, mp4, mpeg, mpga, m4a, ogg, wav, or webm.",
            ],
            [
                "name" => "prompt",
                "type" => "string",
                "required" => false,
                "desc" => "An optional text to guide the model's style or continue a previous audio segment. The prompt should match the audio language.",
            ],
            [
                "name" => "response_format",
                "type" => "string",
                "required" => false,
                "desc" => "The format of the transcript output, in one of these options: json, text, srt, verbose_json, or vtt.",
                "default" => "text",
            ],
            [
                "name" => "temperature",
                "type" => "number",
                "required" => false,
                "desc" => "The sampling temperature, between 0 and 1. Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic. If set to 0, the model will use log probability to automatically increase the temperature until certain thresholds are hit.",
                "default" => 0,
            ],
            [
                "name" => "language",
                "type" => "string",
                "required" => false,
                "desc" => "The language of the input audio. Supplying the input language in ISO-639-1 format will improve accuracy and latency.",
            ]
    ];
    public const LLMS = [
            [
                'name' => 'Whisper',
                'description' => 'Whisper is a general-purpose speech recognition model. It is trained on a large dataset of diverse audio and is also a multi-task model that can perform multilingual speech recognition as well as speech translation and language identification.',
                'modelName' => 'whisper-1',
                'ownedBy' => 'OpenAI',
                'maxTokens' => null,
            ],
    ];
}
