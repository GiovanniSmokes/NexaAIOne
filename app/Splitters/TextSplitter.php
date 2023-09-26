<?php
namespace App\Splitters;

abstract class TextSplitter
{
    protected $chunk_size;
    protected $chunk_overlap;
    protected $keep_separator;
    protected $strip_whitespace;
    protected $extraMetadata = [];

    public function __construct($options = [])
    {
        $defaults = [
            'chunk_size' => 4000,
            'chunk_overlap' => 200,
            'keep_separator' => false,
            'strip_whitespace' => true
        ];
        $options = array_merge($defaults, $options);
        $this->chunk_size = $options['chunk_size'];
        $this->chunk_overlap = $options['chunk_overlap'];
        $this->keep_separator = $options['keep_separator'];
        $this->strip_whitespace = $options['strip_whitespace'];
        if ($this->chunk_overlap > $this->chunk_size) {
            throw new \InvalidArgumentException(
                "Got a larger chunk overlap ({$this->chunk_overlap}) than chunk size " .
                "({$this->chunk_size}), should be smaller."
            );
        }
    }

    abstract public function splitText($text);


    protected function joinDocs($docs, $separator)
    {
        $text = join($separator, $docs);
        if ($this->strip_whitespace) {
            $text = trim($text);
        }
        return $text === '' ? null : $text;
    }

    protected function mergeSplits($splits, $separator)
    {
        $separator_len = mb_strlen($separator);
        $docs = [];
        $current_doc = [];
        $total = 0;

        foreach ($splits as $d) {
            $len = mb_strlen($d);
            if ($total + $len + ($current_doc ? $separator_len : 0) > $this->chunk_size) {
                if ($total > $this->chunk_size) {
                    // Log warning here...
                    print "Warning: chunk size exceeded by a single split: $total > ".$this->chunk_size."\n";
                    // exit;
                    $this->extraMetadata['__warning'] = [
                        'message' => 'chunk size exceeded by a single split',
                        'chunk_size' => $this->chunk_size,
                        'split_size' => $total,
                        'split' => $d
                    ];
                }
                if ($current_doc) {
                    $doc = $this->joinDocs($current_doc, $separator);
                    if ($doc !== null) {
                        $docs[] = $doc;
                    }
                    while ($total > $this->chunk_overlap ||
                        ($total + $len + ($current_doc ? $separator_len : 0) > $this->chunk_size && $total > 0)) {
                        $total -= mb_strlen(array_shift($current_doc)) + ($current_doc ? $separator_len : 0);
                        if(count($current_doc) === 0){ break; };
                    }
                }
            }
            $current_doc[] = $d;
            $total += $len + ($current_doc ? $separator_len : 0);
        }
        $doc = $this->joinDocs($current_doc, $separator);
        if ($doc !== null) {
            $docs[] = $doc;
        }
        return $docs;
    }
}
