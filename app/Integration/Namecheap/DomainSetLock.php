<?php

namespace App\Integration\Namecheap {

    class DomainSetLock extends \Namecheap\Command\ACommand
    {
        public $domain = [];

        public function command()
        {
            return 'namecheap.domains.setRegistrarLock';
        }

        public function params()
        {
            return [
                'DomainName' => null,
                'LockAction' => 'LOCK',
            ];
        }

        /**
         * Process domains array
         */
        protected function _postDispatch()
        {
            $this->domain = [];
            foreach ($this->_response->DomainSetRegistrarLockResult->attributes() as $key => $value) {
                $this->domain[$key] = (string) $value;
            }
        }
    }
}
