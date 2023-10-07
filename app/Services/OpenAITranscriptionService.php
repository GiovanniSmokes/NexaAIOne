<?php

namespace App\Services;

use App\Features\HasCaching;
use App\Features\LLMs\OpenAIAudio\HasOpenAIAudio;
use App\Features\HasDebug;
use Illuminate\Support\Facades\Http;

class OpenAITranscriptionService extends BaseService
{
    use HasCaching;
    use HasOpenAIAudio;
    use HasDebug;

    protected $features = [
        'cachingOptions',
        'openAITranscriptionOptions',
        'openAIAudioCommonOptions',
        'debugOptions'
    ];

    protected $ApiModel;
    protected $api_id;
    protected $app_id;
    protected $httpRequest;


    public function __construct($userOptions = [], $ApiModel = null, $httpRequest = null, $app = null){
        if($userOptions) {
            $this->ApiModel = $ApiModel;
            $this->api_id = $ApiModel->id;
            $this->app_id = $app->id ?? 0;
            $debug = [
                'requestOptions' => $userOptions
            ];
            $this->httpRequest = $httpRequest;
            if($httpRequest) $debug['header'] = $httpRequest->header();
            $this->initOptions($userOptions);

            $this->debug('input', $debug);
        }
    }

    public function execute(){
        try {
            $clearCache = false;

            if ($this->httpRequest && $this->httpRequest->file('file')->isValid()) {
                $this->options['file'] = $this->httpRequest->file->path() . '.' . $this->httpRequest->file('file')->getClientOriginalName();
            }elseif(isset($this->options['fileURL'])){
                $tempFile = tempnam(sys_get_temp_dir(), 'audioFile_');
                $response = Http::sink($tempFile)->get($this->options['fileURL']);
                $response->throw();
                $this->options['file'] = $tempFile;
            }

            $this->__cacheInit([
                'userMessage',
                'openaiBaseUri',
                'openaiApiVersion',
                'openaiOrganization',
                'model',
                'prompt',
                'temperature',
                'response_format',
                'language',
            ], md5_file($this->options['file']));

            if($this->options['clearCache']) $clearCache = $this->clearCache();

            if(!$this->options['file']) {
                if($clearCache) {
                    return $this->responseMessage(['status' => true, 'message' => 'Cache cleared']);
                }
                return $this->responseMessage(['message' => 'No file defined.']);
            }

            $serviceResponse = $this->getCache();
            if($serviceResponse) {
                return $this->responseMessage(['status' => true, 'serviceResponse' => $serviceResponse, 'cached'=>true, 'cacheScope' => $this->options['cacheScope']]);
            }

            $serviceResponse = $this->sendAudioToLLM($this->options['file'],'transcribe');
            
            $this->setCache($serviceResponse);

            $response = [
                'status' => true, 
                'serviceResponse' => $serviceResponse,
                'serviceMeta' => $this->getMeta($serviceResponse),
                'usage' => $this->usage
            ];

        } catch (\Throwable $th) {
            $response = [
                'status' => false, 
                'message' => $th->getMessage(), 
            ];
        }

        return $this->responseMessage($response);
    }

}
