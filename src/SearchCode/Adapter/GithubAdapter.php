<?php

namespace SearchCode\Adapter;

use SearchCode\AdapterInterface;
use SearchCode\Exception\AdapterException;
use SearchCode\Adapter\AbstractAdapter;
use Symfony\Component\HttpFoundation\Request;
use Github;

class GithubAdapter extends AbstractAdapter implements AdapterInterface 
{
    private $github;
    private $token;

    public function __construct() {
        $this->github = new \Github\Client();
    }

    public function configure() : AdapterInterface {
        if(!getenv('GITHUB_TOKEN')){
            throw new AdapterException(
                'The requested adapter needs "GITHUB_TOKEN", please provider your token.'
            );
        }
        $this->token = getenv('GITHUB_TOKEN');
        return $this;
    }

    public function authenticate() : AdapterInterface {
        $this->github->authenticate($this->token, null, Github\Client::AUTH_HTTP_TOKEN);
        return $this;
    }

    public function parseRequest(Request $request) : AbstractAdapter {
        if(!$request->get('q')) {
            throw new AdapterException(
                'The query string parameter "q", can not be invalid.'
            );
        }
        
        $this->query = $request->get('q');
        $this->sort  = $request->get('sort') ? $request->get('sort'): $this->sort;
        $this->page  = $request->get('page') ? $request->get('page'): $this->page;
        $this->hits  = $request->get('hits') ? $request->get('hits'): $this->hits;
        
        return $this;
    }

    public function searchCode() : Array {
        $paginator = new Github\ResultPager($this->github);
        $searchObj = $this->github->api('search');

        $searchObj->setPage($this->page);
        $searchObj->setPerPage($this->hits);

        $data = $paginator->fetch($searchObj, 'code', array($this->query, $this->sort));

        $data = $this->parseReturn($data['items']);

        return $data;
    }

    private function parseReturn(Array $data) : Array {
        $returnData = array();
        foreach($data as $key => $value) {
            $returnData[$key]['owner_name']      = $data[$key]['repository']['owner']['login'];
            $returnData[$key]['repository_name'] = $data[$key]['repository']['name'];
            $returnData[$key]['file_name']       = $data[$key]['name'];
        }
        return $returnData;
    }
}