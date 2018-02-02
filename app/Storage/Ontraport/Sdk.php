<?php

namespace App\Storage\Ontraport;

use Exception;
use GuzzleHttp\Client;

/**
 * Application layer of Ontraport library.
 *
 * @author kzap
 */
class Sdk
{
    /* Properties
    -------------------------------*/
    private $config = null;
    public $ontraport = null;
    public $client = null;
    public $apiVersion = 1;
    public $objectTypeIds = array(
        0 => 'Contact',
        10002 => 'Shoppers', //Not in use
        10008 => 'Customers',
        10009 => 'Brand Associates',
        10010 => 'Offer + Customers'
    );

    protected $api_key;

    protected $api_id;

    protected $api_url = '';

    /* Magic Methods
    -------------------------------*/
    public function __construct($appId, $apiKey)
    {

        $this->api_id = $appId;
        $this->api_key = $apiKey;

        // define constant and get config
        $this->client = new \GuzzleHttp\Client([
            'base_url' => 'https://api.ontraport.com/'.$this->apiVersion.'/',
            'defaults' => [
                'headers' => [
                    'Api-Appid' => $this->api_id,
                    'Api-Key' => $this->api_key,
                ],
            ],

        ]);
    }

    /* Utility Methods
    -------------------------------*/

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function createObject($params = array())
    {
        // get and validate objectTypeId
        $objectTypeId = false;
        // get objectTypeId by object_type_name
        if (isset($params['object_type_name'])) {
            $objectTypeId = $this->getObjectTypeByName($params['object_type_name']);
        }
        // get objectTypeId by object_type_id
        if (isset($params['object_type_id']) && isset($this->objectTypeIds[$params['object_type_id']])) {
            $objectTypeId = $params['object_type_id'];
        }
        // get objectTypeId by objectID
        if (isset($params['objectID'])) {
            $objectTypeId = $params['objectID'];
        }
        if (is_null($objectTypeId) || $objectTypeId === false) {
            throw new Exception(__METHOD__.' needs a valid Object Type ID');
        }

        $body = array();
        $body['objectID'] = (int) $objectTypeId;
        foreach ($params as $fieldName => $fieldValue) {
            $paramsToIgnore = array(
                'objectID',
                'id',
                'object_type_name',
                'object_type_id',
            );
            if (!in_array($fieldName, $paramsToIgnore)) {
                $body[$fieldName] = $fieldValue;
            }
        }
        
        $header = array(
            'Api-Appid' => $this->api_id,
            'Api-Key'   => $this->api_key);    

        $args = array(
            'headers' => $header, 
   //         'body' => $body,
            'form_params' => $body,
            'verify' => false);

        try {
             //$response = $client->request('GET', 'https://api.ontraport.com/1/objects'); 
            $response = $this->client->post('https://api.ontraport.com/1/objects', $args);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return json_decode($response->getBody());    
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function getObject($params = array())
    {
        // get and validate objectTypeId
        $objectTypeId = false;
        $id = $params['id'];
        // get objectTypeId by object_type_name
        if (isset($params['object_type_name'])) {
            $objectTypeId = $this->getObjectTypeByName($params['object_type_name']);
        }
        // get objectTypeId by object_type_id
        if (isset($params['object_type_id']) && isset($this->objectTypeIds[$params['object_type_id']])) {
            $objectTypeId = $params['object_type_id'];
        }
        // get objectTypeId by objectID
        if (isset($params['objectID'])) {
            $objectTypeId = $params['objectID'];
        }
        if (is_null($objectTypeId) || $objectTypeId === false) {
            throw new Exception(__METHOD__.' needs a valid Object Type ID');
        }

        $header = array(
            'Accept'    => 'application/json',
            'Api-Appid' => $this->api_id,
            'Api-Key'   => $this->api_key,
            'Host'      => 'api.ontraport.com');        
        $client = new Client(array(
            'headers' => $header, 
            'query' => ['objectID' => (int) $objectTypeId, 'id' => (int) $id],
            'verify' => false));

        try {
             $response = $client->request('GET', 'https://api.ontraport.com/1/object'); 
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return json_decode($response->getBody());
      //  return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function getObjects($params = array())
    {
        // get and validate objectTypeId
        $objectTypeId = false;
        // get objectTypeId by object_type_name
        if (isset($params['object_type_name'])) {
            $objectTypeId = $this->getObjectTypeByName($params['object_type_name']);
        }
        // get objectTypeId by object_type_id
        if (isset($params['object_type_id']) && isset($this->objectTypeIds[$params['object_type_id']])) {
            $objectTypeId = $params['object_type_id'];
        }
        // get objectTypeId by objectID
        if (isset($params['objectID'])) {
            $objectTypeId = $params['objectID'];
        }
        if (is_null($objectTypeId) || $objectTypeId === false) {
            throw new Exception(__METHOD__.' needs a valid Object Type ID');
        }

        $paramQuery = array();

        /////

        $paramQuery['objectID'] = (int) $objectTypeId;

        if (isset($params['ids'])) {
            if (is_array($params['ids'])) {
                $paramQuery['ids'] = implode(',', $params['ids']);
            } else {
                $paramQuery['ids'] = $params['ids'];
            }
        }

        if (isset($params['start'])) {
            $paramQuery['start'] = $params['start'];
        }
        if (isset($params['range'])) {
            $paramQuery['range'] = $params['range'];
        }
        if (isset($params['condition'])) {
            if (is_array($params['condition'])) {
                $paramQuery['condition'] = '('.implode(') AND (', $params['condition']).')';
            } else {
                $paramQuery['condition'] = $params['condition'];
            }
        }

        $header = array(
            'Api-Appid' => $this->api_id,
            'Api-Key'   => $this->api_key);    

        $args = array(
            'headers' => $header, 
            'query' => $paramQuery,
            'verify' => false);

        try {
             //$response = $client->request('GET', 'https://api.ontraport.com/1/objects'); 
            $response = $this->client->get('https://api.ontraport.com/1/objects', $args);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return json_decode($response->getBody());
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function updateObject($params = array())
    {
        // get and validate objectTypeId
        $objectTypeId = false;
        // get objectTypeId by object_type_name
        if (isset($params['object_type_name'])) {
            $objectTypeId = $this->getObjectTypeByName($params['object_type_name']);
        }
        // get objectTypeId by object_type_id
        if (isset($params['object_type_id']) && isset($this->objectTypeIds[$params['object_type_id']])) {
            $objectTypeId = $params['object_type_id'];
        }
        // get objectTypeId by objectID
        if (isset($params['objectID'])) {
            $objectTypeId = $params['objectID'];
        }
        if (is_null($objectTypeId) || $objectTypeId === false) {
            throw new Exception(__METHOD__.' needs a valid Object Type ID');
        }

        $body = array();
        $body['objectID'] = (int) $objectTypeId;
        $body['id'] = (int) $params['id'];
        foreach ($params as $fieldName => $fieldValue) {
            $paramsToIgnore = array(
                'objectID',
                'id',
                'object_type_name',
                'object_type_id',
            );
            if (!in_array($fieldName, $paramsToIgnore)) {
                $body[$fieldName] = $fieldValue;
            }
        }
        
        $header = array(
            'Api-Appid' => $this->api_id,
            'Api-Key'   => $this->api_key);    

        $args = array(
            'headers' => $header, 
            //'body' => $body,
            'form_params' => $body,
            'verify' => false);

        try {
             //$response = $client->request('GET', 'https://api.ontraport.com/1/objects'); 
            $response = $this->client->put('https://api.ontraport.com/1/objects', $args);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return json_decode($response->getBody());
    }

    /**
     * Convenience Method
     * Insert or Update Object based on Distinct properties
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function upsertObject($params = array())
    {
        // get and validate objectTypeId
        $objectTypeId = false;
        // get objectTypeId by object_type_name
        if (isset($params['object_type_name'])) {
            $objectTypeId = $this->getObjectTypeByName($params['object_type_name']);
        }
        // get objectTypeId by object_type_id
        if (isset($params['object_type_id']) && isset($this->objectTypeIds[$params['object_type_id']])) {
            $objectTypeId = $params['object_type_id'];
        }
        // get objectTypeId by objectID
        if (isset($params['objectID'])) {
            $objectTypeId = $params['objectID'];
        }
        if (is_null($objectTypeId) || $objectTypeId === false) {
            throw new Exception(__METHOD__.' needs a valid Object Type ID');
        }

        ////////
        $form_params = array();
        $form_params['objectID'] = (int) $objectTypeId;
        foreach ($params as $fieldName => $fieldValue) {
            $paramsToIgnore = array(
                'objectID',
                'id',
                'object_type_name',
                'object_type_id',
            );
            if (!in_array($fieldName, $paramsToIgnore)) {
                $form_params[$fieldName] = $fieldValue;
            }
        }

        $header = array(
            'Api-Appid' => $this->api_id,
            'Api-Key'   => $this->api_key);    

        $args = array(
            'headers' => $header, 
            'form_params' => $form_params,
            'verify' => false);

        try {
             //$response = $client->request('GET', 'https://api.ontraport.com/1/objects'); 
            $response = $this->client->post('https://api.ontraport.com/1/objects/saveorupdate', $args);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return json_decode($response->getBody()); 
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function getObjectTypes($params = array())
    {
        // get and validate objectTypeId
        $objectTypeId = false;
        // get objectTypeId by object_type_name
        if (isset($params['object_type_name'])) {
            $objectTypeId = $this->getObjectTypeByName($params['object_type_name']);
        }
        // get objectTypeId by object_type_id
        if (isset($params['object_type_id']) && isset($this->objectTypeIds[$params['object_type_id']])) {
            $objectTypeId = $params['object_type_id'];
        }
        // get objectTypeId by objectID
        if (isset($params['objectID'])) {
            $objectTypeId = $params['objectID'];
        }
        
       // $request = $this->client->createRequest('GET', 'objects/meta');
       // $query = $request->getQuery();

        //////////
        $header = array(
            'Api-Appid' => $this->api_id,
            'Api-Key'   => $this->api_key);        
        $client = new Client(array(
            'headers' => $header, 
            'query' => ['objectID' => (int) $objectTypeId],
            'verify' => false));

        try {
             $response = $client->request('GET', 'https://api.ontraport.com/1/objects/meta'); 
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }
        //////////

        return json_decode($response->getBody());
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function updateObjectTags($params = array())
    {
        // get and validate objectTypeId
        $objectTypeId = false;
        // get objectTypeId by object_type_name
        if (isset($params['object_type_name'])) {
            $objectTypeId = $this->getObjectTypeByName($params['object_type_name']);
        }
        // get objectTypeId by object_type_id
        if (isset($params['object_type_id']) && isset($this->objectTypeIds[$params['object_type_id']])) {
            $objectTypeId = $params['object_type_id'];
        }
        // get objectTypeId by objectID
        if (isset($params['objectID'])) {
            $objectTypeId = $params['objectID'];
        }
        if (is_null($objectTypeId) || $objectTypeId === false) {
            throw new Exception(__METHOD__.' needs a valid Object Type ID');
        }

        $body = array();
        $body['objectID'] = (int) $objectTypeId;
        if (isset($params['ids'])) {
            if (is_array($params['ids'])) {
                $body['ids'] = implode(',', $params['ids']);
            } else {
                $body['ids'] = $params['ids'];
            }
        }
        if (isset($params['start'])) {
            $body['start'] = $params['start'];
        }
        if (isset($params['range'])) {
            $body['range'] = $params['range'];
        }
        if (isset($params['condition'])) {
            if (is_array($params['condition'])) {
                $body['condition'] = '('.implode(') AND (', $params['condition']).')';
            } else {
                $body['condition'] = $params['condition'];
            }
        }
        $request = $this->client->createRequest('PUT', 'objects/tag', ['body' => $body]);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function deleteObjectTags($params = array())
    {
        // get and validate objectTypeId
        $objectTypeId = false;
        // get objectTypeId by object_type_name
        if (isset($params['object_type_name'])) {
            $objectTypeId = $this->getObjectTypeByName($params['object_type_name']);
        }
        // get objectTypeId by object_type_id
        if (isset($params['object_type_id']) && isset($this->objectTypeIds[$params['object_type_id']])) {
            $objectTypeId = $params['object_type_id'];
        }
        // get objectTypeId by objectID
        if (isset($params['objectID'])) {
            $objectTypeId = $params['objectID'];
        }
        if (is_null($objectTypeId) || $objectTypeId === false) {
            throw new Exception(__METHOD__.' needs a valid Object Type ID');
        }

        $body = array();
        $body['objectID'] = (int) $objectTypeId;
        if (isset($params['ids'])) {
            if (is_array($params['ids'])) {
                $body['ids'] = implode(',', $params['ids']);
            } else {
                $body['ids'] = $params['ids'];
            }
        }
        if (isset($params['start'])) {
            $body['start'] = $params['start'];
        }
        if (isset($params['range'])) {
            $body['range'] = $params['range'];
        }
        if (isset($params['condition'])) {
            if (is_array($params['condition'])) {
                $body['condition'] = '('.implode(') AND (', $params['condition']).')';
            } else {
                $body['condition'] = $params['condition'];
            }
        }
        $request = $this->client->createRequest('DELETE', 'objects/tag', ['body' => $body]);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function getForm($params = array())
    {
        $request = $this->client->createRequest('GET', 'form');
        $query = $request->getQuery();
        $query->set('id', (int) $params['id']);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function getMessage($params = array())
    {
        $request = $this->client->createRequest('GET', 'message');
        $query = $request->getQuery();
        $query->set('id', (int) $params['id']);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function taskCancel($params = array())
    {
        $body = array();
        $body['objectID'] = 0;
        if (isset($params['ids'])) {
            if (is_array($params['ids'])) {
                $body['ids'] = implode(',', $params['ids']);
            } else {
                $body['ids'] = $params['ids'];
            }
        }
        if (isset($params['start'])) {
            $body['start'] = $params['start'];
        }
        if (isset($params['range'])) {
            $body['range'] = $params['range'];
        }
        if (isset($params['condition'])) {
            if (is_array($params['condition'])) {
                $body['condition'] = '('.implode(') AND (', $params['condition']).')';
            } else {
                $body['condition'] = $params['condition'];
            }
        }
        $request = $this->client->createRequest('POST', 'task/cancel', ['body' => $body]);
        
        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function taskComplete($params = array())
    {
        $body = array();
        $body['objectID'] = 0;
        if (isset($params['ids'])) {
            if (is_array($params['ids'])) {
                $body['ids'] = implode(',', $params['ids']);
            } else {
                $body['ids'] = $params['ids'];
            }
        }
        if (isset($params['start'])) {
            $body['start'] = $params['start'];
        }
        if (isset($params['range'])) {
            $body['range'] = $params['range'];
        }
        if (isset($params['condition'])) {
            if (is_array($params['condition'])) {
                $body['condition'] = '('.implode(') AND (', $params['condition']).')';
            } else {
                $body['condition'] = $params['condition'];
            }
        }
        $request = $this->client->createRequest('POST', 'task/complete', ['body' => $body]);
        
        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function createTransaction($params = array())
    {
        $body = array();
        $body['objectID'] = 0;
        foreach ($params as $fieldName => $fieldValue) {
            $paramsToIgnore = array(
                'objectID',
                'id',
                'object_type_name',
                'object_type_id',
            );
            if (!in_array($fieldName, $paramsToIgnore)) {
                $body[$fieldName] = $fieldValue;
            }
        }
        $request = $this->client->createRequest('POST', 'transaction/processManual', ['body' => $body]);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function refundTransaction($params = array())
    {
        $body = array();
        $body['objectID'] = 0;
        if (isset($params['ids'])) {
            if (is_array($params['ids'])) {
                $body['ids'] = implode(',', $params['ids']);
            } else {
                $body['ids'] = $params['ids'];
            }
        }
        if (isset($params['start'])) {
            $body['start'] = $params['start'];
        }
        if (isset($params['range'])) {
            $body['range'] = $params['range'];
        }
        if (isset($params['condition'])) {
            if (is_array($params['condition'])) {
                $body['condition'] = '('.implode(') AND (', $params['condition']).')';
            } else {
                $body['condition'] = $params['condition'];
            }
        }
        $request = $this->client->createRequest('PUT', 'transaction/refund', ['body' => $body]);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function declineTransaction($params = array())
    {
        $body = array();
        $body['id'] = (int) $params['id'];
        $request = $this->client->createRequest('PUT', 'transaction/convertToDecline', ['body' => $body]);
        
        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function collectTransaction($params = array())
    {
        $body = array();
        $body['id'] = (int) $params['id'];
        $request = $this->client->createRequest('PUT', 'transaction/convertToCollections', ['body' => $body]);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function voidTransaction($params = array())
    {
        $body = array();
        $body['objectID'] = 0;
        if (isset($params['ids'])) {
            if (is_array($params['ids'])) {
                $body['ids'] = implode(',', $params['ids']);
            } else {
                $body['ids'] = $params['ids'];
            }
        }
        if (isset($params['start'])) {
            $body['start'] = $params['start'];
        }
        if (isset($params['range'])) {
            $body['range'] = $params['range'];
        }
        if (isset($params['condition'])) {
            if (is_array($params['condition'])) {
                $body['condition'] = '('.implode(') AND (', $params['condition']).')';
            } else {
                $body['condition'] = $params['condition'];
            }
        }
        $request = $this->client->createRequest('PUT', 'transaction/void', ['body' => $body]);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function voidTransactionPurchase($params = array())
    {
        $body = array();
        $body['id'] = (int) $params['id'];
        $request = $this->client->createRequest('PUT', 'transaction/voidPurchase', ['body' => $body]);
        
        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function rerunTransactionCommission($params = array())
    {
        $body = array();
        $body['objectID'] = 0;
        if (isset($params['ids'])) {
            if (is_array($params['ids'])) {
                $body['ids'] = implode(',', $params['ids']);
            } else {
                $body['ids'] = $params['ids'];
            }
        }
        if (isset($params['start'])) {
            $body['start'] = $params['start'];
        }
        if (isset($params['range'])) {
            $body['range'] = $params['range'];
        }
        if (isset($params['condition'])) {
            if (is_array($params['condition'])) {
                $body['condition'] = '('.implode(') AND (', $params['condition']).')';
            } else {
                $body['condition'] = $params['condition'];
            }
        }
        $request = $this->client->createRequest('PUT', 'transaction/rerunCommission', ['body' => $body]);
        
        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function markTransactionPaid($params = array())
    {
        $body = array();
        $body['id'] = (int) $params['id'];
        $request = $this->client->createRequest('PUT', 'transaction/markPaid', ['body' => $body]);
        
        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function rerunTransaction($params = array())
    {
        $body = array();
        $body['objectID'] = 0;
        if (isset($params['ids'])) {
            if (is_array($params['ids'])) {
                $body['ids'] = implode(',', $params['ids']);
            } else {
                $body['ids'] = $params['ids'];
            }
        }
        if (isset($params['start'])) {
            $body['start'] = $params['start'];
        }
        if (isset($params['range'])) {
            $body['range'] = $params['range'];
        }
        if (isset($params['condition'])) {
            if (is_array($params['condition'])) {
                $body['condition'] = '('.implode(') AND (', $params['condition']).')';
            } else {
                $body['condition'] = $params['condition'];
            }
        }
        $request = $this->client->createRequest('POST', 'transaction/rerun', ['body' => $body]);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function writeTransactionOff($params = array())
    {
        $body = array();
        $body['id'] = (int) $params['id'];
        $request = $this->client->createRequest('PUT', 'transaction/writeOff', ['body' => $body]);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function getTransaction($params = array())
    {
        $request = $this->client->createRequest('GET', 'transaction/order');
        $query = $request->getQuery();
        $query->set('id', (int) $params['id']);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function updateTransaction($params = array())
    {
        $body = array();
        $body['objectID'] = 0;
        foreach ($params as $fieldName => $fieldValue) {
            $paramsToIgnore = array(
                'objectID',
                'id',
                'object_type_name',
                'object_type_id',
            );
            if (!in_array($fieldName, $paramsToIgnore)) {
                $body[$fieldName] = $fieldValue;
            }
        }
        $request = $this->client->createRequest('PUT', 'transaction/order', ['body' => $body]);
        
        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    /**
     * Uses New API.
     *
     * @param array parameters
     *
     * @return scalar json response
     */
    public function resendTransactionInvoice($params = array())
    {
        $body = array();
        $body['objectID'] = 0;
        foreach ($params as $fieldName => $fieldValue) {
            $paramsToIgnore = array(
                'objectID',
                'id',
                'object_type_name',
                'object_type_id',
            );
            if (!in_array($fieldName, $paramsToIgnore)) {
                $body[$fieldName] = $fieldValue;
            }
        }
        $request = $this->client->createRequest('POST', 'transaction/resendInvoice', ['body' => $body]);

        try {
            $response = $this->client->send($request);
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
            }
        }

        return $response->json();
    }

    public function getObjectTypeByName($name)
    {

        // sanitize name
        $objectTypeName = strtolower($name);

        // sanitize objectTypeIds
        $objectTypeIds = array_map('strtolower', $this->objectTypeIds);

        $objectKey = array_search($objectTypeName, $objectTypeIds);
        if ($objectKey !== false) {
            return $objectKey;
        }

        return false;
    }
}
