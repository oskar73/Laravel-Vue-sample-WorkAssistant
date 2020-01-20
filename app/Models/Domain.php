<?php

namespace App\Models;

use App\Integration\Namecheap\NamecheapExtra;
use Carbon\Carbon;
use Namecheap\Api;
use Namecheap\Config;
use Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Http;

class Domain extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'domains';
    protected $guarded = ['id', 'created_at', 'updated_at'];


    const VALIDATION_MSGS = [
        'tld.exists' => 'Sorry, we doesn\'t support that TLD. Please try to search with other TLDs.',
    ];
    const CONTACT_RULE_CUSTOM_MSG = [
        'phone' => 'The :attribute field contains an invalid number.',
    ];
    const SET_HOST_RULE_CUSTOM_MSG = [
        'phone' => 'The :attribute field contains an invalid number.',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function website()
    {
        return $this->belongsTo(Website::class, 'web_id')->withDefault();
    }

    public function setHostsRule($request)
    {
        $rule['types.*'] = 'required|in:A,AAAA,ALIAS,CAA,CNAME,MX,MXE,NS,TXT,URL,URL301,FRAME';
        $rule['hosts.*'] = 'required|max:191';
        $rule['values.*'] = 'required';
        $rule['ttls.*'] = 'required|integer|min:60|max:60000';

        return $rule;
    }
    public function updateDnsRule()
    {
        $rule['nameserver.1'] = 'required|max:45';
        $rule['nameserver.2'] = 'required|max:45';

        return $rule;
    }

    public function domainRule()
    {
        $rule['domain'] = 'required';
        $rule['domainName'] = 'required';
        $rule['tld'] = 'required|exists:domain_tlds,Name,status,1,IsApiRegisterable,true';

        return $rule;
    }

    public function contactRule($request)
    {
        $rule['firstName'] = 'required|max:45';
        $rule['lastName'] = 'required|max:45';
        $rule['email'] = 'required|email';
        $rule['address1'] = 'required|max:191';
        $rule['address2'] = 'max:191';
        $rule['city'] = 'required|max:45';
        $rule['state'] = 'required|max:45';
        $rule['country'] = 'required|exists:mysql2.country,iso';
        $rule['postalCode'] = 'required|postal_code:'.$request->country;
        $rule['phoneCode'] = 'required|exists:mysql2.country,phonecode';
        $rule['phoneNumber'] = 'required|phone:AUTO,'.$request->country;
        if ($request->saveThis) {
            $rule['contactName'] = 'required|max:45';
        }

        return $rule;
    }
    public function saveContact($request)
    {
        user()->domainContacts()->create($request->except(['contact','saveThis']));
    }
    public function checkDomain($request)
    {
        $domain = $request->domain;
        $tld = $request->tld;
        $domainName = $request->domainName;

        $recommends = DomainTld::where('status', 1)
            ->where('recommend', 1)
            ->where('status', 1)
            ->where('IsApiRegisterable', 'true')
            ->orderBy('sortOrder')
            ->select('id', 'Name')
            ->pluck("Name")
            ->toArray();

        if (! in_array($tld, $recommends)) {
            $recommends[] = $tld;
        }
        Session::put('tldArray', $recommends);
        Session::put('domain', $domain);

        $domainLists = $this->getList($domainName, $recommends);

        return $this->searchList($domainLists);
    }
    public function getList($domainName, $tlds)
    {
        $domainLists = '';
        foreach ($tlds as $key => $tld) {
            if ($key == 0) {
                $domainLists .= $domainName.".".$tld;
            } else {
                $domainLists .= ",".$domainName.".".$tld;
            }
        }

        return $domainLists;
    }
    public function getConfig()
    {
        $config = new Config();

        $namecheap = optional(option("namecheap", []));

        $config->apiUser($namecheap['user'])
            ->apiKey($namecheap['key'])
            ->clientIp($namecheap['ip'])
            ->sandbox($namecheap['sandbox']);

        return $config;
    }
    public function searchList($domainLists)
    {
        $proxyUrl = '/namecheap/domains/check';
        return $this->getProxyResponse($proxyUrl, $domainLists);

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = Api::factory($this->getConfig(), 'domains.check');
        $command->domainList($domainLists)->dispatch();
        if ($command->status() == 'ok') {
            $resp = $command->getResponse();

            $result['result'] = $resp->DomainCheckResult;
            $result['status'] = 1;

            return $result;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['result'] = $errors;
            $result['status'] = 0;

            return $result;
        }
    }
    public function loadMore()
    {
        $domain = Session::get("domain");
        $tldArray = Session::get("tldArray");

        $tlds = DomainTld::whereNotIn("Name", $tldArray)
            ->where('status', 1)
            ->where('IsApiRegisterable', 'true')
            ->take(20)
            ->select("id", "Name")
            ->pluck("Name")
            ->toArray();

        $tldArray = array_unique(array_merge($tldArray, $tlds));
        Session::put('tldArray', $tldArray);
        $domainLists = $this->getList(getDomainName($domain), $tlds);
        $result = $this->searchList($domainLists);

        return $result;
    }

    public function duration($domain, $action)
    {
        $domainArray = Session::get("domainArray") ?? [];
        $tld = getDomainTld($domain);

        $check = $domainArray[$tld];
        Session::put('domainCheck', $check);

        $tldRecord = DomainTld::where("Name", $tld)->first();
        Session::put('tldRecord', $tldRecord);


        if ($check['Available'] == true && $check['IsPremiumName'] == 'false') {
            $prices = DomainPrice::where('tld', $tld)
                ->where('status', 1)
                ->where('Action', $action)
                ->where('Duration', '<=', $tldRecord->MaxRegisterYears)
                ->get();

            return $prices;
        } else {
            return false;
        }
    }

    public function getData($domain, $duration, $contact)
    {
        $data = [
            'DomainName' => $domain,
            'Years' => $duration,
            'RegistrantFirstName' => $contact['firstName'],
            'RegistrantLastName' => $contact['lastName'],
            'RegistrantAddress1' => $contact['address1'],
            'RegistrantAddress2' => $contact['address2'],
            'RegistrantCity' => $contact['city'],
            'RegistrantStateProvince' => $contact['state'],
            'RegistrantPostalCode' => $contact['postalCode'],
            'RegistrantCountry' => $contact['country'],
            'RegistrantPhone' => makePhoneNum($contact['phoneCode'], $contact['phoneNumber']),
            'RegistrantEmailAddress' => $contact['email'],
            'TechFirstName' => $contact['firstName'],
            'TechLastName' => $contact['lastName'],
            'TechAddress1' => $contact['address1'],
            'TechAddress2' => $contact['address2'],
            'TechCity' => $contact['city'],
            'TechStateProvince' => $contact['state'],
            'TechPostalCode' => $contact['postalCode'],
            'TechCountry' => $contact['country'],
            'TechPhone' => makePhoneNum($contact['phoneCode'], $contact['phoneNumber']),
            'TechEmailAddress' => $contact['email'],
            'AdminFirstName' => $contact['firstName'],
            'AdminLastName' => $contact['lastName'],
            'AdminAddress1' => $contact['address1'],
            'AdminAddress2' => $contact['address2'],
            'AdminCity' => $contact['city'],
            'AdminStateProvince' => $contact['state'],
            'AdminPostalCode' => $contact['postalCode'],
            'AdminCountry' => $contact['country'],
            'AdminPhone' => makePhoneNum($contact['phoneCode'], $contact['phoneNumber']),
            'AdminEmailAddress' => $contact['email'],
            'AuxBillingFirstName' => $contact['firstName'],
            'AuxBillingLastName' => $contact['lastName'],
            'AuxBillingAddress1' => $contact['address1'],
            'AuxBillingAddress2' => $contact['address2'],
            'AuxBillingCity' => $contact['city'],
            'AuxBillingStateProvince' => $contact['state'],
            'AuxBillingPostalCode' => $contact['postalCode'],
            'AuxBillingCountry' => $contact['country'],
            'AuxBillingPhone' => makePhoneNum($contact['phoneCode'], $contact['phoneNumber']),
            'AuxBillingEmailAddress' => $contact['email'],
            'Extended ' => getDomainTld($domain),
            'AddFreeWhoisguard' => "Yes",
            'WGEnabled' => "Yes",
        ];

        return $data;
    }
    public function registerDomain($domain, $duration)
    {
        $contact = Session::get("contact");
        $data = $this->getData($domain, $duration, $contact);

        $proxyUrl = '/namecheap/domains/create';
        return $this->getProxyResponse($proxyUrl, $data);

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = Api::factory($this->getConfig(), 'domains.create');
        $command->setParams($data)->dispatch();
        if ($command->status() == 'ok') {
            $resp = $command->getResponse();

            $result['result'] = $resp->DomainCreateResult;
            $result['status'] = 1;

            return $result;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['result'] = $errors;
            $result['status'] = 0;

            return $result;
        }
    }

    public function insertDomain($registerResult, $package_id = null)
    {
        $duration = Session::get("duration");
        $price = DomainPrice::where('Action', 'register')
            ->where('tld', getDomainTld($registerResult['Domain']))
            ->where('Duration', $duration)
            ->where('status', 1)
            ->first()->sumPrice ?? '0';

        $data['user_id'] = user()->id;
        $data['name'] = $registerResult['Domain'];
        $data['domainID'] = $registerResult['DomainID'];
        $data['freePositiveSSL'] = $registerResult['FreePositiveSSL'] == 'true'? 1:0;
        $data['nonRealTimeDomain'] = $registerResult['NonRealTimeDomain'] == 'true'? 1:0;
        $data['orderID'] = $registerResult['OrderID'];
        $data['registered'] = $registerResult['Registered'] == 'true'? 1:0;
        $data['transactionID'] = $registerResult['TransactionID'];
        $data['whoisguardEnable'] = $registerResult['WhoisguardEnable'] == 'true'? 1:0;
        $data['expired_at'] = Carbon::now()->addYears($duration)->toDateString();
        $data['chargedAmountNC'] = $registerResult['ChargedAmount'];
        $data['chargedAmountBB'] = $price;
        $data['pointed'] = 0;
        $data['package_id'] = $package_id;

        Session::forget("duration");
        Session::forget("pickDomain");
        Session::forget("domain");
        Session::forget("domainArray");
        Session::forget("contact");

        return $this->create($data);
    }

    public function getRenew()
    {
        $tld = getDomainTld($this->name);
        $domain = $this;
        $domainTld = DomainTld::where("Name", $tld)
            ->where('IsApiRenewable', 'true')
            ->first();
        $domainPrices = DomainPrice::where("tld", $tld)
            ->where("Action", "renew")
            ->where("status", 1)
            ->get();

        $view = view("components.domain.renew", compact("domainTld", "domainPrices", "domain"))->render();

        return $view;
    }

    public function addRecord($created)
    {
        $data['SLD'] = getDomainName($created->name);
        $data['TLD'] = getDomainTld($created->name);
        $data['HostName1'] = '@';
        $data['RecordType1'] = 'A';
        $data['Address1'] = optional(option("ssh", []))['ip'];
            //    $data['HostName2']= 'www';
            //    $data['RecordType2']= 'CNAME';
            //    $data['Address2']= $created->name;
        $data['TTL'] = 60;
        $data['TTL1'] = 60;

        $proxyUrl = '/namecheap/domains/dns/set-hosts';

        $result = $this->getProxyResponse($proxyUrl, $data);
        if ($result['status'] == 1) {
            $created->pointed = 1;
            $created->save();
        }
        return $result;

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = Api::factory($this->getConfig(), 'domains.dns.setHosts');
        $command->setParams($data)->dispatch();

        if ($command->status() == 'ok') {
            $created->pointed = 1;
            $created->save();

            $resp = $command->getResponse();

            $result['result'] = $resp->DomainDNSSetHostsResult;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['result'] = $errors;
            $result['status'] = 0;
        }

        return $result;
    }

    public function possibleRenew($domain, $duration)
    {
        $tld = DomainTld::where("Name", getDomainTld($domain->name))
        ->where("MinRenewYears", "<=", $duration)
        ->where("MaxRenewYears", ">=", $duration)
        ->where("IsApiRenewable", "true")->first();

        $price = DomainPrice::where("tld", getDomainTld($domain->name))
        ->where('Action', 'renew')
        ->where('Duration', $duration)->where('status', 1)->first();

        if ($tld == null || $price == null) {
            return 'false';
        } else {
            return $price->sumPrice;
        }
    }
    public function renewDomain($domain, $duration)
    {
        $data['DomainName'] = $domain->name;
        $data['Years'] = $duration;

        $proxyUrl = '/namecheap/domains/renew';
        $result = $this->getProxyResponse($proxyUrl, $data);
        if ($result['status'] == 1) {
            $resp = $result['result'];
            $domain->expired_at = Carbon::parse($resp['DomainDetails']['ExpiredDate'])->toDateString();
            $domain->save();

            $result['domain'] = $domain;
        } else {
            $result['domain'] = null;
        }

        return $result;

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = Api::factory($this->getConfig(), 'domains.renew');
        $command->setParams($data)->dispatch();
        if ($command->status() == 'ok') {
            $resp = $command->getResponse();
            $domain->expired_at = Carbon::parse($resp->DomainRenewResult->DomainDetails->ExpiredDate[0])->toDateString();
            $domain->save();


            $result['result'] = $resp->DomainRenewResult;
            $result['domain'] = $domain;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['result'] = $errors;
            $result['status'] = 0;
            $result['domain'] = null;
        }

        return $result;
    }
    public function getDetail()
    {
        $data = [
            'ListType' => 'ALL',
            'SearchTerm' => $this->name,
        ];
        $proxyUrl = '/namecheap/domains/get-list';
        return $this->getProxyResponse($proxyUrl, $data);

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = Api::factory($this->getConfig(), 'domains.getList');
        $command->setParams($data)->dispatch();

        if ($command->status() == 'ok') {
            $resp = $command->getResponse();

            $result['result'] = $resp->DomainGetListResult->Domain;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['result'] = $errors;
            $result['status'] = 0;
        }

        return $result;
    }
    public function getContact()
    {
        $data = [
            'DomainName' => $this->name,
        ];
        $proxyUrl = '/namecheap/domains/get-contacts';

        return $this->getProxyResponse($proxyUrl, $data);

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = Api::factory($this->getConfig(), 'domains.getContacts');
        $command->setParams($data)->dispatch();

        if ($command->status() == 'ok') {
            $resp = $command->getResponse();

            $result['result'] = $resp->DomainContactsResult;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['result'] = $errors;
            $result['status'] = 0;
        }

        return $result;
    }
    public function setContact($request)
    {
        $data = [
            'DomainName' => $this->name,
            'RegistrantFirstName' => $request->firstName,
            'RegistrantLastName' => $request->lastName,
            'RegistrantAddress1' => $request->address1,
            'RegistrantAddress2' => $request->address2,
            'RegistrantCity' => $request->city,
            'RegistrantStateProvince' => $request->state,
            'RegistrantPostalCode' => $request->postalCode,
            'RegistrantCountry' => $request->country,
            'RegistrantPhone' => makePhoneNum($request->phoneCode, $request->phoneNumber),
            'RegistrantEmailAddress' => $request->email,
            'TechFirstName' => $request->firstName,
            'TechLastName' => $request->lastName,
            'TechAddress1' => $request->address1,
            'TechAddress2' => $request->address2,
            'TechCity' => $request->city,
            'TechStateProvince' => $request->state,
            'TechPostalCode' => $request->postalCode,
            'TechCountry' => $request->country,
            'TechPhone' => makePhoneNum($request->phoneCode, $request->phoneNumber),
            'TechEmailAddress' => $request->email,
            'AdminFirstName' => $request->firstName,
            'AdminLastName' => $request->lastName,
            'AdminAddress1' => $request->address1,
            'AdminAddress2' => $request->address2,
            'AdminCity' => $request->city,
            'AdminStateProvince' => $request->state,
            'AdminPostalCode' => $request->postalCode,
            'AdminCountry' => $request->country,
            'AdminPhone' => makePhoneNum($request->phoneCode, $request->phoneNumber),
            'AdminEmailAddress' => $request->email,
            'AuxBillingFirstName' => $request->firstName,
            'AuxBillingLastName' => $request->lastName,
            'AuxBillingAddress1' => $request->address1,
            'AuxBillingAddress2' => $request->address2,
            'AuxBillingCity' => $request->city,
            'AuxBillingStateProvince' => $request->state,
            'AuxBillingPostalCode' => $request->postalCode,
            'AuxBillingCountry' => $request->country,
            'AuxBillingPhone' => makePhoneNum($request->phoneCode, $request->phoneNumber),
            'AuxBillingEmailAddress' => $request->email,
            'Extended ' => getDomainTld($this->name),
        ];
        $proxyUrl = '/namecheap/domains/set-contacts';

        return $this->getProxyResponse($proxyUrl, $data);

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = NamecheapExtra::factory($this->getConfig(), 'domains.setContacts');
        $command->setParams($data)->dispatch();

        if ($command->status() == 'ok') {
            $resp = $command->getResponse();

            $result['data'] = $resp->DomainSetContactResult;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['data'] = $errors;
            $result['status'] = 0;
        }

        return $result;
    }
    public function getHosts()
    {
        $data = [
            'SLD' => getDomainName($this->name),
            'TLD' => getDomainTld($this->name),
        ];
        $proxyUrl = '/namecheap/domains/dns/get-hosts';

        return $this->getProxyResponse($proxyUrl, $data);

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = Api::factory($this->getConfig(), 'domains.dns.getHosts');
        $command->setParams($data)->dispatch();

        if ($command->status() == 'ok') {
            $resp = $command->getResponse();

            $result['result'] = $resp->DomainDNSGetHostsResult;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['result'] = $errors;
            $result['status'] = 0;
        }

        return $result;
    }
    public function getDns()
    {
        $data = [
            'SLD' => getDomainName($this->name),
            'TLD' => getDomainTld($this->name),
        ];
        $proxyUrl = '/namecheap/domains/dns/get-list';

        return $this->getProxyResponse($proxyUrl, $data);

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = Api::factory($this->getConfig(), 'domains.dns.getList');
        $command->setParams($data)->dispatch();

        if ($command->status() == 'ok') {
            $resp = $command->getResponse();

            $result['data'] = $resp->DomainDNSGetListResult->Nameserver;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['data'] = $errors;
            $result['status'] = 0;
        }

        return $result;
    }
    public function updateDns($nameservers)
    {
        $proxyUrl = '/namecheap/domains/dns/set-custom';

        $resp = $this->getProxyResponse($proxyUrl, [
            'servers'   =>  $nameservers,
            'domain'    =>  $this->name
        ]);

        $result['status'] = $resp['status'];
        $result['data'] = $resp['result'];
        return $result;

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = Api::factory($this->getConfig(), 'domains.dns.setCustom');
        $command->domainName($this->name)->nameservers($nameservers)->dispatch();

        if ($command->status() == 'ok') {
            $resp = $command->getResponse();

            $result['data'] = $resp->DomainDNSSetCustomResult;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['data'] = $errors;
            $result['status'] = 0;
        }

        return $result;
    }
    public function setDefaultDns()
    {
        $data = [
            'SLD' => getDomainName($this->name),
            'TLD' => getDomainTld($this->name),
        ];
        $proxyUrl = '/namecheap/domains/dns/set-default';

        $resp = $this->getProxyResponse($proxyUrl, $data);

        $result['status'] = $resp['status'];
        $result['data'] = $resp['result'];
        return $result;

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = Api::factory($this->getConfig(), 'domains.dns.setDefault');
        $command->setParams($data)->dispatch();

        if ($command->status() == 'ok') {
            $resp = $command->getResponse();

            $result['data'] = $resp->DomainDNSSetDefaultResult;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['data'] = $errors;
            $result['status'] = 0;
        }

        return $result;
    }

    public function getLocked()
    {
        $data = [
            'DomainName' => $this->name,
        ];
        $proxyUrl = '/namecheap/domains/get-lock';

        $resp = $this->getProxyResponse($proxyUrl, $data);
        $result['status'] = $resp['status'];
        $result['data'] = $resp['result'];

        return $result;

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = NamecheapExtra::factory($this->getConfig(), 'domains.getLock');  
        $command->setParams($data)->dispatch();

        if ($command->status() == 'ok') {
            $result['data'] = $command->domain;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['data'] = $errors;
            $result['status'] = 0;
        }

        return $result;
    }

    public function setHosts($request)
    {
        $data = [
            'SLD' => getDomainName($this->name),
            'TLD' => getDomainTld($this->name),
        ];
        foreach ($request->hosts as $key => $host) {
            $data['HostName'.($key + 1)] = $host;
            $data['TTL'.($key + 1)] = $request->ttls[$key];
            $data['RecordType'.($key + 1)] = $request->types[$key];
            if (in_array($request->types[$key], ['MX', 'MXE', 'FRAME'])) {
                $data['Address'.($key + 1)] = explode(" ", $request->values[$key])[1];
                $data['MXPref'.($key + 1)] = explode(" ", $request->values[$key])[0];
                $data['EmailType'] = $request->types[$key];
            } else {
                $data['Address'.($key + 1)] = $request->values[$key];
            }
        }
        $proxyUrl = '/namecheap/domains/dns/set-hosts';
        $resp = $this->getProxyResponse($proxyUrl, $data);
        if ($resp['status'] == 1) {
            $result['data'] = $resp['command'];
            $result['status'] = 1;
        } else {
            $result['data'] = $resp['result'];
            $result['status'] = 0;
        }
        return $result;

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = Api::factory($this->getConfig(), 'domains.dns.setHosts');
        $command->setParams($data)->dispatch();
        if ($command->status() == 'ok') {
            $result['data'] = $command;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['data'] = $errors;
            $result['status'] = 0;
        }

        return $result;
    }
    public function switchLocked($status, $name = null)
    {
        $data = [
            'DomainName' => $name ?? $this->name,
            'LockAction' => $status == 'true'? 'LOCK': 'UNLOCK',
        ];
        $proxyUrl = '/namecheap/domains/set-lock';
        return $this->getProxyResponse($proxyUrl, $data);

        /**
         * @deprecated: new proxy server for static ip
         */
        $command = NamecheapExtra::factory($this->getConfig(), 'domains.setLock');
        $command->setParams($data)->dispatch();

        if ($command->status() == 'ok') {
            $result['result'] = $command->domain;
            $result['status'] = 1;
        } else {
            if ($command->status() == 'error') {
                $errors[] = $command->errorMessage;
            } else {
                $errors[] = 'Connection error! Sorry for inconvenience.';
            }
            $result['result'] = $errors;
            $result['status'] = 0;
        }

        return $result;
    }
    public function getDatatable($status, $user)
    {
        $now = Carbon::now()->toDateString();
        switch ($status) {
            case 0:
                $domains = $this->with('user')->select('*');

                break;

            case 1:
                $domains = $this->with('user')->where('expired_at', ">", $now)->select('*');

                break;

            case 2:
                $domains = $this->with('user')->where('expired_at', "<=", $now)->select('*');

                break;

            case 3:
                $domains = $this->with('user')->where('transfered', 1)->select('*');

                break;

            default:
                $domains = $this->with('user')->select('*');

                break;
        }
        if ($user != 'all') {
            $domains = $domains->where("user_id", $user);
        }

        return DataTables::of($domains)->editColumn('chargedAmountNC', function ($row) {
            return formatNumber($row->chargedAmountNC) ?? '';
        })->editColumn('created_at', function ($row) {
            return $row->created_at?->diffForHumans();
        })->addColumn('website', function ($row) {
            if ($row->web_id == null) {
                return '';
            } else {
                return "<a href='".route('admin.website.list.show', $row->web_id)."'>{$row->website->name}</a>";
            }
        })->editColumn('chargedAmountBB', function ($row) {
            return formatNumber($row->chargedAmountBB) ?? '';
        })->addColumn('user', function ($row) {
            return "<a href='".route('admin.userManage.detail', $row->user_id ?? 0)."'>{$row->user->name}</a>";
        })->addColumn('action', function ($row) use ($user) {
            if ($user == 'all') {
                return '<a href="#/renew" data-id="'.$row->id.'" data-area="renew" class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon renew_btn">
                        <span>
                            <i class="la la-refresh"></i>
                            <span>Renew</span>
                        </span>
                    </a>
                    <a href="'.route('admin.domainList.show', $row->id).'" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>';
            } else {
                return '
                    <a href="'.route('admin.domainList.show', $row->id).'" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>';
            }
        })->rawColumns(['checkbox', 'action', 'user', 'website'])->make(true);
    }

    public function getProxyResponse($url, $data) {
        try {
            $server = config('proxy.server').'/api';
            $token = config('proxy.token');
            $namecheapConfig = optional(option("namecheap", []));
            $payload = [
                'data'      =>  $data,
                'config'    =>  [
                    'apiUser' => $namecheapConfig['user'],
                    'apiKey' => $namecheapConfig['key'],
                    'clientIp' => $namecheapConfig['ip'],
                    'sandbox' => $namecheapConfig['sandbox'],
                ],
            ];
    
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post($server.$url, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                if ($responseData['status'] == 0) {
                    return [
                        'status' => 0,
                        'result' => $responseData['result']
                    ];
                } else {
                    $respData = [];
                    foreach ($responseData['result'] as $key => $value) {
                        if ($key == '@attributes') {
                            // Assign the first array to $formatedRes[0]
                            $respData[0] = $value;
                        } else {
                            // Assign other arrays to their respective keys
                            $respData[$key] = $value;
                        }
                    }

                    return [
                        'status' => 1,
                        'result' => $respData,
                        'command' => $responseData['command'] ?? null
                    ];
                }
            } else {
                return [
                    'status' => 0,
                    'result' => ['Connection error! Sorry for inconvenience.']
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 0,
                'result' => [$e->getMessage()],
            ];
        }
    }
}
