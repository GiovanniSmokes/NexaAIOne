<?php

namespace App\AIEndPoints;

class OpenAIChatCompletionService
{
    public const REQUEST_SCHEMA = [
            [
                "name" => "session",
                "type" => "string",
                "required" => false,
                "desc" => "Unique session id for this conversation.",
                "default" => "global",
            ],
            [
                "name" => "system_message",
                "type" => "string",
                "required" => false,
                "desc" => "Provides high-level directives or context for the entire conversation. Typically used at the beginning of the chat. If you wish to update or add more context during a conversation, use the 'update_system_message' parameter.",
            ],
            [
                "name" => "preSystemMessage",
                "type" => "string",
                "required" => false,
                "desc" => "An optional instruction or context provided prior to the main system message (will be used only if system_message provided as API option), setting the foundational tone or guidelines for the ensuing conversation",
                "if-api-option" => "system_message",
            ],
            [
                "name" => "postParamSystemMessage",
                "type" => "string",
                "required" => false,
                "desc" => "An optional instruction or context appended after the main system message (will be used only if system_message provided as API option), used to fine-tune or further direct the model's responses in the conversation.",
                "if-api-option" => "system_message",
            ],
            [
                "name" => "update_system_message",
                "type" => "string",
                "required" => false,
                "desc" => "Allows you to send additional system-level instructions during a conversation, helping to refine or redirect the model's behavior as the conversation progresses.",
            ],

            [
                "name" => "user_message",
                "type" => "string",
                "required" => true,
                "desc" => "Instruct, question, or guide the model, eliciting specific responses based on the input provided.",
            ],
            ["name" => "max_tokens", "type" => "integer", "required" => false, "desc" => "The maximum number of tokens to generate in the chat completion."],
            [
                "name" => "stream",
                "type" => "boolean",
                "required" => false,
                "desc" => "If set, partial message deltas will be sent, like in ChatGPT. Tokens will be sent as data-only server-sent events as they become available, with the stream terminated by a data: [DONE] message.",
                "default" => false,
            ],
            [
                "name" => "debug",
                "type" => "boolean",
                "required" => false,
                "desc" => "Retains all request and response data, facilitating issue troubleshooting and prompt optimization",
                "default" => false,
            ],
            [
                "name" => "model",
                "type" => "string",
                "required" => false,
                "desc" => "The LLM model to use.",
            ],
            [
                "name" => "messages",
                "type" => "json",
                "required" => false,
                "desc" => "A list of messages comprising the conversation so far. check https://platform.openai.com/docs/api-reference/chat/create#messages",
            ],
            ["name" => "temperature", "type" => "number", "required" => false, "desc" => "What sampling temperature to use, between 0 and 2. Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic.

We generally recommend altering this or top_p but not both.", "default" => 1],

            ["name" => "top_p", "type" => "number", "required" => false, "desc" => "An alternative to sampling with temperature, called nucleus sampling, where the model considers the results of the tokens with top_p probability mass. So 0.1 means only the tokens comprising the top 10% probability mass are considered.

            We generally recommend altering this or temperature but not both.", "default" => 1],

            ["name" => "chat_choices", "type" => "number", "required" => false, "desc" => "How many chat completion choices to generate for each input message.", "default" => 1, 'apiName'=>'n'],

            ["name" => "stop_sequences", "type" => "string / array / null", "required" => false, "desc" => "Up to 4 sequences where the API will stop generating further tokens.", "default" => null, 'apiName'=>'stop'],

            ["name" => "presence_penalty", "type" => "number", "required" => false, "desc" => "Number between -2.0 and 2.0. Positive values penalize new tokens based on whether they appear in the text so far, increasing the model's likelihood to talk about new topics.", "default" => 0],

            ["name" => "frequency_penalty", "type" => "number", "required" => false, "desc" => "Number between -2.0 and 2.0. Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model's likelihood to repeat the same line verbatim.", "default" => 0],

            ["name" => "logit_bias", "type" => "json", "required" => false, "desc" => "Modify the likelihood of specified tokens appearing in the completion.

            Accepts a json object that maps tokens (specified by their token ID in the tokenizer) to an associated bias value from -100 to 100. Mathematically, the bias is added to the logits generated by the model prior to sampling. The exact effect will vary per model, but values between -1 and 1 should decrease or increase likelihood of selection; values like -100 or 100 should result in a ban or exclusive selection of the relevant token.", "default" => null],

            ["name" => "user", "type" => "string", "required" => false, "desc" => "A unique identifier representing your end-user, which can help OpenAI to monitor and detect abuse.", "default" => null]
        ];
    public const LLMS = [
        [
            'name' => 'GPT-4',
            'description' => 'More capable than any GPT-3.5 model, able to do more complex tasks, and optimized for chat. Will be updated with our latest model iteration 2 weeks after it is released',
            'modelName' => 'gpt-4',
            'ownedBy' => 'OpenAI',
            'maxTokens' => 8192,
        ],
        [
            'name' => 'GPT-4 32k',
            'description' => 'Same capabilities as the standard gpt-4 mode but with 4x the context length. Will be updated with our latest model iteration.',
            'modelName' => 'gpt-4-32k',
            'ownedBy' => 'OpenAI',
            'maxTokens' => 32768,
        ],
        [
            'name' => 'GPT-3.5 Turbo',
            'description' => 'Most capable GPT-3.5 model and optimized for chat at 1/10th the cost of text-davinci-003. Will be updated with our latest model iteration 2 weeks after it is released.',
            'modelName' => 'gpt-3.5-turbo',
            'ownedBy' => 'OpenAI',
            'maxTokens' => 4097,
        ],
        [
            'name' => 'GPT-3.5 Turbo 16k',
            'description' => 'Same capabilities as the standard gpt-3.5-turbo model but with 4 times the context.',
            'modelName' => 'gpt-3.5-turbo-16k',
            'ownedBy' => 'OpenAI',
            'maxTokens' => 16385,
        ]
    ];
}
