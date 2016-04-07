<?php

namespace GoGlobal;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;

class LoggerStream implements LoggerInterface
{
    /** @var resource */
    protected $_stream;

    /** @var bool */
    protected $_autoClose;

    /**
     * LoggerStream constructor.
     * @param resource $stream
     * @param bool $autoClose
     * @throws InvalidArgumentException
     */
    public function __construct($stream, $autoClose=false)
    {
        if(!is_resource($stream)) {
            throw new InvalidArgumentException('$stream must be an open resource');
        }
        $meta = stream_get_meta_data($stream);
        if(!is_writable($meta['url'])) {
            throw new InvalidArgumentException('$stream must be writable');
        }
        $this->_stream = $stream;
        $this->_autoClose = $autoClose;
    }

    public function __destruct()
    {
        if($this->_autoClose && is_resource($this->_stream)) {
            fclose($this->_stream);
        }
    }

    public function emergency($message, array $context=[])
    {
        $this->log(strtoupper(__FUNCTION__), $message, $context);
    }

    public function alert($message, array $context=[])
    {
        $this->log(strtoupper(__FUNCTION__), $message, $context);
    }

    public function critical($message, array $context=[])
    {
        $this->log(strtoupper(__FUNCTION__), $message, $context);
    }

    public function error($message, array $context=[])
    {
        $this->log(strtoupper(__FUNCTION__), $message, $context);
    }

    public function warning($message, array $context=[])
    {
        $this->log(strtoupper(__FUNCTION__), $message, $context);
    }

    public function notice($message, array $context=[])
    {
        $this->log(strtoupper(__FUNCTION__), $message, $context);
    }

    public function info($message, array $context=[])
    {
        $this->log(strtoupper(__FUNCTION__), $message, $context);
    }

    public function debug($message, array $context=[])
    {
        $this->log(strtoupper(__FUNCTION__), $message, $context);
    }

    public function log($level, $message, array $context=[])
    {
        if(count($context)>0) {
            $message.= PHP_EOL.json_encode($context, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
        fwrite($this->_stream, sprintf("%s [%s]: %s%s", strftime("%c"), $level, $message, PHP_EOL));
    }
    
}
