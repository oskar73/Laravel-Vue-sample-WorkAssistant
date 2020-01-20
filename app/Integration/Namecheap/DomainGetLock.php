<?php

namespace App\Integration\Namecheap {

    class DomainGetLock extends \Namecheap\Command\ACommand
    {
        public $domain = [];

        public function command()
        {
            return 'namecheap.domains.getRegistrarLock';
        }

        public function params()
        {
            return [
                'DomainName' => null,
            ];
        }

        /**
         * Process domains array
         */
        protected function _postDispatch()
        {
            $this->domain = [];
            foreach ($this->_response->DomainGetRegistrarLockResult->attributes() as $key => $value) {
                $this->domain[$key] = (string) $value;
            }
        }
    }
}
