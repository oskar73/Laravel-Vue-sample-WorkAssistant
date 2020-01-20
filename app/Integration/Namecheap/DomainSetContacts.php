<?php


namespace App\Integration\Namecheap {

    class DomainSetContacts extends \Namecheap\Command\ACommand
    {
        public $domain = [];

        public function command()
        {
            return 'namecheap.domains.setContacts';
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
            foreach ($this->_response->DomainSetContactResult->attributes() as $key => $value) {
                $this->domain[$key] = (string) $value;
            }
        }
    }
}
