<?php

namespace PHPUPhar {

    use Phar;
        use GuzzleHttp\Client;
        use Exception;

    class PHPUPhar
    {
        private $urlVersion;
        private $onlyCheck;
        private $versionNow;
        private $urlDownload;
        private $version = false;

        public function __construct(array $urlVersion, $onlyCheck, $versionNow = null, $urlDownload = null, $pharFileName = null)
        {
            $this->urlVersion = $urlVersion;
            $this->onlyCheck = $onlyCheck;
            $this->versionNow = trim(str_replace('.', '', $versionNow));
            $this->urlDownload = $urlDownload;
            $this->pharFileName = $pharFileName;
        }

        public function getVersion()
        {
            if (!$this->version) {
                $this->version = $this->getLastVersion();
            }
            return $this->version;
        }

        public function update()
        {
            $lastVersion = str_replace('.', '', $this->getVersion());

            // if ($lastVersion != str_replace('.', '', $this->versionNow)) {
                return $this->updatePhar();
            // }

            return false;
        }

        private function updatePhar()
        {
            $pharFile = str_replace($_SERVER['argv'][0], '', Phar::running(false)) . '/' . $this->pharFileName;
            // $pharFile = './' . $this->pharFileName;
                 try {
                     $client = new Client();
                    $response = $client->request('GET', $this->urlDownload);
                    $body = $response->getBody();
                    $phar = array();
                    while (!$body->eof()) {
                        $phar[] = $body->read(10240);
                    }
                    $phar = implode($phar);
                    $fopen = fopen($pharFile, 'w');
                    fwrite($fopen, $phar);
                    fclose($fopen);
                         // $this->_log('Updating Phar file');
                    copy($pharFile, $this->pharFileName);
                         // $this->_true('Updating Phar file');
                         // $this->_log('Removing temporary file');
                        // unlink($pharFile);
                         // $this->_true('Removing temporary file');
                        // $this->_true('PHPatr updated to: ' . $version);
                    // if(php_sapi_name() != 'cli' || $this->_return_logs){
                        // return implode($this->_echo);
                    // }
                         return true;
                 } catch (Exception $e) {
                    debug($e->getMessage());
                    // $this->_echo($e->getMessage());
                    // if(php_sapi_name() != 'cli' || $this->_return_logs){
                    //     return implode($this->_echo);
                    // }
                    //      die(1);
                 }
                return false;
        }

        private function getLastVersion()
        {
            $client = new Client([
                'base_uri' => $this->urlVersion['base'],
                'timeout' => 10,
                'allow_redirects' => false,
            ]);

            try {
                $response = $client->request('GET', $this->urlVersion['path']);
            } catch(Exception $e){
                return false;
            }
            $body = $response->getBody();
            $version = array();
            while (!$body->eof()) {
                $version[] = $body->read(1024);
            }
           return trim(implode($version));
           // return str_replace('.', '', $version);
        }
    }

}